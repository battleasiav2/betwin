<?php

namespace App\Http\Controllers\Gateway\BdPayInNagad;

use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;

class ProcessController extends Controller
{
    /*
     * Nagad Payment Process
     */
    public static function process($deposit)
    {
        $nagadAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        
        // 1. প্যারামিটার তৈরি
        $param = [
            'mchId'         => $nagadAcc->mch_id,
            'currency'      => 'BDT',
            'out_trade_no'  => $deposit->trx,
            'pay_type'      => $nagadAcc->pay_type, // Admin panel e 'NAGAD' thakbe
            'money'         => (string)round($deposit->final_amount), // Integer String
            'attach'        => '', 
            'notify_url'    => route('ipn.'.$deposit->gateway->alias), // ipn.BdPayInNagad
            
            // Iframe সমস্যা সমাধানের জন্য স্পেশাল রিটার্ন ইউআরএল
            'returnUrl'     => route('bdpayinnagad.return.success'), 
        ];

        // 2. সিগনেচার তৈরি (সঠিক লজিক সহ)
        $param['sign'] = self::generateSignature($param, $nagadAcc->secret_key);

        // 3. রিকোয়েস্ট পাঠানো
        $url = $nagadAcc->base_url . '/v1/Collect';

        try {
            $response = CurlRequest::curlPostContent($url, $param);
            $result = json_decode($response);

            if ($result && isset($result->code) && $result->code == 0) {
                $send['redirect'] = true;
                $send['redirect_url'] = $result->data->url;
            } else {
                $send['error'] = true;
                $send['message'] = isset($result->msg) ? $result->msg : 'Gateway Connection Error';
            }
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
        }

        return json_encode($send);
    }

    /*
     * IPN Callback Handler for Nagad
     */
    public function ipn(Request $request)
    {
        $track = $request->out_trade_no;
        if(!$track) return 'fail'; 

        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if (!$deposit) return 'fail';

        // স্ট্যাটাস চেক (1 = Success)
        if ($request->status != 1) return 'fail';

        $nagadAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // সিগনেচার ভেরিফিকেশন
        $verifyData = $request->except(['sign']);
        $generatedSign = self::generateSignature($verifyData, $nagadAcc->secret_key);

        if ($request->sign === $generatedSign) {
            PaymentController::userDataUpdate($deposit);
            return 'success'; // Plain text response
        }

        return 'fail';
    }

    /*
     * Success Return Page (Iframe Breakout Fix)
     * এটি লগইন পেজ লুপ সমস্যার সমাধান করবে এবং নগদের থিম কালার ব্যবহার করবে
     */
    public function returnSuccess()
    {
        $redirectUrl = route('user.home'); 

        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Nagad Payment Successful</title>
            <style>
                body { font-family: "Segoe UI", sans-serif; text-align: center; padding: 50px; background-color: #fcebeb; } /* Nagad reddish bg */
                .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: inline-block; max-width: 400px; width: 100%; }
                h3 { color: #e12727; margin-bottom: 15px; font-size: 24px; } /* Nagad Red */
                p { color: #666; font-size: 16px; margin-bottom: 25px; }
                .btn { display: inline-block; padding: 12px 30px; background-color: #e12727; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: 0.3s; }
                .btn:hover { background-color: #b51f1f; }
                .loader { border: 4px solid #f3f3f3; border-top: 4px solid #e12727; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 20px auto; }
                @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="loader"></div>
                <h3>Nagad Payment Successful!</h3>
                <p>We have received your payment via Nagad.</p>
                <p>Redirecting to dashboard...</p>
                <a href="'.$redirectUrl.'" class="btn">Go to Dashboard</a>
            </div>
            <script type="text/javascript">
                function redirect() {
                    var url = "'.$redirectUrl.'";
                    try {
                        // এই কোডটি Iframe ভেঙ্গে মেইন উইন্ডোতে রিডাইরেক্ট করবে
                        if (window.top !== window.self) { 
                            window.top.location.href = url; 
                        } else { 
                            window.location.href = url; 
                        }
                    } catch (e) { window.location.href = url; }
                }
                // ১ সেকেন্ড অপেক্ষা করে রিডাইরেক্ট
                setTimeout(redirect, 1000);
            </script>
        </body>
        </html>';
    }

    /*
     * Signature Logic Update
     * key=SECRET_KEY ফরম্যাট ব্যবহার করা হয়েছে
     */
    public static function generateSignature($params, $secretKey)
    {
        ksort($params); 
        $signStr = "";
        foreach ($params as $key => $val) {
            if ($val !== "" && $val !== null && $key !== "sign") {
                $signStr .= $key . "=" . $val . "&";
            }
        }
        // সঠিক ফরম্যাট: প্যারামিটারের শেষে &key=YOUR_SECRET
        $signStr .= "key=" . $secretKey; 
        return md5($signStr);
    }
}