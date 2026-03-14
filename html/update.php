<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';

// Clear vars
$id = null;
$title = $body = '';
$titleErr = $bodyErr = '';

// Process form data
if (isset($_POST['id'])) {
    // Check the given id
    $id = $_POST['id'];
    if (!is_numeric($id))
        die("Error: a numeric id is required");

    // Make sure we are allowed to update the given id
    if (($ret = can_change_feedback($id)) !== true)
        die($ret);

    // Update the data
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
            $sql = "UPDATE feedback SET title = ?, body = ? WHERE id = ?";
            $res = $conn->prepare($sql);
            $res->bindValue(1, $title);
            $res->bindValue(2, $body);
            $res->bindValue(3, $id, PDO::PARAM_INT);
            $res->execute();
        }
        catch (Throwable $e) {
            die(db_maintenance_link($e));
        }
        header('Location: index.php');
        exit;
    }
}
// Prepare the data to fill the form
elseif (isset($_GET['id'])) {
    // Check the given id
    $id = $_GET['id'];
    if (!is_numeric($id))
        die("Error: a numeric id is required");

    // Fetch feedback by given id
    try {
        $sql = "SELECT * FROM feedback WHERE id = ?";
        $res = $conn->prepare($sql);
        $res->bindValue(1, (int)$id, PDO::PARAM_INT);
        $res->execute();
        $feedback = $res->fetch(PDO::FETCH_ASSOC);
        if ($feedback === false)
            die("Error: could not find the entry with id=$id");
    }
    catch (Throwable $e) {
        die("PDO failed: {$e->getMessage()}\n");
    }
    $title = $feedback['title'];
    $body = $feedback['body'];
}
else
    die("Error: provide an id to update");
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container d-flex flex-column align-items-center">
        <img src="img/round-icons-q5-Db2x3WVc-unsplash.png" style="width: 120px" class="img-fluid" alt="logo">
        <h2 class="mt-2">Update Feedback</h2>
        <p class="lead text-center">Edit your feedback on <?= BLOG_NAME ?></p>
        <!-- Set the action url explicitly to remove the id query parameter, 
             since the id is already posted through the hidden field below. -->
        <form method="post" action="update.php" class="mt-3 w-75">
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
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" name="submit" class="btn btn-dark w-100">Update</button>
            </div>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>