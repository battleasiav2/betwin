<?php
/**
 * RapidVerse wallet callback — settle bet/win, write game_logs + transactions history.
 * URL: https://bet369win.com/callback.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$host = 'localhost';
$user = '';
$pass = '';
$name = '';
$apiPrefix = 'nix6260006107';
$secretEnv = '';

$envPath = __DIR__ . '/core/.env';
if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || ($line[0] ?? '') === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        match ($k) {
            'DB_HOST' => $host = $v,
            'DB_USERNAME' => $user = $v,
            'DB_PASSWORD' => $pass = $v,
            'DB_DATABASE' => $name = $v,
            'RAPIDVERSE_API_PREFIX' => $apiPrefix = $v ?: $apiPrefix,
            'RAPIDVERSE_SECRET_KEY' => $secretEnv = $v,
            default => null,
        };
    }
}

function rv_admin_alert(mysqli $conn, string $title, string $dedupeKey = ''): void
{
    $title = mb_substr($title, 0, 250);
    if ($dedupeKey !== '') {
        $dir = __DIR__ . '/core/storage/framework/cache';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file = $dir . '/cb_alert_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $dedupeKey) . '.lock';
        if (is_file($file) && (time() - filemtime($file)) < 300) {
            return;
        }
        @file_put_contents($file, (string) time());
    }
    try {
        $stmt = $conn->prepare('INSERT INTO admin_notifications (user_id, title, click_url, is_read, created_at, updated_at) VALUES (0, ?, ?, 0, NOW(), NOW())');
        if (!$stmt) {
            return;
        }
        $url = '/xpanel/users';
        $stmt->bind_param('ss', $title, $url);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        // never break wallet settle
    }
}

function rv_trx(mysqli $conn, int $userId, float $amount, string $type, float $postBal, string $details, string $remark, string $trx): void
{
    $charge = 0.0;
    $stmt = $conn->prepare('INSERT INTO transactions (user_id, amount, charge, post_balance, trx_type, trx, details, remark, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())');
    if (!$stmt) {
        throw new Exception('trx prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('idddssss', $userId, $amount, $charge, $postBal, $type, $trx, $details, $remark);
    if (!$stmt->execute()) {
        throw new Exception('trx insert failed: ' . $stmt->error);
    }
    $stmt->close();
}

$raw = file_get_contents('php://input');
$data = json_decode((string) $raw, true);
if (!is_array($data)) {
    // Also accept form-encoded
    if (!empty($_POST)) {
        $data = $_POST;
    } else {
        echo json_encode(['code' => 1, 'msg' => 'invalid body']);
        exit;
    }
}

$conn = @new mysqli($host, $user, $pass, $name);
if ($conn->connect_error) {
    echo json_encode(['code' => 1, 'msg' => 'db']);
    exit;
}
$conn->set_charset('utf8mb4');

// Optional: load prefix from api_game_settings
$prefRow = @$conn->query('SELECT api_prefix, secret_key FROM api_game_settings ORDER BY id ASC LIMIT 1');
if ($prefRow && ($pr = $prefRow->fetch_assoc())) {
    if (!empty($pr['api_prefix'])) {
        $apiPrefix = (string) $pr['api_prefix'];
    }
    if (!empty($pr['secret_key'])) {
        $secretEnv = (string) $pr['secret_key'];
    }
}

// Optional secret check (do not hard-fail if RapidVerse sends no header)
$hdrSecret = $_SERVER['HTTP_X_SECRET_KEY'] ?? $_SERVER['HTTP_X_API_SECRET'] ?? '';
if ($secretEnv !== '' && $hdrSecret !== '' && !hash_equals($secretEnv, (string) $hdrSecret)) {
    rv_admin_alert($conn, 'CALLBACK FAIL: bad secret', 'bad_secret');
    echo json_encode(['code' => 1, 'msg' => 'auth']);
    exit;
}

$rawUser = (string) ($data['user_id'] ?? $data['userId'] ?? $data['member_account'] ?? $data['memberAccount'] ?? '');
$rawUser = trim($rawUser);
if ($apiPrefix !== '' && str_starts_with($rawUser, $apiPrefix)) {
    $rawUser = substr($rawUser, strlen($apiPrefix));
}
$userId = (int) preg_replace('/\D+/', '', $rawUser);

$gameCode = trim((string) ($data['game_code'] ?? $data['gameCode'] ?? $data['game_uid'] ?? $data['gameUid'] ?? 'API Game'));
$gameName = substr($gameCode !== '' ? $gameCode : 'API Game', 0, 40);
$provider = strtoupper(substr(trim((string) ($data['vendorCode'] ?? $data['vendor_code'] ?? $data['game_provider'] ?? $data['provider'] ?? '')), 0, 20));
if ($provider !== '') {
    $gameName = substr($provider . ':' . $gameName, 0, 40);
}

$bet = (float) ($data['bet_amount'] ?? $data['betAmount'] ?? $data['bet'] ?? 0);
$win = (float) ($data['win_amount'] ?? $data['winAmount'] ?? $data['win'] ?? 0);
$serial = trim((string) ($data['serial_number'] ?? $data['serialNumber'] ?? $data['game_round'] ?? $data['gameRound'] ?? $data['transactionId'] ?? ''));
if ($serial === '') {
    $serial = 'RV' . md5($raw . microtime(true));
}
$serial = substr($serial, 0, 100);
$winStat = ($win > $bet) ? 1 : 0;
$gameId = 0;

if ($userId <= 0) {
    rv_admin_alert($conn, 'CALLBACK FAIL: bad user id raw=' . mb_substr($rawUser, 0, 40), 'bad_uid');
    echo json_encode(['code' => 1, 'msg' => 'user']);
    exit;
}

// Deduplicate
$chk = $conn->prepare('SELECT id FROM game_logs WHERE serial_number=? LIMIT 1');
$chk->bind_param('s', $serial);
$chk->execute();
if ($chk->get_result()->num_rows > 0) {
    $bq = $conn->prepare('SELECT balance FROM users WHERE id=?');
    $bq->bind_param('i', $userId);
    $bq->execute();
    $br = $bq->get_result()->fetch_assoc();
    echo json_encode(['code' => 0, 'balance' => round((float) ($br['balance'] ?? 0), 2)]);
    exit;
}

$q = $conn->prepare('SELECT balance, turnover_requirement FROM users WHERE id=? LIMIT 1');
$q->bind_param('i', $userId);
$q->execute();
$userData = $q->get_result()->fetch_assoc();
if (!$userData) {
    rv_admin_alert($conn, 'CALLBACK FAIL: user not found #' . $userId . ' raw=' . mb_substr((string) ($data['user_id'] ?? $data['userId'] ?? ''), 0, 40), 'nouser_' . $userId);
    echo json_encode(['code' => 1, 'msg' => 'user_not_found']);
    exit;
}

$bal = (float) $userData['balance'];
$turnoverReq = (float) ($userData['turnover_requirement'] ?? 0);
$newBal = round($bal - $bet + $win, 8);
if ($newBal < 0) {
    $newBal = 0.0;
}

$newTurnover = $turnoverReq;
if ($turnoverReq > 0 && $bet > 0) {
    $newTurnover = max(0, $turnoverReq - $bet);
}

$trxBase = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $serial), 0, 10));
if ($trxBase === '') {
    $trxBase = 'RV' . strtoupper(bin2hex(random_bytes(4)));
}

$conn->begin_transaction();
try {
    $u = $conn->prepare('UPDATE users SET balance=?, turnover_requirement=? WHERE id=?');
    $u->bind_param('ddi', $newBal, $newTurnover, $userId);
    if (!$u->execute()) {
        throw new Exception('user update failed');
    }

    $l = $conn->prepare('INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at, updated_at) VALUES (?,?,?,?,?,?,?,0,1,NOW(),NOW())');
    $l->bind_param('iisddsi', $userId, $gameId, $gameName, $bet, $win, $serial, $winStat);
    if (!$l->execute()) {
        throw new Exception('game_log insert failed: ' . $l->error);
    }

    // Fund history (transactions page)
    $afterBet = round($bal - $bet, 8);
    if ($bet > 0) {
        rv_trx(
            $conn,
            $userId,
            $bet,
            '-',
            max(0, $afterBet),
            'Game bet ' . $gameName,
            'game_bet',
            $trxBase . 'B'
        );
    }
    if ($win > 0) {
        rv_trx(
            $conn,
            $userId,
            $win,
            '+',
            $newBal,
            ($winStat ? 'Game win ' : 'Game return ') . $gameName,
            $winStat ? 'game_win' : 'game_return',
            $trxBase . 'W'
        );
    }

    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
    rv_admin_alert($conn, 'CALLBACK FAIL: ' . $e->getMessage() . ' user#' . $userId, 'txfail');
    echo json_encode(['code' => 1, 'msg' => 'tx']);
    exit;
}

echo json_encode(['code' => 0, 'balance' => round($newBal, 2)]);
