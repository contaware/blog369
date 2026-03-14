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
    <!-- For production use the corresponding *.min.css versions. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        main {
            /* We need some spacing because of the fixed navbar */
            padding-top: 56px;
        }

        /* Breakpoint indicator (remove that for production) */
        body::before {
            content: "xs";
            color: red;
            font-size: 2rem;
            font-weight: bold;
            position: fixed;
            top: 14px;
            right: 17px;
            z-index: 1100;
        }
        @media (min-width: 576px) {
            body::before {
                content: "sm";
            }
        }
        @media (min-width: 768px) {
            body::before {
                content: "md";
            }
        }
        @media (min-width: 992px) {
            body::before {
                content: "lg";
            }
        }
        @media (min-width: 1200px) {
            body::before {
                content: "xl";
            }
        }
        @media (min-width: 1400px) {
            body::before {
                content: "xxl";
            }
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top navbar-expand-sm navbar-light bg-light">
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
                        <a  class="nav-link <?= $page === 'create.php' ? 'active' : '' ?>" 
                            href="create.php">Create</a>
                    </li>
                    <li class="nav-item">
                        <a  class="nav-link <?= $page === 'about.php' ? 'active' : '' ?>" 
                            href="about.php">About</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <?= htmlSafe(getCurrentUser()['name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item-text"><?= htmlSafe(getCurrentUser()['email']) ?></span></li>
                                <li><span class="dropdown-item-text">Role: <?= htmlSafe(getCurrentUser()['role']) ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a  class="nav-link <?= $page === 'login_user.php' ? 'active' : '' ?>" 
                                href="login_user.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a  class="nav-link <?= $page === 'create_user.php' ? 'active' : '' ?>" 
                                href="create_user.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>