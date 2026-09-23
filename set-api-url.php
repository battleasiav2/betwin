<?php
header("Content-Type: text/plain; charset=utf-8");
if (!hash_equals("BET369WIN-REBRAND-2026", (string)($_GET["token"] ?? ""))) { http_response_code(403); echo "forbidden"; exit; }
$envPath = __DIR__ . "/core/.env";
$db = ["host"=>"localhost","port"=>"3306","user"=>"","pass"=>"","name"=>""];
foreach (file($envPath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
  $line = trim($line);
  if ($line==="" || str_starts_with($line,"#") || !str_contains($line,"=")) continue;
  [$k,$v] = explode("=", $line, 2); $k=trim($k); $v=trim($v);
  if ((str_starts_with($v,'"')&&str_ends_with($v,'"'))||(str_starts_with($v,"'")&&str_ends_with($v,"'"))) $v=substr($v,1,-1);
  match($k){ "DB_HOST"=>$db["host"]=$v, "DB_PORT"=>$db["port"]=$v, "DB_USERNAME"=>$db["user"]=$v, "DB_PASSWORD"=>$db["pass"]=$v, "DB_DATABASE"=>$db["name"]=$v, default=>null};
}
$conn = new mysqli($db["host"],$db["user"],$db["pass"],$db["name"],(int)$db["port"]);
$conn->set_charset("utf8mb4");
$url = "https://www.rapidverse.site/api/versev1";
$conn->query("UPDATE api_game_settings SET api_url='".$conn->real_escape_string($url)."', updated_at=NOW()");
$r = $conn->query("SELECT api_url FROM api_game_settings ORDER BY id ASC LIMIT 1")->fetch_assoc();
echo "api_url=".$r["api_url"]."\n";
// patch .env if present
if (is_writable($envPath)) {
  $c = file_get_contents($envPath);
  $c2 = preg_replace('/^RAPIDVERSE_API_URL=.*$/m', 'RAPIDVERSE_API_URL='.$url, $c);
  if ($c2 && $c2 !== $c) { file_put_contents($envPath, $c2); echo "env_updated=1\n"; }
  elseif (!preg_match('/^RAPIDVERSE_API_URL=/m', $c)) { file_put_contents($envPath, rtrim($c)."\nRAPIDVERSE_API_URL=$url\n"); echo "env_appended=1\n"; }
}
@unlink(__FILE__);
echo "done\n";
