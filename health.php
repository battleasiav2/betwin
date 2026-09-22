<?php
/**
 * Temporary diagnostics — delete after site works.
 * https://bet369win.com/health.php
 */
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

echo "PHP " . PHP_VERSION . "\n";
echo "cwd=" . __DIR__ . "\n";

$checks = [
    'index.php' => is_file(__DIR__ . '/index.php'),
    'core/.env' => is_file(__DIR__ . '/core/.env'),
    'core/vendor/autoload.php' => is_file(__DIR__ . '/core/vendor/autoload.php'),
    'core/bootstrap/app.php' => is_file(__DIR__ . '/core/bootstrap/app.php'),
    'setup-env.php' => is_file(__DIR__ . '/setup-env.php'),
    'storage writable' => is_dir(__DIR__ . '/core/storage') && is_writable(__DIR__ . '/core/storage'),
    'bootstrap/cache writable' => is_dir(__DIR__ . '/core/bootstrap/cache') && is_writable(__DIR__ . '/core/bootstrap/cache'),
];

foreach ($checks as $k => $ok) {
    echo ($ok ? '[OK] ' : '[FAIL] ') . $k . "\n";
}

$envPath = __DIR__ . '/core/.env';
if (!is_readable($envPath)) {
    echo "\nNO .env readable\n";
    exit;
}

$env = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if (
        (str_starts_with($v, '"') && str_ends_with($v, '"')) ||
        (str_starts_with($v, "'") && str_ends_with($v, "'"))
    ) {
        $v = substr($v, 1, -1);
    }
    $env[$k] = $v;
}

echo "\nAPP_URL=" . ($env['APP_URL'] ?? '(missing)') . "\n";
echo "APP_KEY=" . (empty($env['APP_KEY']) ? '(missing)' : 'set(' . strlen($env['APP_KEY']) . ')') . "\n";
echo "DB_HOST=" . ($env['DB_HOST'] ?? '') . "\n";
echo "DB_DATABASE=" . ($env['DB_DATABASE'] ?? '') . "\n";
echo "DB_USERNAME=" . ($env['DB_USERNAME'] ?? '') . "\n";
echo "DB_PASSWORD=" . (isset($env['DB_PASSWORD']) ? 'set(len=' . strlen($env['DB_PASSWORD']) . ')' : '(missing)') . "\n";

mysqli_report(MYSQLI_REPORT_OFF);
$host = $env['DB_HOST'] ?? 'localhost';
$user = $env['DB_USERNAME'] ?? '';
$pass = $env['DB_PASSWORD'] ?? '';
$name = $env['DB_DATABASE'] ?? '';

$m = @new mysqli($host, $user, $pass, $name);
if ($m->connect_error) {
    echo "DB_CONNECT=FAIL " . $m->connect_error . "\n";
} else {
    echo "DB_CONNECT=OK\n";
    $r = $m->query('SELECT COUNT(*) c FROM users');
    if ($r) {
        echo "users_count=" . $r->fetch_assoc()['c'] . "\n";
    } else {
        echo "users_query=FAIL " . $m->error . "\n";
    }
    $m->close();
}

// Laravel boot test
echo "\n--- Laravel boot ---\n";
try {
    require __DIR__ . '/core/vendor/autoload.php';
    $app = require __DIR__ . '/core/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "LARAVEL_BOOT=OK\n";
    echo "app_name=" . config('app.name') . "\n";
} catch (Throwable $e) {
    echo "LARAVEL_BOOT=FAIL\n";
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n";
}
