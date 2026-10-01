<?php

namespace App\Http\Controllers\Gateway\Akpay;

use App\Models\Deposit;
use App\Models\User;
use App\Models\Transaction;
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
        \Log::info('========== AKPAY PROCESS STARTED ==========');
        \Log::info('Deposit Data:', [
            'trx' => $deposit->trx,
            'amount' => $deposit->amount,
            'final_amount' => $deposit->final_amount,
            'user_id' => $deposit->user_id,
            'status' => $deposit->status
        ]);
        
        // Get gateway currency and its parameters
        $gatewayCurrency = $deposit->gatewayCurrency();
        
        \Log::info('Gateway Currency Data:', ['gatewayCurrency' => $gatewayCurrency]);
        
        // Check if gatewayCurrency exists
        if (!$gatewayCurrency) {
            \Log::error('Gateway Currency not found!');
            $send['error'] = true;
            $send['message'] = 'Gateway configuration not found';
            return json_encode($send);
        }
        
        $account = self::accountConfig($gatewayCurrency);
        if (!$account) {
            \Log::error('Akpay merchant is not configured in admin');
            $send['error'] = true;
            $send['message'] = 'Payment gateway is not configured';
            return json_encode($send);
        }

        $mchId = $account['mchId'];
        $secretKey = $account['secret_key'];
        $payType = 'bkash';
        $namedType = false;

        $gatewayName = $gatewayCurrency->name ?? '';
        \Log::info('Gateway Name:', ['name' => $gatewayName]);

        if (stripos($gatewayName, 'NAGAD') !== false) {
            $payType = 'nagad';
            $namedType = true;
        } elseif (stripos($gatewayName, 'BKASH') !== false) {
            $payType = 'bkash';
            $namedType = true;
        } elseif (stripos($gatewayName, 'ROCKET') !== false) {
            $payType = 'rocket';
            $namedType = true;
        } elseif (!empty($account['pay_type'])) {
            $payType = $account['pay_type'];
        }

        // Final fallback: If still not set, try to detect from gateway_alias
        if (!$namedType && $payType == 'bkash' && isset($gatewayCurrency->gateway_alias)) {
            $gatewayAlias = strtolower($gatewayCurrency->gateway_alias);
            if (strpos($gatewayAlias, 'nagad') !== false) {
                $payType = 'nagad';
                \Log::info('pay_type detected from gateway_alias: nagad');
            } elseif (strpos($gatewayAlias, 'rocket') !== false) {
                $payType = 'rocket';
                \Log::info('pay_type detected from gateway_alias: rocket');
            }
        }
        
        \Log::info('Final Akpay Config:', [
            'mchId' => $mchId,
            'pay_type' => $payType,
            'gateway_name' => $gatewayName,
        ]);
        
        // API URL
        $apiUrl = 'https://www.akpay.space/v1/collect';
        \Log::info('API URL:', ['url' => $apiUrl]);
        
        $outTradeNo = $deposit->trx;
        $amount = number_format($deposit->final_amount, 2, '.', '');
        
        // Try multiple route name possibilities
        $notifyUrl = null;
        try {
            $notifyUrl = route('ipn.Akpay');
        } catch (\Exception $e) {
            \Log::warning('Route ipn.Akpay not found, trying ipn.akpay');
            try {
                $notifyUrl = route('ipn.akpay');
            } catch (\Exception $e) {
                \Log::error('Both routes not found! Using fallback URL');
                $notifyUrl = url('/ipn/akpay');
            }
        }
        
        $returnUrl = route('user.deposit.history');
        
        \Log::info('URLs:', [
            'notify_url' => $notifyUrl,
            'return_url' => $returnUrl
        ]);
        
        // Akpay data preparation
        $akpayData = [
            'mchId' => $mchId,
            'out_trade_no' => $outTradeNo,
            'money' => $amount,
            'pay_type' => $payType,
            'currency' => 'BDT',
            'notify_url' => $notifyUrl,
            'returnUrl' => $returnUrl
        ];
        
        \Log::info('Akpay Data before sign:', $akpayData);
        
        // Generate sign
        $akpayData['sign'] = self::createSign($akpayData, $secretKey);
        
        \Log::info('Final Akpay Request Data:', $akpayData);
        
        // CURL Request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($akpayData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        \Log::info('CURL Response:', [
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError
        ]);
        
        if ($curlError) {
            \Log::error('CURL Error: ' . $curlError);
            $send['error'] = true;
            $send['message'] = 'Connection error: ' . $curlError;
            return json_encode($send);
        }
        
        $responseData = json_decode($response, true);
        
        if (!$responseData) {
            \Log::error('Invalid JSON response: ' . $response);
            $send['error'] = true;
            $send['message'] = 'Invalid response from payment gateway';
            return json_encode($send);
        }
        
        \Log::info('Parsed Response:', $responseData);
        
        // Check response based on actual API response format
        if (isset($responseData['code']) && $responseData['code'] === 0) {
            if (isset($responseData['data']['payment_url']) && !empty($responseData['data']['payment_url'])) {
                \Log::info('Payment URL received:', ['url' => $responseData['data']['payment_url']]);
                $send['redirect'] = true;
                $send['redirect_url'] = $responseData['data']['payment_url'];
                return json_encode($send);
            } else {
                \Log::error('Payment URL missing in response', $responseData);
            }
        }
        
        // Error handling
        $errorMsg = isset($responseData['message']) ? $responseData['message'] : 'Payment initialization failed';
        \Log::error('Akpay Error Response:', [
            'code' => $responseData['code'] ?? 'unknown',
            'message' => $errorMsg,
            'full_response' => $responseData
        ]);
        
        $send['error'] = true;
        $send['message'] = $errorMsg;
        return json_encode($send);
    }
    
    /*
     * Create Sign
     */
    public static function createSign($params, $secretKey)
    {
        \Log::info('Creating sign with params:', $params);
        
        // Remove empty values and sign parameter
        foreach ($params as $k => $v) {
            if ($v === '' || $v === null || $k === 'sign') {
                unset($params[$k]);
            }
        }
        
        \Log::info('Params after removing empty values:', $params);
        
        // Sort by key
        ksort($params);
        \Log::info('Params after sorting:', $params);
        
        // Build query string
        $query = [];
        foreach ($params as $k => $v) {
            if ($v !== '' && $v !== null && !is_array($v)) {
                $query[] = "$k=$v";
            }
        }
        
        $string = implode('&', $query) . "&key=" . $secretKey;
        $sign = strtolower(md5($string));
        \Log::info('Generated MD5 sign:', ['sign' => $sign]);
        
        return $sign;
    }
    
    /*
     * IPN Callback Handler
     */
    public function ipn(Request $request)
    {
        \Log::info('========== AKPAY IPN CALLBACK RECEIVED ==========');
        \Log::info('IPN Request Method: ' . $request->method());
        \Log::info('IPN Request Headers:', $request->headers->all());
        \Log::info('IPN Request Data:', $request->all());
        
        $data = $request->all();
        
        // If no data in request->all(), try to get from raw input
        if (empty($data)) {
            $rawInput = $request->getContent();
            \Log::info('Empty request data, trying raw input:', ['raw' => $rawInput]);
            if (!empty($rawInput)) {
                parse_str($rawInput, $data);
                \Log::info('Parsed from raw input:', $data);
            }
        }
        
        // Validate required fields
        if (!isset($data['out_trade_no']) || !isset($data['sign'])) {
            \Log::error('IPN: Missing required fields', [
                'has_out_trade_no' => isset($data['out_trade_no']),
                'has_sign' => isset($data['sign']),
                'available_keys' => array_keys($data)
            ]);
            return 'fail';
        }
        
        \Log::info('IPN: Processing order', ['out_trade_no' => $data['out_trade_no']]);
        
        // Find deposit
        $deposit = Deposit::where('trx', $data['out_trade_no'])->first();
        
        if (!$deposit) {
            \Log::error('IPN: Deposit not found', ['trx' => $data['out_trade_no']]);
            return 'fail';
        }
        
        \Log::info('IPN: Deposit found', [
            'deposit_id' => $deposit->id,
            'user_id' => $deposit->user_id,
            'amount' => $deposit->amount,
            'status' => $deposit->status
        ]);
        
        // Check if already processed
        if ($deposit->status != 0) {
            \Log::info('IPN: Deposit already processed. Status: ' . $deposit->status);
            return 'success';
        }
        
        $gatewayCurrency = $deposit->gatewayCurrency();
        $account = self::accountConfig($gatewayCurrency);
        if (!$account) {
            \Log::error('IPN: Akpay merchant is not configured');
            return 'fail';
        }
        $secretKey = $account['secret_key'];
        
        // Verify signature
        $signParams = [];
        foreach ($data as $key => $value) {
            if ($key !== 'sign' && !is_array($value) && $value !== '') {
                $signParams[$key] = $value;
            }
        }
        
        \Log::info('IPN: Sign params for verification:', $signParams);
        
        $generatedSign = self::createSign($signParams, $secretKey);
        
        \Log::info('IPN: Signature comparison', [
            'received' => $data['sign'],
            'generated' => $generatedSign,
            'match' => ($generatedSign === $data['sign'])
        ]);
        
        if ($generatedSign !== $data['sign']) {
            \Log::error('IPN: Sign mismatch');
            return 'fail';
        }
        
        \Log::info('IPN: Signature verified successfully');
        
        // Check payment status
        $status = strtolower($data['status'] ?? '');
        $tradeStatus = strtolower($data['trade_status'] ?? '');
        
        \Log::info('IPN: Payment status', [
            'status' => $status,
            'trade_status' => $tradeStatus
        ]);
        
        $isSuccess = ($status == 'success' || $status == 'paid' || $tradeStatus == 'success' || $tradeStatus == 'paid');
        
        if ($isSuccess) {
            \Log::info('IPN: Payment successful, processing deposit');
            
            // Update deposit
            $deposit->status = 1;
            $deposit->save();
            
            // Update user balance
            $user = User::find($deposit->user_id);
            if ($user) {
                $oldBalance = $user->balance;
                $user->balance += $deposit->amount;
                $user->save();
                
                \Log::info('IPN: User balance updated', [
                    'user_id' => $user->id,
                    'old_balance' => $oldBalance,
                    'added_amount' => $deposit->amount,
                    'new_balance' => $user->balance
                ]);
                
                // Create transaction record
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $deposit->amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge = $deposit->charge;
                $transaction->trx_type = '+';
                $transaction->details = 'Deposit Via Akpay - Transaction ID: ' . ($data['trade_no'] ?? $data['out_trade_no']);
                $transaction->trx = $deposit->trx;
                $transaction->remark = 'deposit';
                $transaction->save();
                
                \Log::info('IPN: Transaction record created', ['transaction_id' => $transaction->id]);
            } else {
                \Log::error('IPN: User not found', ['user_id' => $deposit->user_id]);
            }
            
            \Log::info('========== AKPAY IPN PROCESSED SUCCESSFULLY ==========');
            return 'success';
        }
        
        \Log::warning('IPN: Payment not successful', ['status' => $status]);
        \Log::info('========== AKPAY IPN FAILED ==========');
        return 'fail';
    }

    private static function accountConfig($gatewayCurrency): ?array
    {
        if (!$gatewayCurrency) {
            return null;
        }

        $raw = $gatewayCurrency->gateway_parameter ?? null;
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }
        if (!is_array($raw)) {
            return null;
        }

        $mchId = trim((string) ($raw['mchId'] ?? ''));
        $secretKey = trim((string) ($raw['secret_key'] ?? ''));
        if ($mchId === '' || $secretKey === '') {
            return null;
        }

        return [
            'mchId' => $mchId,
            'secret_key' => $secretKey,
            'pay_type' => trim((string) ($raw['pay_type'] ?? '')),
        ];
    }
}
