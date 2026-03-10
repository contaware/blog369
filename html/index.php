<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/database.php';
$name = $email = $body = '';
$nameErr = $emailErr = $bodyErr = '';
if (isset($_POST['submit'])) {
    if (isset($_POST['name']) && strlen($_POST['name']) > 0)
        $name = $_POST['name'];
    else
        $nameErr = 'Name is required';
    if (isset($_POST['email']) && strlen($_POST['email']) > 0)
        $email = $_POST['email'];
    else
        $emailErr = 'Email is required';
    if (isset($_POST['body']) && strlen($_POST['body']) > 0)
        $body = $_POST['body'];
    else
        $bodyErr = 'Feedback is required';
    if ($nameErr === '' && $emailErr === '' && $bodyErr === '') {
        try {
            $sql = "INSERT INTO feedback (name, email, body) VALUES (?, ?, ?)";
            $res = $conn->prepare($sql);
            $res->bindValue(1, $name);
            $res->bindValue(2, $email);
            $res->bindValue(3, $body);
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
                <label for="name" class="form-label">Name</label>
                <input  type="text" 
                        class="form-control <?= $nameErr ? 'is-invalid' : '' ?>" 
                        id="name" name="name" 
                        value="<?= htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                <div class="invalid-feedback"><?= $nameErr ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input  type="email" 
                        class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" 
                        id="email" name="email" 
                        value="<?= htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                <div class="invalid-feedback"><?= $emailErr ?></div>
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