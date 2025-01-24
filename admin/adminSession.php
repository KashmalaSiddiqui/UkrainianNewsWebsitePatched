<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random 32-byte token
}

// Set admin session timeout in seconds (e.g., 20 minutes for higher security)
$adminTimeout = 1800; // 20 minutes

// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);

// Allow access to specific public pages without login (e.g., login page, registration page)
$publicPages = ['adminLogin.php']; // Add other public pages here
if (in_array($currentPage, $publicPages)) {
    return; // Skip further checks for public pages
}

// Check if admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Check if the session has exceeded the timeout duration
    if (isset($_SESSION['LOGIN_TIME']) && (time() - $_SESSION['LOGIN_TIME'] > $adminTimeout)) {
        // Destroy session and redirect if the session has expired
        session_unset();
        session_destroy();
        header("Location: /adminLogin.php?error=session_expired");
        exit();
    }

    // Update LOGIN_TIME to keep the session active
    $_SESSION['LOGIN_TIME'] = time();
} else {
    // If the admin is not logged in, redirect to the admin login page
    header("Location: /adminLogin.php?error=unauthorized");
    exit();
}
