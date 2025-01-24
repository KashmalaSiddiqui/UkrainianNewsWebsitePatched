<?php
// include_once './includes/sessionManagement.php';
include_once 'adminSession.php';


if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Delete the session cookie for added security
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
}

header("Location: adminLogin.php"); // Redirect to the home page
exit();
