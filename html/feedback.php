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
foreach ($feedback as &$item) {
    $item['title'] = htmlSafe($item['title']);
    $item['body'] = htmlSafe($item['body']);
    $item['date'] = htmlSafe($item['date']);
}
unset($item); // break reference with last element
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main>
    <div class="container d-flex flex-column align-items-center">
        <h2>Posted Feedbacks</h2>
        <?php if (empty($feedback)): ?>
            <p class="lead mt3">There is no feedback</p>
        <?php else: ?>
            <?php foreach ($feedback as $item): ?>
                <div class="card my-3 p-2 w-75">
                    <?php $user = getUser($item['user_id']); ?>
                    <div class="row text-center justify-content-between">
                        <div class="col-sm-6 col-lg-4 order-lg-0">
                            <div class="text-secondary text-sm-start">
                                <?php $name = htmlSafe($user['name']); ?>
                                <em><?= $name ?></em><br><?= $item['date'] ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 order-lg-2">
                            <?php if (isCurrentUser($item['user_id']) || isAdmin()): ?>
                                <div class="text-danger text-sm-end">
                                    <?= "<a class=\"btn btn-danger\" href=\"delete.php?id={$item['id']}\"><i class=\"bi bi-trash\"></i></a>\n" ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-12 col-lg-4 order-lg-1">
                            <h4 class="card-title" style="text-wrap: balance;"><?= $item['title'] ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <p style="text-wrap: pretty;"><?= $item['body'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>