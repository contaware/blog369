<?php

// This configuration.php file must always be included at the beginning 
// of every php file as it starts the session and defines global constants.

// Start session
session_start();

// Set the wanted blog name
define('BLOG_NAME', 'Blog 369');

// Database
define('DB_HOST', 'db');
define('DB_PORT', '3306');
define('DB_USER', 'blog');
define('DB_PASS', '1234');
define('DB_NAME', 'blogdb');
