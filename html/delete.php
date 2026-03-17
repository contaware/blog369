<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Make sure we are allowed to delete the given id
    if (($ret = canChangeFeedback($id)) !== true)
        die($ret);

    // Delete the given id
    try {
        $sql = "DELETE FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, $id, PDO::PARAM_INT);
        $res->execute();
        if ($res->rowCount() > 0)
            header('Location: index.php');
        else
            die("Error: could not delete the entry with id=$id");
    }
    catch (Throwable $e) {
        die("PDO failed: {$e->getMessage()}\n");
    }
}
else
    die("Error: provide an id to delete");