<?php
require_once __DIR__ . '/configuration.php';
require_once __DIR__ . '/database.php';

function registerUser($name, $email, $password, $role = 'user') {
    global $conn;
    
    // Make sure that user does not exist
    $user = getUserByEmail($email);
    if ($user)
        return false;

    // Insert user
    try {
        $res = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $res->bindValue(1, $name);
        $res->bindValue(2, $email);
        $res->bindValue(3, password_hash($password, PASSWORD_DEFAULT));
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
    $user = getUserByEmail($email);
    
    // Add user to session if the pw is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'date' => $user['date']
        ];
        return true;
    }
    
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    if (isLoggedIn()) {
        return $_SESSION['user']['role'] === 'admin';
    }
    return false;
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function logoutUser() {
    unset($_SESSION['user']);
}

function isCurrentUser($id) {
    if (isLoggedIn()) {
        return $_SESSION['user']['id'] === $id;
    }
    return false;
}

function getUserById($id) {
    global $conn;
    
    // Fetch user from db
    $user = null;
    try {
        $res = $conn->prepare("SELECT id, name, email, role, date FROM users WHERE id = ?");
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        $user = $res->fetch(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        die(db_maintenance_link($e));
    }
    
    return $user;
}

function getUserByEmail($email) {
    global $conn;
    
    // Fetch user from db
    $user = null;
    try {
        $res = $conn->prepare("SELECT id, name, email, role, date FROM users WHERE email = ?");
        $res->bindValue(1, $email);
        $res->execute();
        $user = $res->fetch(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        die(db_maintenance_link($e));
    }
    
    return $user;
}