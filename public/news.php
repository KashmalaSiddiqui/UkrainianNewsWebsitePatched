<?php
// include('../includes/config/config.php');
// include('../includes/header.php');
// Include database connection
/*-----------------------------FOR DOCKER-------------------------------*/
include('./includes/config/config.php');
include('./includes/header.php');
/* solved Vulnerable: DOM-based XSS
Solved this by using htmlspecialchars() function to escape the user input
*/

// Start or resume the session and generate a CSRF token if it doesn’t exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get the news ID from the query parameter
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid news ID.");
}
// $commentsFile = __DIR__ . "../admin/uploads/comments.txt";

// Fetch the news article
$stmt = $conn->prepare("SELECT title, content, url_to_image, source_name, published_at FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if (!$news) {
    die("News article not found.");
}

// Fetch all comments for the news article
$stmt = $conn->prepare("SELECT comment FROM comments WHERE news_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$comments = $stmt->get_result();

// Fetch ads
$ads = $conn->query("SELECT title, link FROM ads LIMIT 4");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <!-- Back Arrow -->
    <?php if (isset($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)): ?>
        <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8'); ?>" class="back-arrow-news">&larr; Back</a>
    <?php endif; ?>
    <div class="complete-container-news">
        <div class="sidebar left-sidebar">
            <?php while ($ad = $ads->fetch_assoc()): ?>
                <?php if (filter_var($ad['link'], FILTER_VALIDATE_URL)): ?>
                    <div class="ad">
                        <a href="<?php echo htmlspecialchars($ad['link'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($ad['link'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($ad['title'], ENT_QUOTES, 'UTF-8'); ?>" class="ad-image">
                        </a>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>

        <!-- News Article Container -->
        <div class="page-container">
            <!-- News Title -->
            <h2 style="font-size: 2rem; color: #222;"><?php echo htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <!-- News Source and Publish Date -->
            <small style="color: gray; display: block; margin-bottom: 20px;">
                Source: <?php echo htmlspecialchars($news['source_name'] ?? 'Unknown Source', ENT_QUOTES, 'UTF-8'); ?> |
                Published At: <?php echo htmlspecialchars($news['published_at'], ENT_QUOTES, 'UTF-8'); ?>
            </small>
            <!-- News Image (only if exists) -->
            <?php if (!empty($news['url_to_image']) && filter_var($news['url_to_image'], FILTER_VALIDATE_URL)): ?>
                <img src="<?php echo htmlspecialchars($news['url_to_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="News Image" style="width: 100%; height: auto; margin-bottom: 20px; border-radius: 5px;">
            <?php endif; ?>
            <!-- News Content -->
            <p style="line-height: 1.8; color: #444; font-size: 1.1rem;">
                <?php echo nl2br(htmlspecialchars($news['content'] ?? 'Content not available.', ENT_QUOTES, 'UTF-8')); ?>
            </p>

            <!-- Approved Comments Section -->
            <div class="approved-comments" style="margin-top: 40px;">
                <h3 style="margin-bottom: 10px;">Approved Comments</h3>
                <ul class="comments-list">
                    <?php while ($comment = $comments->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <!-- Comment Box -->
            <div class="comment-section" style="margin-top: 40px;">
                <h3 style="margin-bottom: 10px;">Leave a Comment</h3>
                <form action="./scripts/submitComment.php" method="POST">
                    <textarea name="comment" rows="5" placeholder="Write your comment here..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
                    <input type="hidden" name="news_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Include CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" style="margin-top: 10px; padding: 10px 20px; background-color: #004080; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Submit Comment
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Sidebar (Ads) -->
        <div class="sidebar right-sidebar">
            <?php
            $ads->data_seek(0); // Reset the pointer to the beginning of the result set
            while ($ad = $ads->fetch_assoc()): ?>
                <?php if (filter_var($ad['link'], FILTER_VALIDATE_URL)): ?>
                    <div class="ad">
                        <a href="<?php echo htmlspecialchars($ad['link'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($ad['link'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($ad['title'], ENT_QUOTES, 'UTF-8'); ?>" class="ad-image">
                        </a>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #004080; color: white; text-align: center; padding: 10px 0; position: relative; bottom: 0; width: 100%;">
        <p>&copy; <?php echo date("Y"); ?> Kyiv News</p>
    </footer>
</body>

</html>