<?php
/**
 * DISABLED — was overwriting Hostinger DB on every visit.
 * Your DB is locked via core/.env.locked — do not use this script.
 */
header('Content-Type: text/plain; charset=utf-8');
http_response_code(403);
echo "fix-db-env DISABLED.\n";
echo "DB is permanent in core/.env (locked).\n";
echo "If you must reset, delete core/.env.locked first (not recommended).\n";
exit;
