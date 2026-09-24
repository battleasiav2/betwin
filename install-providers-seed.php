<?php
/**
 * One-shot: deploy RapidVerse provider catalog + sync api_game_controls.
 * Self-deletes after run.
 */
header('Content-Type: application/json');

$root = __DIR__;
$files = [
    // filled by generator below at deploy time — this template is rebuilt in shell
];

$out = ['files' => [], 'seeded' => 0];

require $root . '/core/vendor/autoload.php';
$app = require $root . '/core/bootstrap/app.php';
$k = $app->make(Illuminate\Contracts\Console\Kernel::class);
$k->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$providers = [];
$configPath = $root . '/core/config/rapidverse_providers.php';
if (is_file($configPath)) {
    $providers = require $configPath;
}

if (Schema::hasTable('api_game_controls') && is_array($providers)) {
    $now = date('Y-m-d H:i:s');
    foreach ($providers as $p) {
        $slug = substr((string) ($p['slug'] ?? ''), 0, 40);
        $name = substr((string) ($p['name'] ?? $slug), 0, 40);
        if ($slug === '') {
            continue;
        }
        $exists = DB::table('api_game_controls')->where('slug', $slug)->exists();
        if ($exists) {
            continue;
        }
        DB::table('api_game_controls')->insert([
            'name' => $name,
            'slug' => $slug,
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $out['seeded']++;
    }
}

$k->call('view:clear');
$k->call('config:clear');
$n = 0;
foreach (glob($root . '/core/storage/framework/views/*.php') ?: [] as $f) {
    if (@unlink($f)) {
        $n++;
    }
}
$out['views_cleared'] = $n;
$out['code'] = 0;
$out['providers'] = is_array($providers) ? count($providers) : 0;

@unlink(__FILE__);
echo json_encode($out);
