<?php
/**
 * RapidVerse wallet callback.
 * Launch API uses X-Secret-Key. Wallet posts from RapidVerse do not.
 * A wrong secret is rejected. A missing secret is still settled so games can play.
 * Credentials come from core/.env — nothing is hardcoded here.
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

function cb_fail(int $code = 1, array $extra = []): void
{
    echo json_encode(['code' => $code] + $extra);
    exit;
}

function cb_env(string $path): array
{
    $out = [];
    if (!is_readable($path)) {
        return $out;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        $out[$k] = $v;
    }
    return $out;
}

function cb_header_secret(): string
{
    $given = (string) ($_SERVER['HTTP_X_SECRET_KEY'] ?? '');
    if ($given !== '') {
        return $given;
    }
    if (function_exists('getallheaders')) {
        foreach (getallheaders() as $name => $value) {
            if (strcasecmp((string) $name, 'X-Secret-Key') === 0) {
                return (string) $value;
            }
        }
    }
    return '';
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    cb_fail();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    cb_fail();
}

$env = cb_env(__DIR__ . '/core/.env');
$dbHost = $env['DB_HOST'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';
$dbName = $env['DB_DATABASE'] ?? '';
$dbPort = (int) ($env['DB_PORT'] ?? 3306);
$apiPrefix = (string) ($env['RAPIDVERSE_API_PREFIX'] ?? '');
$secret = (string) ($env['RAPIDVERSE_SECRET_KEY'] ?? '');

if ($dbHost === '' || $dbUser === '' || $dbName === '') {
    cb_fail();
}

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort > 0 ? $dbPort : 3306);
if ($conn->connect_error) {
    cb_fail();
}
$conn->set_charset('utf8mb4');

if ($secret === '') {
    $res = $conn->query('SELECT secret_key FROM api_game_settings ORDER BY id ASC LIMIT 1');
    if ($res && ($row = $res->fetch_assoc())) {
        $secret = trim((string) ($row['secret_key'] ?? ''));
    }
}

$given = cb_header_secret();
if ($given === '') {
    foreach (['secret_key', 'secret', 'sign', 'api_secret'] as $secretField) {
        if (!empty($data[$secretField]) && is_string($data[$secretField])) {
            $given = trim($data[$secretField]);
            break;
        }
    }
}
if ($given !== '' && ($secret === '' || !hash_equals($secret, $given))) {
    $conn->close();
    cb_fail();
}

$userRaw = trim((string) ($data['user_id'] ?? $data['userId'] ?? $data['member_account'] ?? ''));
if ($apiPrefix !== '' && str_starts_with($userRaw, $apiPrefix)) {
    $userRaw = substr($userRaw, strlen($apiPrefix));
}
$userId = (int) $userRaw;
$gameName = trim((string) ($data['game_code'] ?? $data['gameCode'] ?? 'API Game'));
if ($gameName === '') {
    $gameName = 'API Game';
}
$gameName = substr($gameName, 0, 120);
$bet = (float) ($data['bet_amount'] ?? $data['betAmount'] ?? $data['bet'] ?? 0);
$win = (float) ($data['win_amount'] ?? $data['winAmount'] ?? $data['win'] ?? 0);
$serial = trim((string) ($data['serial_number'] ?? $data['serialNumber'] ?? $data['transaction_id'] ?? $data['transactionId'] ?? ''));
$serial = substr($serial, 0, 190);
$winStat = $win > $bet ? 1 : 0;
$gameId = 0;

$action = strtolower((string) ($data['action'] ?? $data['type'] ?? ''));
$balanceOnly = in_array($action, ['balance', 'getbalance', 'get_balance'], true)
    || ($serial === '' && $bet == 0.0 && $win == 0.0);

if ($userId <= 0 || $bet < 0 || $win < 0 || !is_finite($bet) || !is_finite($win)) {
    $conn->close();
    cb_fail();
}

if ($balanceOnly) {
    $q = $conn->prepare('SELECT balance FROM users WHERE id=? LIMIT 1');
    $q->bind_param('i', $userId);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();
    $conn->close();
    if (!$row) {
        cb_fail();
    }
    $bal = round((float) $row['balance'], 2);
    echo json_encode(['code' => 0, 'balance' => $bal, 'userBalance' => $bal]);
    exit;
}

if ($serial === '') {
    $conn->close();
    cb_fail();
}

$lockName = 'glog_' . substr(hash('sha256', $serial), 0, 32);
$lock = $conn->prepare('SELECT GET_LOCK(?, 8)');
$lock->bind_param('s', $lockName);
$lock->execute();
$got = $lock->get_result()->fetch_row();
if ((int) ($got[0] ?? 0) !== 1) {
    $conn->close();
    cb_fail();
}

try {
    $conn->begin_transaction();

    $userStmt = $conn->prepare('SELECT balance, turnover_requirement FROM users WHERE id=? FOR UPDATE');
    $userStmt->bind_param('i', $userId);
    $userStmt->execute();
    $userData = $userStmt->get_result()->fetch_assoc();
    if (!$userData) {
        $conn->rollback();
        cb_fail();
    }

    $dup = $conn->prepare('SELECT id FROM game_logs WHERE serial_number=? LIMIT 1');
    $dup->bind_param('s', $serial);
    $dup->execute();
    if ($dup->get_result()->num_rows) {
        $conn->commit();
        $bal = round((float) $userData['balance'], 2);
        echo json_encode(['code' => 0, 'balance' => $bal]);
        exit;
    }

    $bal = (float) $userData['balance'];
    $turnoverReq = (float) $userData['turnover_requirement'];
    if ($bet > $bal + 0.0001) {
        $conn->rollback();
        echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => round($bal, 2)]);
        exit;
    }

    $newBal = round($bal - $bet + $win, 2);
    if ($newBal < 0) {
        $conn->rollback();
        echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => round($bal, 2)]);
        exit;
    }

    $newTurnover = ($turnoverReq > 0 && $bet > 0) ? max(0, $turnoverReq - $bet) : $turnoverReq;

    $upd = $conn->prepare('UPDATE users SET balance=?, turnover_requirement=? WHERE id=?');
    $upd->bind_param('ddi', $newBal, $newTurnover, $userId);
    $upd->execute();

    $ins = $conn->prepare('INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at) VALUES (?,?,?,?,?,?,?,0,1,NOW())');
    $ins->bind_param('iisddsi', $userId, $gameId, $gameName, $bet, $win, $serial, $winStat);
    if (!$ins->execute()) {
        $conn->rollback();
        if ((int) $conn->errno === 1062) {
            echo json_encode(['code' => 0, 'balance' => round($bal, 2)]);
            exit;
        }
        cb_fail();
    }

    $conn->commit();
    echo json_encode(['code' => 0, 'balance' => $newBal]);
} catch (Throwable $e) {
    $conn->rollback();
    cb_fail();
} finally {
    $rel = $conn->prepare('SELECT RELEASE_LOCK(?)');
    $rel->bind_param('s', $lockName);
    $rel->execute();
    $conn->close();
}
