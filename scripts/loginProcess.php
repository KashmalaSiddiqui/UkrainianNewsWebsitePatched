<?php
ob_start(); // Start output buffering
//dont change even for docker
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../includes/config/config.php');




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

        die("Invalid CSRF token.");
    }
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    /* Solved Vulnerability: CWE-692 Incomplete Denylist for XSS Prevention
    rather then using a denylist, we should use a allowlist to only allow certain characters
    and does not allow any other 
    */

    // Incomplete Denylist for XSS Prevention (VULNERABLE) hence we use allowlist
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        die("Invalid username format.");
    }

    // echo "Welcome, " . $username;

    // Database Query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['username'] = htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
        $_SESSION['LOGIN_TIME'] = time();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token4
        // print_r($_SESSION);
        session_write_close();

        // header("Location: ../public/main.php"); // Redirect to index.php
        //--------------------------------FOR DOCKER--------------------------------
        header("Location: /main.php"); // Redirect to index.php
        exit();
    } else {
        echo ('Invalid credentials');
        header("Location: /login.php?error=invalid_credentials");
        exit();
    }

    // }
    $stmt->close();
    $conn->close();
}

ob_end_flush(); // Flush the output buffer
