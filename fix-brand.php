<?php
header("Content-Type: text/plain; charset=utf-8");
if (!hash_equals("BET369WIN-REBRAND-2026", (string)($_GET["token"] ?? ""))) { http_response_code(403); echo "forbidden"; exit; }
$envPath = __DIR__ . "/core/.env";
$db = ["host"=>"localhost","port"=>"3306","user"=>"","pass"=>"","name"=>""];
foreach (file($envPath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
  $line = trim($line);
  if ($line==="" || str_starts_with($line,"#") || !str_contains($line,"=")) continue;
  [$k,$v] = explode("=", $line, 2);
  $k=trim($k); $v=trim($v);
  if ((str_starts_with($v,'"')&&str_ends_with($v,'"'))||(str_starts_with($v,"'")&&str_ends_with($v,"'"))) $v=substr($v,1,-1);
  match($k){ "DB_HOST"=>$db["host"]=$v, "DB_PORT"=>$db["port"]=$v, "DB_USERNAME"=>$db["user"]=$v, "DB_PASSWORD"=>$db["pass"]=$v, "DB_DATABASE"=>$db["name"]=$v, default=>null};
}
$conn = new mysqli($db["host"],$db["user"],$db["pass"],$db["name"],(int)$db["port"]);
$conn->set_charset("utf8mb4");
$conn->query("UPDATE general_settings SET site_name='BET369WIN', email_from_name='BET369WIN', sms_from='BET369WIN', email_from='support@bet369win.com'");
$r = $conn->query("SELECT site_name FROM general_settings LIMIT 1")->fetch_assoc();
echo "site_name=".$r["site_name"]."\n";
$hash = password_hash("bet369win@7777", PASSWORD_BCRYPT, ["cost"=>12]);
$st = $conn->prepare("UPDATE admins SET username=?, password=?, name=? WHERE id=1");
$u="bet369win"; $n="BET369WIN Super Admin";
$st->bind_param("sss",$u,$hash,$n); $st->execute();
echo "admin_ok\n";
// wipe cache files
$cache = __DIR__."/core/storage/framework/cache/data";
$views = __DIR__."/core/bootstrap/cache";
$cviews = __DIR__."/core/storage/framework/views";
foreach ([$cache,$views,$cviews] as $dir) {
  if (!is_dir($dir)) continue;
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
  foreach ($it as $f) { if ($f->isFile()) @unlink($f->getPathname()); }
}
echo "cache_cleared\n";
@unlink(__FILE__);
echo "done\n";
