<?php
require_once __DIR__ . '/inc/database.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id))
        die("Error: a numeric id is required");
    try {
        $sql = "DELETE FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        if ($res->rowCount() > 0)
            header('Location: feedback.php');
        else
            die("Error: could not delete the entry with id=$id");
    }
    catch (Throwable $e) {
        die("PDO failed: {$e->getMessage()}\n");
    }
}