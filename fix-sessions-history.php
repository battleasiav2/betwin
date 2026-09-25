<?php
/**
 * One-shot: create sessions table if missing (fixes Laravel 500 when SESSION_DRIVER=database)
 * + show game history counts.
 * https://bet369win.com/fix-sessions-history.php?token=BET369WIN-FIX-SESS-2026
 */
header('Content-Type: text/plain; charset=utf-8');
if (!hash_equals('BET369WIN-FIX-SESS-2026', (string)($_GET['token'] ?? ''))) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$envPath = __DIR__ . '/core/.env';
$db = ['host'=>'localhost','port'=>'3306','user'=>'','pass'=>'','name'=>''];
foreach (file($envPath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line==='' || str_starts_with($line,'#') || !str_contains($line,'=')) continue;
    [$k,$v] = explode('=', $line, 2);
    $k=trim($k); $v=trim($v);
    if ((str_starts_with($v,'"')&&str_ends_with($v,'"'))||(str_starts_with($v,"'")&&str_ends_with($v,"'"))) $v=substr($v,1,-1);
    match($k){
        'DB_HOST'=>$db['host']=$v,
        'DB_PORT'=>$db['port']=$v,
        'DB_USERNAME'=>$db['user']=$v,
        'DB_PASSWORD'=>$db['pass']=$v,
        'DB_DATABASE'=>$db['name']=$v,
        default=>null
    };
}

$conn = new mysqli($db['host'],$db['user'],$db['pass'],$db['name'],(int)$db['port']);
if ($conn->connect_error) { echo "db_error={$conn->connect_error}\n"; exit; }
$conn->set_charset('utf8mb4');

$has = $conn->query("SHOW TABLES LIKE 'sessions'");
if (!$has || $has->num_rows===0) {
    $ok = $conn->query("CREATE TABLE sessions (
        id VARCHAR(255) NOT NULL PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        payload LONGTEXT NOT NULL,
        last_activity INT NOT NULL,
        INDEX sessions_user_id_index (user_id),
        INDEX sessions_last_activity_index (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo $ok ? "sessions_table=created\n" : ("sessions_fail=".$conn->error."\n");
} else {
    echo "sessions_table=exists\n";
}

foreach (['users','game_logs','transactions','deposits','withdrawals'] as $t) {
    $r = @$conn->query("SELECT COUNT(*) AS c FROM `$t`");
    $c = ($r && ($row=$r->fetch_assoc())) ? $row['c'] : 'ERR';
    echo "{$t}_count={$c}\n";
}

$r = @$conn->query("SELECT id,user_id,game_name,invest,win_amo,win_status,created_at FROM game_logs ORDER BY id DESC LIMIT 5");
if ($r) {
    echo "--- latest game_logs ---\n";
    while ($row = $r->fetch_assoc()) {
        echo "id={$row['id']} user={$row['user_id']} game={$row['game_name']} bet={$row['invest']} win={$row['win_amo']} status={$row['win_status']} at={$row['created_at']}\n";
    }
}

// clear config cache
@unlink(__DIR__.'/core/bootstrap/cache/config.php');
echo "config_cache_cleared=1\n";
$conn->close();
@unlink(__FILE__);
echo "done\n";
