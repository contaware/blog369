<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';
if (isset($_GET['id'])) {
    // Check the given id
    $id = $_GET['id'];
    if (!is_numeric($id))
        die("Error: a numeric id is required");

    // Make sure we are allowed to delete the given id
    try {
        $sql = "SELECT user_id FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        $user_id = $res->fetchColumn();
        if ($user_id === false) // use === because a 0 int is a valid id
            die("Error: could not find the entry with id=$id");
        if (!isCurrentUser($user_id) && !isAdmin())
            die("Error: not allowed to delete the entry with id=$id");
    }
    catch (Throwable $e) {
        die("PDO failed: {$e->getMessage()}\n");
    }

    // Delete the given id
    try {
        $sql = "DELETE FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
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