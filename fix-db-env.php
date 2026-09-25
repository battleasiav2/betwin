<?php
/**
 * One-time: rewrite core/.env for Hostinger + clear caches.
 * Open: https://bet369win.com/fix-db-env.php?token=BET369WIN-FIX-DB-2026
 * Deletes itself after success.
 */
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

$token = (string) ($_GET['token'] ?? '');
if (!hash_equals('BET369WIN-FIX-DB-2026', $token)) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$root = __DIR__;
$envPath = $root . '/core/.env';

$dbPass = (string) ($_GET['db_pass'] ?? $_POST['db_pass'] ?? 'F1z>0Rw#e0');
$quotedPass = '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $dbPass) . '"';

$env = <<<ENV
APP_NAME=BET369WIN
APP_ENV=production
APP_KEY=base64:CU19sat/F4uWQfyG9DFr49GmF818g/vLpb3ptj9s2sc=
APP_DEBUG=false
APP_URL=https://bet369win.com
LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u811189100_betwin
DB_USERNAME=u811189100_betwin
DB_PASSWORD={$quotedPass}
BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=43200
SESSION_EXPIRE_ON_CLOSE=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="support@bet369win.com"
MAIL_FROM_NAME="\${APP_NAME}"
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
MIX_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"

RAPIDVERSE_API_URL=https://www.rapidverse.site/api/versev1
RAPIDVERSE_API_TOKEN=3ad0d33f2bd3e61667fce3600dbbe903a298d1d975585970efb281d39bc16fb5
RAPIDVERSE_SECRET_KEY=7536eaeabcb058a34ba41b0c61890575445327aa1d2ad919a9735f0c5aeb7fac
RAPIDVERSE_CALLBACK_URL=https://bet369win.com/callback.php
RAPIDVERSE_AGENT_USER=nix626000
RAPIDVERSE_CURRENCY=BDT
APK_DOWNLOAD_URL=
RAPIDVERSE_API_PREFIX=nix6260006107
BIG_WITHDRAW_THRESHOLD=50000
ALERT_API_DOWN_DEDUP_SECONDS=600
ALERT_CALLBACK_DEDUP_SECONDS=300
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHAT_ID=
ENV;

mysqli_report(MYSQLI_REPORT_OFF);
$m = @new mysqli('localhost', 'u811189100_betwin', $dbPass, 'u811189100_betwin');
if ($m->connect_error) {
    echo "DB_TEST=FAIL " . $m->connect_error . "\n";
    echo "HINT: Hostinger → Databases → MySQL → reset password for u811189100_betwin\n";
    echo "Then reopen: /fix-db-env.php?token=BET369WIN-FIX-DB-2026&db_pass=YOUR_NEW_PASSWORD\n";
    exit;
}
echo "DB_TEST=OK\n";
$m->close();

if (@file_put_contents($envPath, $env) === false) {
    http_response_code(500);
    echo "WRITE_FAIL core/.env\n";
    exit;
}
@chmod($envPath, 0600);
echo "ENV_WRITTEN\n";

foreach ([
    $root . '/core/bootstrap/cache/config.php',
    $root . '/core/bootstrap/cache/packages.php',
    $root . '/core/bootstrap/cache/services.php',
] as $c) {
    if (is_file($c)) {
        @unlink($c);
    }
}

$dirs = [
    $root . '/core/storage/framework/cache/data',
    $root . '/core/storage/framework/views',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $f) {
        if ($f->isFile()) {
            @unlink($f->getPathname());
        }
    }
}
echo "CACHE_CLEARED\n";

try {
    require $root . '/core/vendor/autoload.php';
    $app = require $root . '/core/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo "LARAVEL_BOOT=OK\n";
    echo "site=" . (function_exists('gs') ? (string) gs('site_name') : config('app.name')) . "\n";
} catch (Throwable $e) {
    echo "LARAVEL_BOOT=FAIL " . $e->getMessage() . "\n";
}

@unlink(__FILE__);
echo "SELF_DELETED\n";
echo "DONE open https://bet369win.com/\n";
