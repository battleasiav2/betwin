<?php
/**
 * One-shot: set RapidVerse callback_url with secret_key query (Hostinger strips headers).
 * Visit once, then delete this file.
 */
require __DIR__ . '/core/vendor/autoload.php';
$app = require __DIR__ . '/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

header('Content-Type: application/json');

if (!Schema::hasTable('api_game_settings')) {
    echo json_encode(['code' => 1, 'msg' => 'no settings table']);
    exit;
}

if (!Schema::hasColumn('api_game_settings', 'api_prefix')) {
    Schema::table('api_game_settings', function ($t) {
        $t->string('api_prefix', 64)->nullable()->after('agent_user');
    });
}

$row = DB::table('api_game_settings')->orderBy('id')->first();
if (!$row || empty($row->secret_key)) {
    echo json_encode(['code' => 1, 'msg' => 'secret missing']);
    exit;
}

$base = 'https://bet369win.com/callback.php';
$url = $base . '?secret_key=' . rawurlencode($row->secret_key);

DB::table('api_game_settings')->where('id', $row->id)->update([
    'callback_url' => $url,
    'api_prefix'   => $row->api_prefix ?: 'nix6260006107',
    'updated_at'   => now(),
]);

echo json_encode([
    'code' => 0,
    'msg' => 'ok',
    'callback_url' => $url,
    'note' => 'Paste this EXACT URL into RapidVerse panel → YOUR CALLBACK URL, then delete fix-callback-url.php',
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
