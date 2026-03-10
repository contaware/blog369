<?php 
require_once __DIR__ . '/configuration.php'; 
require_once __DIR__ . '/auth.php';
$page = basename($_SERVER['SCRIPT_FILENAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= BLOG_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?= BLOG_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a  class="nav-link <?= $page === 'index.php' ? 'active' : '' ?>" 
                            href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a  class="nav-link <?= $page === 'feedback.php' ? 'active' : '' ?>" 
                            href="feedback.php">Feedbacks</a>
                    </li>
                    <li class="nav-item">
                        <a  class="nav-link <?= $page === 'about.php' ? 'active' : '' ?>" 
                            href="about.php">About</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <?= htmlspecialchars(getCurrentUser()['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item-text"><?= htmlspecialchars(getCurrentUser()['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></li>
                                <li><span class="dropdown-item-text">Role: <?= htmlspecialchars(getCurrentUser()['role'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a  class="nav-link <?= $page === 'login.php' ? 'active' : '' ?>" 
                                href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a  class="nav-link <?= $page === 'register.php' ? 'active' : '' ?>" 
                                href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>