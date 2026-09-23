<?php
/**
 * One-shot view/config cache clear. Delete this file after use.
 */
require __DIR__ . '/core/vendor/autoload.php';
$app = require __DIR__ . '/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$out = [];
foreach (['view:clear', 'cache:clear', 'config:clear', 'route:clear'] as $cmd) {
    try {
        $kernel->call($cmd);
        $out[$cmd] = 'ok';
    } catch (Throwable $e) {
        $out[$cmd] = $e->getMessage();
    }
}

// Also wipe compiled blades
$compiled = __DIR__ . '/core/storage/framework/views';
$n = 0;
if (is_dir($compiled)) {
    foreach (glob($compiled . '/*.php') ?: [] as $f) {
        if (@unlink($f)) {
            $n++;
        }
    }
}
$out['compiled_deleted'] = $n;

header('Content-Type: application/json');
echo json_encode(['code' => 0, 'msg' => 'cleared', 'detail' => $out], JSON_PRETTY_PRINT);
