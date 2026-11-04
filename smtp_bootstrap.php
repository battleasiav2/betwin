<?php
/**
 * One-time SMTP bootstrap for live Hostinger.
 * Call via POST then this file deletes itself.
 */
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'POST only';
    exit;
}

$key = (string) ($_POST['key'] ?? '');
if (!hash_equals('Bet369SmtpFix2026', $key)) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$host = trim((string) ($_POST['host'] ?? 'smtp.hostinger.com'));
$port = trim((string) ($_POST['port'] ?? '465'));
$enc = trim((string) ($_POST['enc'] ?? 'ssl'));
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$from = trim((string) ($_POST['from'] ?? $username));
$fromName = trim((string) ($_POST['from_name'] ?? 'BET369WIN'));

if ($username === '' || $password === '') {
    http_response_code(422);
    echo 'username/password required';
    exit;
}

$envPath = __DIR__ . '/core/.env';
if (!is_file($envPath)) {
    http_response_code(500);
    echo 'env missing';
    exit;
}

$env = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $env[trim($k)] = trim($v, " \t\"'");
}

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName),
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $mailConfig = json_encode([
        'name' => 'smtp',
        'host' => $host,
        'port' => $port,
        'enc' => $enc,
        'username' => $username,
        'password' => $password,
    ], JSON_UNESCAPED_SLASHES);

    $st = $pdo->prepare('UPDATE general_settings SET en = 1, email_from = ?, email_from_name = ?, mail_config = ? WHERE id = 1');
    $st->execute([$from, $fromName, $mailConfig]);

    foreach (['PASS_RESET_CODE', 'PASS_RESET_DONE', 'DEFAULT'] as $act) {
        $pdo->prepare('UPDATE notification_templates SET email_status = 1 WHERE act = ?')->execute([$act]);
    }

    echo "OK smtp set for {$username}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'FAIL ' . $e->getMessage();
    exit;
}

@unlink(__FILE__);
echo "bootstrap removed\n";
