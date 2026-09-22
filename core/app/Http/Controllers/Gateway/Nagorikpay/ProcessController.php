<?php

namespace App\Http\Controllers\Gateway\Nagorikpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Support\Facades\Http;

class ProcessController extends Controller
{
    /**
     * Redirect user to NagorikPay payment page
     */
    public static function process($deposit)
    {
        $gateway = Gateway::where('alias', 'Nagorikpay')->first();

        if (!$gateway) {
            abort(404, 'NagorikPay Gateway Not Found');
        }

        $apiKey = $gateway->parameters->api_key ?? '';

        $payload = [
            "cus_name"    => $deposit->user->fullname,
            "cus_email"   => $deposit->user->email,
            "amount"      => number_format($deposit->amount, 2, '.', ''),
            "success_url" => route('ipn.nagorikpay'),
            "cancel_url"  => route('user.deposit'),
            "webhook_url" => route('ipn.nagorikpay'),
            "metadata"    => [
                "deposit_id" => $deposit->id,
                "user_id"    => $deposit->user_id
            ]
        ];

        try {
            $response = Http::withHeaders([
                'API-KEY' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://secure-pay.nagorikpay.com/api/payment/create', $payload);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] == true) {
                return redirect($result['payment_url']);
            }

            return back()->withErrors([
                'error' => $result['message'] ?? 'NagorikPay Payment Initialization Failed'
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'NagorikPay Connection Error'
            ]);
        }
    }

    /**
     * IPN / Payment Verify
     */
    public function ipn(Request $request)
    {
        $transactionId = $request->transactionId;

        if (!$transactionId) {
            return redirect()->route('user.deposit')
                ->withErrors(['error' => 'Invalid Transaction']);
        }

        $gateway = Gateway::where('alias', 'Nagorikpay')->first();
        $apiKey  = $gateway->parameters->api_key ?? '';

        try {
            $verify = Http::withHeaders([
                'API-KEY' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://secure-pay.nagorikpay.com/api/payment/verify', [
                'transaction_id' => $transactionId
            ])->json();

            if (isset($verify['status']) && $verify['status'] === 'COMPLETED') {

                $depositId = $verify['metadata']['deposit_id'] ?? null;

                $deposit = Deposit::where('id', $depositId)
                    ->where('status', 0)
                    ->first();

                if ($deposit) {
                    $deposit->status = 1;
                    $deposit->save();

                    // Add balance to user
                    $user = $deposit->user;
                    $user->balance += $deposit->amount;
                    $user->save();
                }

                return redirect()->route('user.deposit')
                    ->with('success', 'Payment Successful');

            }

            return redirect()->route('user.deposit')
                ->withErrors(['error' => 'Payment Not Completed']);

        } catch (\Exception $e) {
            return redirect()->route('user.deposit')
                ->withErrors(['error' => 'NagorikPay Verification Error']);
        }
    }
}
