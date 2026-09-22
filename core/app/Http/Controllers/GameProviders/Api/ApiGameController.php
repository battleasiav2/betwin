<?php

namespace App\Http\Controllers\GameProviders\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiGameController extends Controller
{
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
        $settings = $this->getApiSettings();

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

        $ch = curl_init($settings['api_url']);

        $headers = [
            'Content-Type: application/json',
            'X-API-Token: ' . $settings['api_token'],
            'X-Secret-Key: ' . $settings['secret_key'],
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

    private function getApiSettings(): array
    {
        $defaults = [
            'api_url'    => env('RAPIDVERSE_API_URL', 'https://www.rapidverse.site/api/demo'),
            'api_token'  => env('RAPIDVERSE_API_TOKEN', ''),
            'secret_key' => env('RAPIDVERSE_SECRET_KEY', ''),
        ];

        if (!Schema::hasTable('api_game_settings')) {
            return $defaults;
        }

        $row = DB::table('api_game_settings')->orderBy('id')->first();
        if (!$row) {
            return $defaults;
        }

        return [
            'api_url'    => $row->api_url ?: $defaults['api_url'],
            'api_token'  => $row->api_token ?: $defaults['api_token'],
            'secret_key' => $row->secret_key ?: $defaults['secret_key'],
        ];
    }

    private function getVendorCode(string $provider): string
    {
        return match (strtolower($provider)) {
            'pg' => 'PG',
            'jdb' => 'JDB',
            'cq9', 'g9' => 'CQ9',
            'evo', 'casino' => 'EVOLUTION',
            'card365' => 'Card365',
            'idg' => 'IDG',
            'km' => 'KM',
            'v8' => 'V8',
            'mg' => 'MG',
            'jili', 'hot', 'crash', 'sports', 'default' => 'JILI',
            default => strtoupper($provider),
        };
    }
}
