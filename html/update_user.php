<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';

// Clear vars
$id = null;
$updateDone = $updateErr = '';
$name = $email = $password = $confirmPassword = '';
$nameErr = $emailErr = $pwErr = $confirmPwErr = '';

// Process form data
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Make sure we are allowed to update the given id
    if (!isCurrentUser($id) && !isAdmin())
        die("Error: not allowed to change the user with id=$id");

    // Update the data
    if (isset($_POST['name']) && strlen($_POST['name']) > 0)
        $name = $_POST['name'];
    else
        $nameErr = 'Name is required';
    if (isset($_POST['email']) && strlen($_POST['email']) > 0)
        $email = $_POST['email'];
    else
        $emailErr = 'Email is required';
    if (isset($_POST['password']))
        $password = $_POST['password'];
    else
        $pwErr = 'Password is required';
    if (isset($_POST['confirm_password']))
        $confirmPassword = $_POST['confirm_password'];
    else
        $confirmPwErr = 'Password confirmation is required';
    if ($pwErr === '' && $confirmPwErr === '') {
        if ($password !== $confirmPassword)
            $confirmPwErr = $pwErr = 'Passwords do not match';
        elseif (strlen($password) > 0 && strlen($password) < 8)
            $confirmPwErr = $pwErr = 'Password must be at least 8 characters long';
    }
    if ($nameErr === '' && $emailErr === '' && $pwErr === '' && $confirmPwErr === '') {
        if (updateUser($id, $name, $email, $password))
            $updateDone = 'User updated successfully';
        else
            $updateErr = 'User does not exist';
    }
}
// Prepare the data to fill the form
elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Make sure we are allowed to update the given id
    if (!isCurrentUser($id) && !isAdmin())
        die("Error: not allowed to change the user with id=$id");

    // Fetch user by given id
    $user = getUserById($id);
    if (!$user)
        die("Error: could not find the user with id=$id");
    $name = $user['name'];
    $email = $user['email'];
}
else
    die("Error: provide a user id to update");
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">User Settings</h3>
                        <?php if ($updateErr): ?>
                            <div class="alert alert-danger">
                                <?= $updateErr ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($updateDone): ?>
                            <div class="alert alert-success">
                                <?= $updateDone ?>
                            </div>
                            <div class="text-center">
                                <a href="index.php" class="btn btn-dark">Go to Home</a>
                            </div>
                        <?php else: ?>
                            <!-- Set the action url explicitly to remove the id query parameter, 
                                 since the id is already posted through the hidden field below. -->
                            <form method="post" action="update_user.php">
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
                                            placeholder="Leave empty to keep existing password" 
                                            autocomplete="new-password" 
                                            value="<?= htmlSafe($password) ?>">
                                    <div class="invalid-feedback"><?= $pwErr ?></div>
                                </div>
                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input  type="password" 
                                            class="form-control <?= $confirmPwErr ? 'is-invalid' : '' ?>" 
                                            id="confirm_password" name="confirm_password" 
                                            placeholder="Leave empty to keep existing password" 
                                            autocomplete="new-password" 
                                            value="<?= htmlSafe($confirmPassword) ?>">
                                    <div class="invalid-feedback"><?= $confirmPwErr ?></div>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" name="submit" class="btn btn-dark w-100">Update</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>