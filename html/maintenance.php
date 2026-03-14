<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/database.php';

function create_tables() {
    global $conn;
    $msg = '';

    // Create users table if not existing
    // role supports: 'user' or 'admin'
    // (do not use the non-portable ENUM data type for it)
    try {
        $sql = "CREATE TABLE users (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL DEFAULT 'user',
            date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
        $msg .= "Created 'users' table\n";
        
        // Register default admin user
        registerUser(ADMIN_NAME, ADMIN_USER, ADMIN_PASS, 'admin');
    }
    catch (Throwable $e) {
        $msg .= "Could not create 'users' table: {$e->getMessage()}\n";
    }
    
    // Create feedback table if not existing
    try {
        $sql = "CREATE TABLE feedback (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            body LONGTEXT,
            date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_id INT NOT NULL REFERENCES users(id)
        )";
        $conn->exec($sql);
        $msg .= "Created 'feedback' table\n";
    }
    catch (Throwable $e) {
        $msg .= "Could not create 'feedback' table: {$e->getMessage()}\n";
    }

    // Add more tables creation here
    // ...

    return $msg;
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container d-flex flex-column align-items-center">
        <h2>Database maintenance</h2>
        <p class="text-center">
            <?php
            $out = create_tables();
            $out = htmlSafe($out);
            $out = nl2br($out);
            echo $out;
            ?>
        </p>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>