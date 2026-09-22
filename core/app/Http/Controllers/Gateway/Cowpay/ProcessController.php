<?php

namespace App\Http\Controllers\Gateway\Cowpay;

use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessController extends Controller
{
    /*
     * Payment Initiate Process
     */
    public static function process($deposit)
    {
        $cowpayAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // ১. মূল ডাটাগুলো সাজানো
        // টাকার পরিমাণ দশমিকের পর ২ ঘর পর্যন্ত ফিক্স করা হলো (যেমন: 100.00)
        $amount = number_format($deposit->final_amount, 2, '.', '');

        $innerParams = [
            'merchant_code' => $cowpayAcc->merchant_code,
            'country_code'  => $cowpayAcc->country_code,
            'order_no'      => $deposit->trx,
            'order_amount'  => $amount,
            'pay_type'      => $cowpayAcc->pay_type,
            'notify_url'    => route('ipn.Cowpay'),
            'return_url'    => route('user.home'),
        ];

        // ২. সিগনেচার তৈরি করা
        $signature = self::generateSignature($innerParams, $cowpayAcc->secret_key);

        // ৩. ফাইনাল স্ট্রাকচার
        $finalPayload = [
            'signtype'  => 'MD5',       
            'sign'      => $signature,
            'transdata' => $innerParams 
        ];

        // ৪. URL এবং Request
        $baseUrl = rtrim($cowpayAcc->base_url, '/');
        $url = $baseUrl . '/pay';
        $data_string = json_encode($finalPayload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        try {
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                $send['error'] = true;
                $send['message'] = 'CURL Error: ' . $curlError;
                return json_encode($send);
            }

            $result = json_decode($response);

            if ($result && isset($result->code) && $result->code == "0" && $result->status == true) {
                // পেমেন্ট লিংক খোঁজা হচ্ছে
                $payUrl = $result->pay_url ?? ($result->transdata->pay_url ?? null);

                if ($payUrl) {
                    $send['redirect'] = true;
                    $send['redirect_url'] = $payUrl;
                } else {
                    $send['error'] = true;
                    $send['message'] = 'Pay URL not found in response';
                }
            } else {
                $send['error'] = true;
                $send['message'] = 'API Response: ' . $response;
            }

        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = 'System Error: ' . $e->getMessage();
        }

        return json_encode($send);
    }

    /*
     * IPN Callback Handler
     */
    public function ipn(Request $request)
    {
        $data = $request->all();
        
        if (!isset($data['transdata']) || !isset($data['sign'])) {
            return 'fail';
        }

        $transdata = $data['transdata'];
        if (is_string($transdata)) {
            $transdata = json_decode($transdata, true);
        }

        $incomingSign = $data['sign'];

        $track = $transdata['order_no'] ?? null;
        if (!$track) return 'fail';

        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if (!$deposit) return 'fail';

        $cowpayAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        
        $generatedSign = self::generateSignature($transdata, $cowpayAcc->secret_key);

        $status = $transdata['order_status'] ?? ($transdata['status'] ?? '');

        // সিগনেচার চেকিং (উভয়ই আপারকেস করে চেক করা হচ্ছে)
        if (strtoupper($incomingSign) === strtoupper($generatedSign) && $status == 'success') {
            PaymentController::userDataUpdate($deposit);
            return 'success';
        }
        return 'fail';
    }

    /*
     * Signature Logic (Updated)
     */
    public static function generateSignature($params, $secretKey)
    {
        ksort($params);
        $signStr = "";
        foreach ($params as $key => $val) {
            if ($val !== "" && $val !== null && $key !== "sign" && !is_array($val)) {
                $signStr .= $key . "=" . $val . "&";
            }
        }
        $signStr .= "key=" . $secretKey;
        
        // ফিক্স: MD5 হ্যাশকে Uppercase (বড় হাতের) করা হয়েছে ডকুমেন্টেশন অনুযায়ী
        return strtoupper(md5($signStr));
    }
}