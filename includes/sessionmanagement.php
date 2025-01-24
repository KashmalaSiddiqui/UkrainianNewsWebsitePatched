<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random 32-byte token
}

// Set session timeout in seconds (30 minutes)
$timeout = 1800;

// Check if the user is logged in
if (isset($_SESSION['LOGIN_TIME'])) {
    // Verify if the session has exceeded the timeout duration
    if (time() - $_SESSION['LOGIN_TIME'] > $timeout) {
        // Destroy the session if it has expired
        session_unset();
        session_destroy();
        header("Location: /index.php?timeout=true"); // Redirect to index.php with timeout message
        exit();
    }
} else {
    // For users who are not logged in
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Define public pages where users do not need to be logged in
    $publicPages = ['main.php', 'login.php', 'news.php', 'register.php', 'index.php'];


    // Redirect to login.php if the current page is not public
    if (!in_array($currentPage, $publicPages)) {
        header("Location: /login.php");
        exit();
    }
}
