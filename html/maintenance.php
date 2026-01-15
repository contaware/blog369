<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/database.php';
function create_tables() {
    global $conn;
    $msg = '';
    try {
        switch (DB_TYPE) {
            case 0: // sqlite
                $sql = "CREATE TABLE feedback (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    name TEXT,
                    email TEXT,
                    body TEXT,
                    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )";
                break;
            case 1: // mysql (or mariadb)
                $sql = "CREATE TABLE feedback (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255),
                    email VARCHAR(255),
                    body LONGTEXT,
                    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )";
                break;
            case 2: // microsoft sql server
                $sql = "CREATE TABLE feedback (
                    id INT NOT NULL IDENTITY PRIMARY KEY,
                    name VARCHAR(255),
                    email VARCHAR(255),
                    body VARCHAR(MAX),
                    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )";
                break;
            default:
                $sql = '';
        }
        $conn->exec($sql);
        $msg .= "Created 'feedback' table\n";
    }
    catch (Throwable $e) {
        $msg .= "Could not create 'feedback' table: {$e->getMessage()}\n";
        return $msg;
    }
    // Add more tables creation here
    return $msg;
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="container d-flex flex-column align-items-center">
        <h2>Database maintenance</h2>
        <p class="text-center">
            <?php
            $out = create_tables();
            $out = htmlspecialchars($out, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $out = nl2br($out);
            echo $out;
            ?>
        </p>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>