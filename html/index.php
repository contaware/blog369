<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/database.php';

try {
    $sql = "SELECT * FROM feedback";
    $res = $conn->query($sql);
    $feedback = $res->fetchAll(PDO::FETCH_ASSOC);
}
catch (Throwable $e) {
    die(db_maintenance_link($e));
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="py-4 container d-flex flex-column align-items-center">
        <img src="img/round-icons-q5-Db2x3WVc-unsplash.png" style="width: 120px" class="img-fluid" alt="logo">
        <h2 class="mt-2">Feedbacks</h2>
        <p class="lead text-center">View the feedbacks on <?= BLOG_NAME ?></p>
<?php if (empty($feedback)): ?>
        <p class="lead mt3">There is no feedback</p>
<?php else: ?>
<?php foreach ($feedback as $item): ?>
        <div class="card my-3 p-2 w-75">
            <div class="row text-center justify-content-between">
                <div class="col-sm-6 col-lg-4 order-lg-0">
                    <div class="text-secondary text-sm-start">
                        <em><?= htmlSafe(getUserById($item['user_id'])['name']) ?></em><br>
                        <span><?= htmlSafe($item['date']) ?></span>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 order-lg-2">
<?php if (isCurrentUser($item['user_id']) || isAdmin()): ?>
                    <div class="text-sm-end">
                        <?= "<a class=\"btn btn-primary m-2\" href=\"update.php?id={$item['id']}\"><i class=\"bi bi-pencil-square\"></i></a>\n" ?>
                        <button type="button" class="btn btn-danger m-2" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= "{$item['id']}" ?>"><i class="bi bi-trash"></i></button>
                        <div class="modal fade" id="delete-modal-<?= "{$item['id']}" ?>" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Feedback</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">Do you really want to delete the '<?= htmlSafe($item['title']) ?>' feedback?</div>
                                    <div class="modal-footer">
                                        <a href="<?= "delete.php?id={$item['id']}" ?>" class="btn btn-danger">Delete</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php endif; ?>
                </div>
                <div class="col-sm-12 col-lg-4 order-lg-1">
                    <h4 class="card-title" style="text-wrap: balance;"><?= htmlSafe($item['title']) ?></h4>
                </div>
            </div>
            <div class="card-body">
                <p style="text-wrap: pretty;"><?= htmlSafe($item['body']) ?></p>
            </div>
        </div>
<?php endforeach; ?>
<?php endif; ?>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>