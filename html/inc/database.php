<?php
require_once __DIR__ . '/configuration.php';
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $conn = new PDO($dsn, DB_USER, DB_PASS);
}
catch (Throwable $e) {
    die("PDO failed: {$e->getMessage()}\n");
}
function db_maintenance_link($e) {
    return "PDO failed: {$e->getMessage()}\n<br><a href=\"maintenance.php\">Go to Maintenance</a> page to solve the problem.\n";
}