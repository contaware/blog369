<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';

// If already logged-in, head to home page
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Process form data
$regDone = $regErr = '';
$name = $email = $password = $confirmPassword = '';
$nameErr = $emailErr = $pwErr = $confirmPwErr = '';
if (isset($_POST['submit'])) {
    if (isset($_POST['name']) && strlen($_POST['name']) > 0)
        $name = $_POST['name'];
    else
        $nameErr = 'Name is required';
    if (isset($_POST['email']) && strlen($_POST['email']) > 0)
        $email = $_POST['email'];
    else
        $emailErr = 'Email is required';
    if (isset($_POST['password']) && strlen($_POST['password']) > 0)
        $password = $_POST['password'];
    else
        $pwErr = 'Password is required';
    if (isset($_POST['confirm_password']) && strlen($_POST['confirm_password']) > 0)
        $confirmPassword = $_POST['confirm_password'];
    else
        $confirmPwErr = 'Password confirmation is required';
    if ($pwErr === '' && $confirmPwErr === '') {
        if ($password !== $confirmPassword)
            $confirmPwErr = $pwErr = 'Passwords do not match';
        elseif (strlen($password) < 8)
            $confirmPwErr = $pwErr = 'Password must be at least 8 characters long';
    }
    if ($nameErr === '' && $emailErr === '' && $pwErr === '' && $confirmPwErr === '') {
        if (registerUser($name, $email, $password))
            $regDone = 'Registration successful';
        else
            $regErr = 'Email already exists';
    }
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Register</h3>
                    <?php if ($regErr): ?>
                        <div class="alert alert-danger">
                            <?= $regErr ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($regDone): ?>
                        <div class="alert alert-success">
                            <?= $regDone ?>
                        </div>
                        <div class="text-center">
                            <a href="login.php" class="btn btn-dark">Go to Login</a>
                        </div>
                    <?php else: ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input  type="text" 
                                        class="form-control <?= $nameErr ? 'is-invalid' : '' ?>" 
                                        id="name" name="name"
                                        value="<?= htmlSafe($name) ?>">
                                <div class="invalid-feedback"><?= $nameErr ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input  type="email" 
                                        class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" 
                                        id="email" name="email"
                                        value="<?= htmlSafe($email) ?>">
                                <div class="invalid-feedback"><?= $emailErr ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input  type="password" 
                                        class="form-control <?= $pwErr ? 'is-invalid' : '' ?>" 
                                        id="password" name="password"
                                        value="<?= htmlSafe($password) ?>">
                                <div class="invalid-feedback"><?= $pwErr ?></div>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input  type="password" 
                                        class="form-control <?= $confirmPwErr ? 'is-invalid' : '' ?>" 
                                        id="confirm_password" name="confirm_password"
                                        value="<?= htmlSafe($confirmPassword) ?>">
                                <div class="invalid-feedback"><?= $confirmPwErr ?></div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="submit" class="btn btn-dark w-100">Register</button>
                            </div>
                        </form>
                        <div class="text-center">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/inc/footer.php'; ?>