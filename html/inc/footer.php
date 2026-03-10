<?php require_once __DIR__ . '/configuration.php'; ?>
<footer class="text-center mt-5">
    Copyright &copy; <?= new DateTimeImmutable()->format('Y'), ' &ndash; ', BLOG_NAME ?>
</footer>
<!-- Always place this before the body closing tag.
     For production use the corresponding *.min.js version. -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.js" crossorigin="anonymous"></script>
</body>
</html>