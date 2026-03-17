<?php
require_once __DIR__ . '/configuration.php';
require_once __DIR__ . '/auth.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $conn = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch (Throwable $e) {
    die("PDO failed: {$e->getMessage()}\n");
}

function dbMaintenanceLink($e) {
    return "PDO failed: {$e->getMessage()}\n<br><a href=\"maintenance.php\">Go to Maintenance</a> page to solve the problem.\n";
}

function canChangeFeedback($id) {
    global $conn;
    
    try {
        $sql = "SELECT user_id FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        $user_id = $res->fetchColumn();
        if ($user_id === false) // use === because a 0 int is a valid id
            return "Error: could not find the entry with id=$id";
        if (!isCurrentUser($user_id) && !isAdmin())
            return "Error: not allowed to change the entry with id=$id";
        return true;
    }
    catch (Throwable $e) {
        die("PDO failed: {$e->getMessage()}\n");
    }
}