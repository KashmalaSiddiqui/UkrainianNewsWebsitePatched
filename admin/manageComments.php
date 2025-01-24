<?php
// session_start();
ob_start();
include('adminHeader.php');
require_once './includes/config/config.php';

//----------------------For docker----------------------
// include('adminHeader.php');
// require_once './includes/config/config.php';
// include_once './includes/config/sessionManagement.php';

$commentsFile = __DIR__ . "/uploads/comments.txt";
// CSRF Protection: Validate the CSRF token before processing any POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }
}

// Download Comment Logs without path traversal
if (isset($_POST['download_log'])) {
    if (file_exists($commentsFile)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="comments.txt"');
        readfile($commentsFile);
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>File not found.</p>";
    }
}


// Add Comment with News ID
if (isset($_POST['add_comment'])) {
    $new_comment = trim($_POST['new_comment']);
    $news_id = intval($_POST['news_id']);

    if (!empty($new_comment) && $news_id > 0) {
        $stmt = $conn->prepare("INSERT INTO comments (news_id, comment) VALUES (?, ?)");
        $stmt->bind_param("is", $news_id, $new_comment);
        $stmt->execute();

        // Append the comment to the comments.txt file
        $comment_entry = "News ID: $news_id | Comment: $new_comment" . PHP_EOL;
        file_put_contents($commentsFile, $comment_entry, FILE_APPEND | LOCK_EX);
    }
}

// Remove Comment
if (isset($_POST['remove_comment'])) {
    $comment_id = intval($_POST['comment_id']);
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
}



// Fetch Comments
$result = $conn->query("SELECT id, news_id, comment FROM comments");
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body class="manage-comments">
    <div class="container manage-comments-container">
        <h1>Manage Comments</h1>

        <div class="comments-row">
            <!-- Add Comment Form -->
            <div class="add-comment">
                <h2>Add a Comment</h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <label for="news_id">News ID:</label><br>
                    <input type="number" name="news_id" id="news_id" placeholder="Enter News ID" required><br><br>
                    <textarea name="new_comment" rows="3" cols="50" placeholder="Write your comment here..." required></textarea><br>
                    <button type="submit" name="add_comment">Add Comment</button>
                </form>
            </div>

            <!-- Download Logs Form -->
            <div class="download-logs">
                <h2>Download Comment Logs</h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" name="download_log">Download</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Display Comments List -->

    <div class="container comments-list">
        <h2>Comments List</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <form method="POST" class="comment-box">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <p><strong>News ID:</strong> <?php echo htmlspecialchars($row['news_id']); ?></p>
                <p><strong>Comment:</strong> <?php echo htmlspecialchars($row['comment']); ?></p>
                <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="remove_comment">Remove</button>
            </form>
        <?php endwhile; ?>
    </div>
</body>

</html>