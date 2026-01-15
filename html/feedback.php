<?php
require_once __DIR__ . '/inc/configuration.php';
require_once __DIR__ . '/inc/database.php';
try {
    $sql = 'SELECT * FROM feedback';
    $res = $conn->query($sql);
    $feedback = $res->fetchAll(PDO::FETCH_ASSOC);
}
catch (Throwable $e) {
    die(db_maintenance_link($e));
}
foreach ($feedback as &$item) {
    $item['name'] = htmlspecialchars($item['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $item['email'] = htmlspecialchars($item['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $item['body'] = htmlspecialchars($item['body'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $item['date'] = htmlspecialchars($item['date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
                <div class="card my-3 w-75">
                    <div class="card-body text-center">
                        <?= $item['body'] ?>
                        <div class="text-secondary mt-2">
                            by <?= $item['name'] ?> on <?= $item['date'] ?>
                        </div>
                        <div class="text-secondary mt-2">
                            E-Mail <?= "<a href=\"mailto:{$item['email']}\">{$item['email']}</a>\n" ?>
                        </div>
                        <div class="text-danger mt-2">
                            Delete <?= "<a href=\"delete.php?id={$item['id']}\">this entry (id {$item['id']})</a>\n" ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>