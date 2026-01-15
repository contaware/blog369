<?php
require_once __DIR__ . '/configuration.php';
switch (DB_TYPE) {
case 0: // sqlite
    $dsn = "sqlite:" . DB_HOST;
    break;
case 1: // mysql (or mariadb)
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    break;
case 2: // microsoft sql server
    $dsn = "sqlsrv:Server=" . DB_HOST . "," . DB_PORT . ";Database=" . DB_NAME . ";TrustServerCertificate=true";
    break;
default:
    $dsn = '';
}
try {
    $conn = new PDO($dsn, DB_USER, DB_PASS);
}
catch (Throwable $e) {
    die("PDO failed: {$e->getMessage()}\n");
}
function db_maintenance_link($e) {
    return "PDO failed: {$e->getMessage()}\n<br><a href=\"maintenance.php\">Go to Maintenance</a> page to solve the problem.\n";
}