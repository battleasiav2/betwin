<?php

namespace App\Http\Controllers\GameProviders\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiGameController extends Controller
{
    private string $API_URL = 'https://www.rapidverse.site/api/demo';
    private string $API_TOKEN = '138647523b33be085931bd1514a2ec78ec53393d95715c9504b9a5c090accfb4';
    private string $SECRET_KEY = '5d944b3769f42a36284a5935c144e287ac29fee8cd8b16714d7d33fb8e5c66c6';

    public function launch(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'game_code' => 'required|string',
            'provider'  => 'nullable|string'
        ]);

        $user->refresh();

        $provider = $request->provider ?? 'JILI';
        $vendorCode = $this->getVendorCode($provider);
        
        $payload = [
            'userId'      => (string) $user->id,
            'gameCode'    => $request->game_code,
            'userBalance' => round((float) $user->balance, 2),
            'vendorCode'  => $vendorCode,
            'language'    => '0',
            'phonetype'   => '1',
            'returnUrl'   => route('user.home'),
        ];

        $ch = curl_init($this->API_URL);
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Token: ' . $this->API_TOKEN,
            'X-Secret-Key: ' . $this->SECRET_KEY,
        ];
        
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return back()->withErrors('Connection error: ' . $error);
        }

        $result = json_decode($response, true);

        if ($httpCode !== 200 || !isset($result['code']) || $result['code'] != 0) {
            return back()->withErrors($result['msg'] ?? 'Game server error');
        }

        return view('templates.sunfyre.gamelunch', [
            'game_url' => $result['data']['url'],
            'game_provider' => strtoupper($provider),
            'pageTitle' => strtoupper($provider) . ' Game'
        ]);
    }
    
    private function getVendorCode(string $provider): string
    {
        return match(strtolower($provider)) {
            'pg' => 'PG',
            'jdb' => 'JDB',
            'cq9' => 'CQ9',
            'evo' => 'EVOLUTION',
            'jili', 'default' => 'JILI'
        };
    }
}
