<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';

// If not logged-in, head to login page
if (!isLoggedIn()) {
    header('Location: login_user.php');
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
            $res->bindValue(3, getCurrentUser()['id'], PDO::PARAM_INT);
            $res->execute();
        }
        catch (Throwable $e) {
            die(db_maintenance_link($e));
        }
        header('Location: index.php');
        exit;
    }
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container d-flex flex-column align-items-center">
        <img src="img/round-icons-q5-Db2x3WVc-unsplash.png" style="width: 120px" class="img-fluid" alt="logo">
        <h2 class="mt-2">Create Feedback</h2>
        <p class="lead text-center">Leave a feedback on <?= BLOG_NAME ?></p>
        <form method="post" class="mt-3 w-75">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input  type="text" 
                        class="form-control <?= $titleErr ? 'is-invalid' : '' ?>" 
                        id="title" name="title" 
                        value="<?= htmlSafe($title) ?>">
                <div class="invalid-feedback"><?= $titleErr ?></div>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Feedback</label>
                <textarea   class="form-control <?= $bodyErr ? 'is-invalid' : '' ?>" 
                            rows="5" 
                            id="body" name="body"><?= htmlSafe($body) ?></textarea>
                <div class="invalid-feedback"><?= $bodyErr ?></div>
            </div>
            <div>
                <button type="submit" name="submit" class="btn btn-dark w-100">Submit</button>
            </div>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>