<?php

include('../includes/config/config.php'); // Database connection DONT CHNAGE EVEN FOR DOCKER



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }
    // Collect and sanitize the form data
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Input validation (basic example)
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("Error: Email or Username already exists.");
    }

    // Hash the password for security
    $hashedPassword = password_hash(trim($password), PASSWORD_BCRYPT);

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Successful registration, redirect to login page
        header("Location: /login.php");
        echo "Successfully Registered";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
