<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';

// If not logged-in, head to login page
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Process form data
$title = $body = '';
$titleErr = $bodyErr = '';
if (isset($_POST['submit'])) {
    if (isset($_POST['title']) && strlen($_POST['title']) > 0)
        $title = $_POST['title'];
    else
        $titleErr = 'Title is required';
    if (isset($_POST['body']) && strlen($_POST['body']) > 0)
        $body = $_POST['body'];
    else
        $bodyErr = 'Feedback is required';
    if ($titleErr === '' && $bodyErr === '') {
        try {
            $sql = "INSERT INTO feedback (title, body, user_id) VALUES (?, ?, ?)";
            $res = $conn->prepare($sql);
            $res->bindValue(1, $title);
            $res->bindValue(2, $body);
            $res->bindValue(3, getCurrentUser()['id']);
            $res->execute();
        }
        catch (Throwable $e) {
            die(db_maintenance_link($e));
        }
        header('Location: feedback.php');
        exit;
    }
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="container d-flex flex-column align-items-center">
        <img src="img/round-icons-q5-Db2x3WVc-unsplash.png" style="width: 120px" class="img-fluid mb-3" alt="logo">
        <h2>Feedback</h2>
        <p class="lead text-center">Leave feedback on <?= BLOG_NAME ?></p>
        <form method="post" class="mt-4 w-75">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input  type="text" 
                        class="form-control <?= $titleErr ? 'is-invalid' : '' ?>" 
                        id="title" name="title" 
                        value="<?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                <div class="invalid-feedback"><?= $titleErr ?></div>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Feedback</label>
                <textarea   class="form-control <?= $bodyErr ? 'is-invalid' : '' ?>" 
                            id="body" name="body"><?= htmlspecialchars($body, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
                <div class="invalid-feedback"><?= $bodyErr ?></div>
            </div>
            <div class="mb-3">
                <button type="submit" name="submit" class="btn btn-dark w-100">Submit</button>
            </div>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>