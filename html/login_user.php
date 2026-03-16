<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';

// If already logged-in, head to create page
if (isLoggedIn()) {
    header('Location: create.php');
    exit;
}

// Process form data
$email = $password = '';
$emailErr = $pwErr = $loginErr = '';
if (isset($_POST['submit'])) {
    if (isset($_POST['email']) && strlen($_POST['email']) > 0)
        $email = $_POST['email'];
    else
        $emailErr = 'Email is required';
    if (isset($_POST['password']) && strlen($_POST['password']) > 0)
        $password = $_POST['password'];
    else
        $pwErr = 'Password is required';
    if ($emailErr === '' && $pwErr === '') {
        if (loginUser($email, $password)) {
            header('Location: create.php');
            exit;
        }
        else
            $loginErr = 'Invalid email or password';
    }
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Login</h3>
<?php if ($loginErr): ?>
                        <div class="alert alert-danger"><?= $loginErr ?></div>
<?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input  type="text" 
                                        class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" 
                                        id="email" name="email" 
                                        value="<?= htmlSafe($email) ?>">
                                <div class="invalid-feedback"><?= $emailErr ?></div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input  type="password" 
                                        class="form-control <?= $pwErr ? 'is-invalid' : '' ?>" 
                                        id="password" name="password" 
                                        value="<?= htmlSafe($password) ?>">
                                <div class="invalid-feedback"><?= $pwErr ?></div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="submit" class="btn btn-dark w-100">Login</button>
                            </div>
                        </form>
                        <div class="text-center">
                            <p>Don't have an account? <a href="create_user.php">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>