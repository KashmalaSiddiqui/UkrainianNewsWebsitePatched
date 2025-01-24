<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../includes/config/config.php'); //dont change even for docker 


if (!isset($_SESSION['username'])) {
    header("Location: /login.php");
    exit();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }
    $news_id = $_POST['news_id'] ?? null;
    $comment = $_POST['comment'] ?? null;

    // Validate input
    if (empty($news_id) || empty($comment)) {
        die("News ID or comment cannot be empty.");
    }


    // Save the comment directly
    $stmt = $conn->prepare("INSERT INTO comments (news_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $news_id, $comment);
    // $commentsFile = __DIR__ . "/../admin/uploads/comments.txt";
    // $comment_entry = "News ID: $news_id | Comment: $comment" . PHP_EOL;  // Append the comment to the comments.txt file


    // file_put_contents($commentsFile, $comment_entry, FILE_APPEND | LOCK_EX);


    if ($stmt->execute()) {
        // Redirect to the news page
        // Regenerate CSRF Token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        // header("Location: /news.php?id=$news_id");-----------FOR DOCKER----------------
        header("Location: /news.php?id=$news_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
