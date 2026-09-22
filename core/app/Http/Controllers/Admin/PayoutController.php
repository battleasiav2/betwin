<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    // 商户ID
    private $merchantId = '5043';

    // 签名密钥
    private $secretKey  = '332884a254de2beca9c9de435c806106';

    // 代付接口地址
    private $apiUrl     = 'https://api.wpay.life/v1/Payout';

    // 回调地址
    private $notifyUrl  = 'https://micro.ringid.fun/callback/payout';

    // 代付表单页面
    public function index()
    {
        $pageTitle = 'Instant Payout';
        return view('admin.payout', compact('pageTitle'));
    }

    // 面板密码表单
    public function showPanelPasswordForm()
    {
        $pageTitle = 'Payout Security';
        return view('admin.payout.panel_password', compact('pageTitle'));
    }

    // 校验面板密码
    public function checkPanelPassword(Request $request)
    {
        $request->validate([
            'panel_password' => 'required|digits:6',
        ]);

        if (!hash_equals(config('payout_security.panel_password'), $request->panel_password)) {
            $notify[] = ['error', 'Invalid payout panel password'];
            return back()->withNotify($notify);
        }

        session(['payout_panel_ok' => true]);

        return redirect()->route('admin.payout');
    }

    // 提交代付
    public function send(Request $request)
    {
        $request->validate([
            'channel'      => 'required|in:BKASH,NAGAD',
            'account'      => 'required|string|min:8|max:20',
            'amount'       => 'required|numeric|min:1',
            'user_name'    => 'nullable|string|max:50',
            'security_pin' => 'required|digits:4',
        ]);

        if (!hash_equals(config('payout_security.form_pin'), $request->security_pin)) {
            $msg      = 'Invalid Security PIN';
            $notify[] = ['error', $msg];

            return back()
                ->withNotify($notify)
                ->with('error', $msg)
                ->withInput();
        }

        // 商户订单号
        $outTradeNo = 'WD' . time() . rand(1000, 9999);

        // 请求参数
        $data = [
            'mchId'        => $this->merchantId,
            'currency'     => 'BDT',
            'out_trade_no' => $outTradeNo,
            'pay_type'     => $request->channel,
            'account'      => $request->account,
            'userName'     => $request->user_name ?: 'admin',
            'money'        => (string) intval(round($request->amount)),
            'attach'       => '',
            'notify_url'   => $this->notifyUrl,
        ];

        // 生成签名
        $data['sign'] = $this->makeSign($data);

        try {
            Log::info('Payout Request:', $data);

            // 调用接口
            $ch = curl_init($this->apiUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
            ]);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);

                Log::error('Payout cURL Error: ' . $error);

                $msg      = 'API সংযোগে সমস্যা: ' . $error;
                $notify[] = ['error', $msg];

                return back()
                    ->withNotify($notify)
                    ->with('error', $msg)
                    ->with('api_raw', $error);
            }

            curl_close($ch);

            // 解析响应
            $json = json_decode($response, true);

            Log::info('Payout Response:', ['raw' => $response, 'json' => $json]);

            if (is_array($json) && isset($json['code'])) {
                if ((int) $json['code'] === 0) {
                    // 成功
                    $txnId = $json['data']['transaction_Id'] ?? 'N/A';
                    $msg   = 'Payout সফলভাবে সাবমিট হয়েছে। Txn ID: ' . $txnId;

                    $notify[] = ['success', $msg];

                    return back()
                        ->withNotify($notify)
                        ->with('success', $msg)
                        ->with('api_raw', $response);
                } else {
                    // 业务失败
                    $apiMsg = $json['msg'] ?? 'Unknown error';
                    $msg    = 'Payout Failed: ' . $apiMsg;

                    $notify[] = ['error', $msg];

                    return back()
                        ->withNotify($notify)
                        ->with('error', $msg)
                        ->with('api_raw', $response);
                }
            } else {
                // 非法响应
                $msg      = 'অকার্যকর API Response: ' . $response;
                $notify[] = ['error', $msg];

                return back()
                    ->withNotify($notify)
                    ->with('error', $msg)
                    ->with('api_raw', $response);
            }
        } catch (\Throwable $e) {
            Log::error('Payout Exception: ' . $e->getMessage());

            $msg      = 'সিস্টেম ত্রুটি: ' . $e->getMessage();
            $notify[] = ['error', $msg];

            return back()
                ->withNotify($notify)
                ->with('error', $msg)
                ->with('api_raw', $e->getMessage());
        }
    }

    // 生成签名字符串
    private function makeSign(array $params): string
    {
        unset($params['sign']);

        // 过滤空值
        $filtered = [];
        foreach ($params as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            $filtered[$key] = $value;
        }

        // 按 key 排序
        ksort($filtered);

        // 拼接参数
        $pairs = [];
        foreach ($filtered as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }

        $signString = implode('&', $pairs) . '&key=' . $this->secretKey;

        Log::info('Payout Sign String: ' . $signString);

        return strtolower(md5($signString));
    }
}