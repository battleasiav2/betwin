<?php
header("Content-Type: application/json");
require __DIR__."/core/vendor/autoload.php";
$app = require __DIR__."/core/bootstrap/app.php";
$k = $app->make(Illuminate\Contracts\Console\Kernel::class);
$k->bootstrap();
$k->call("view:clear");
$n=0; foreach (glob(__DIR__."/core/storage/framework/views/*.php") ?: [] as $f) { if(@unlink($f)) $n++; }
@unlink(__FILE__);
echo json_encode(["code"=>0,"cleared"=>$n]);
