<?php
require_once __DIR__ . '/configuration.php';
require_once __DIR__ . '/database.php';

function registerUser($name, $email, $password, $role = 'user') {
    global $conn;
    
    // Check whether email already exists
    try {
        $res = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $res->bindValue(1, $email);
        $res->execute();
        if ($res->fetch())
            return false; // email already exists
    }
    catch (Throwable $e) {
        die(db_maintenance_link($e));
    }

    // Insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $res = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $res->bindValue(1, $name);
        $res->bindValue(2, $email);
        $res->bindValue(3, $hashedPassword);
        $res->bindValue(4, $role);
        $res->execute();
    }
    catch (Throwable $e) {
        die(db_maintenance_link($e));
    }

    return true;
}

function loginUser($email, $password) {
    global $conn;
    
    // Fetch user from db
    $user = null;
    try {
        $res = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $res->bindValue(1, $email);
        $res->execute();
        $user = $res->fetch(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        die(db_maintenance_link($e));
    }
    
    // Add user to session if the pw is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }
    
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function logoutUser() {
    unset($_SESSION['user']);
}
