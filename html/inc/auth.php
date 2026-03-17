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
        die(dbMaintenanceLink($e));
    }

    return true;
}

function updateUser($id, $name, $email, $password = '', $role = '') {
    global $conn;
    
    // Make sure that user exists
    $user = getUserById($id);
    if (!$user)
        return false;

    // Update user
    try {
        if ($password !== '' && $role !== '') {
            $res = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            $res->bindValue(1, $name);
            $res->bindValue(2, $email);
            $res->bindValue(3, password_hash($password, PASSWORD_DEFAULT));
            $res->bindValue(4, $role);
            $res->bindValue(5, (int)$id, PDO::PARAM_INT);
        }
        elseif ($password !== '') {
            $res = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $res->bindValue(1, $name);
            $res->bindValue(2, $email);
            $res->bindValue(3, password_hash($password, PASSWORD_DEFAULT));
            $res->bindValue(4, (int)$id, PDO::PARAM_INT);
        }
        elseif ($role !== '') {
            $res = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $res->bindValue(1, $name);
            $res->bindValue(2, $email);
            $res->bindValue(3, $role);
            $res->bindValue(4, (int)$id, PDO::PARAM_INT);
        }
        else {
            $res = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $res->bindValue(1, $name);
            $res->bindValue(2, $email);
            $res->bindValue(3, (int)$id, PDO::PARAM_INT);
        }
        $res->execute();
    }
    catch (Throwable $e) {
        die(dbMaintenanceLink($e));
    }

    // Update session vars
    if (isCurrentUser($id)) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        if ($role !== '')
            $_SESSION['user']['role'] = $role;
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
        return $_SESSION['user']['id'] === (int)$id;
    }
    return false;
}

function getUserById($id) {
    global $conn;
    
    // Fetch user from db
    $user = null;
    try {
        $res = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        $user = $res->fetch(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        die(dbMaintenanceLink($e));
    }
    
    return $user;
}

function getUserByEmail($email) {
    global $conn;
    
    // Fetch user from db
    $user = null;
    try {
        $res = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $res->bindValue(1, $email);
        $res->execute();
        $user = $res->fetch(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        die(dbMaintenanceLink($e));
    }
    
    return $user;
}