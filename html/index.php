<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/database.php';
$name = $email = $body = '';
$nameErr = $emailErr = $bodyErr = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['name']))
        $nameErr = 'Name is required';
    else
        $name = $_POST['name'];
    if (empty($_POST['email']))
        $emailErr = 'E-Mail is required';
    else
        $email = $_POST['email'];
    if (empty($_POST['body']))
        $bodyErr = 'Feedback is required';
    else
        $body = $_POST['body'];
    if (empty($nameErr) && empty($emailErr) && empty($bodyErr)) {
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
        <img src="img/round-icons-q5-Db2x3WVc-unsplash.png" style="width: 16%" class="mb-3" alt="logo">
        <h2>Feedback</h2>
        <p class="lead text-center">Leave feedback on <?= BLOG_NAME ?></p>
        <form action="index.php" method="post" class="mt-4 w-75">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control <?= $nameErr ? 'is-invalid' : '' ?>" id="name" name="name" <?= "value=\"$name\"" ?> placeholder="Enter your name">
                <div class="invalid-feedback"><?= $nameErr ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" id="email" name="email" <?= "value=\"$email\"" ?> placeholder="Enter your email">
                <div class="invalid-feedback"><?= $emailErr ?></div>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Feedback</label>
                <textarea class="form-control <?= $bodyErr ? 'is-invalid' : '' ?>" id="body" name="body" <?= "value=\"$body\"" ?> placeholder="Enter your feedback"></textarea>
                <div class="invalid-feedback"><?= $bodyErr ?></div>
            </div>
            <div class="mb-3">
                <input type="submit" name="submit" value="Submit" class="btn btn-dark w-100">
            </div>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>