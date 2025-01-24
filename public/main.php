<?php
// include('../includes/header.php');
// include('../includes/config/config.php');
// include('../includes/footer.php');
// include('../scripts/fetchnews.php');

/*---------------------------------- For docker---------------------------------------*/
// Include database connection
include('./includes/header.php');
include('./includes/config/config.php');
include('./includes/footer.php');
include('./scripts/fetchnews.php'); // Dynamically fetch news and save to the database



/* SOLVED Vulnerability  :  XSS
Removed the vulnerable code and added the secure code to prevent  XSS attacks.
using html special chars to prevent XSS attacks and using prepared statements to prevent SQL Injection attacks.
also when outputting the data to the page, we are using htmlspecialchars to prevent XSS attacks.
*/
$searchQuery = "";
$output = "";
$news = []; // Initialize as an empty array
$limit = 15; // Number of news articles per page (updated to 15)
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Calculate offset
// echo "Session Debug:<br>";
// print_r($_SESSION);

if (isset($_GET['query'])) {
    $searchQuery = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); // Using htmlspecialchars to prevent XSS attacks
    $output = "<div id=\"results\" title=\"" . htmlspecialchars($searchQuery) . "\">Search Results for: <b>" . htmlspecialchars($searchQuery) . "</b></div>";
    // Filter news results based on the search query
    $stmt = $conn->prepare("SELECT id, title, content, url_to_image, published_at FROM news WHERE title LIKE ? OR content LIKE ? ORDER BY published_at DESC");
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Sanitize fetched data to prevent XSS
            $news[] = array_map(function ($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }, $row);
        }
    }
} else {
    // Dynamically fetch news and save to the database
    if (!function_exists('fetch_news')) {
        include('./scripts/fetchnews.php');
    }

    // Fetch all news from the database
    $stmt = $conn->prepare("SELECT id, title, content, url_to_image, published_at 
                            FROM news 
                            ORDER BY published_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Sanitize fetched data to prevent XSS
            $news[] = array_map(function ($value) {
                return $value;
            }, $row);
        }
    } else {
        $output .= "<p>No news available at the moment.</p>";
    }
}

// Fetch total number of records to calculate total pages
$totalRecordsResult = $conn->query("SELECT COUNT(*) as total FROM news");
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyiv News</title>
    <!-- <link rel="stylesheet" href="../styles/main.css">  W/O docker-->
    <link rel="stylesheet" href="./styles/main.css">

    <style>
        /* Basic layout */
        .container {
            display: flex;
            flex-direction: row;
            margin: 20px;
            gap: 20px;
            width: calc(100% - 40px);
        }

        .main-content {
            flex: 4;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .side-panel {
            flex: 1;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .main-news img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .main-news-heading a {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: #000;
        }

        .main-news-heading a:hover {
            color: #007bff;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .news-box {
            border: 1px solid #ddd;
            padding: 10px;
            background: #f9f9f9;
            text-align: center;
        }

        .news-box img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .news-box h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .news-box a {
            text-decoration: none;
            color: #000;
        }

        .news-box a:hover {
            color: #007bff;
        }

        .signup-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .signup-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .signup-box p {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .signup-box input[type="email"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .signup-box button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .signup-box button:hover {
            background-color: #333;
        }
    </style>
</head>

<body class="main-page">

    <div class="container">
        <?php if (isset($output)) echo $output; ?>
        <!-- Main Content Area -->
        <div class="main-content">
            <?php if (!empty($news[0])): ?>
                <div class="main-news">
                    <!-- Validate and sanitize the image URL -->
                    <?php if (isset($news[0]['url_to_image']) && filter_var($news[0]['url_to_image'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?php echo htmlspecialchars($news[0]['url_to_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Main News Image">
                    <?php else: ?>
                        <img src="placeholder.jpg" alt="Placeholder Image">
                    <?php endif; ?>

                    <!-- Sanitize the title and ID for HTML output -->
                    <h2 class="main-news-heading">
                        <a href="news.php?id=<?php echo htmlspecialchars($news[0]['id']); ?>">
                            <?php echo htmlspecialchars($news[0]['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h2>

                    <!-- Sanitize published_at for safe HTML display -->
                    <small>Published At: <?php echo htmlspecialchars($news[0]['published_at'], ENT_QUOTES, 'UTF-8'); ?></small>
                </div>
            <?php else: ?>
                <p>No main news available.</p>
            <?php endif; ?>

            <!-- Grid for Remaining News -->
            <div class="news-grid">
                <?php for ($i = 1; $i < count($news); $i++): ?>
                    <div class="news-box">
                        <!-- Validate and sanitize the image URL -->
                        <?php if (isset($news[$i]['url_to_image']) && filter_var($news[$i]['url_to_image'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo htmlspecialchars($news[$i]['url_to_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="News Thumbnail">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Placeholder Image">
                        <?php endif; ?>

                        <!-- Sanitize the title and ID for HTML output -->
                        <h3>
                            <a href="news.php?id=<?php echo htmlspecialchars($news[$i]['id']); ?>">
                                <?php echo htmlspecialchars($news[$i]['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>

                        <!-- Sanitize published_at for safe HTML display -->
                        <small>Published At: <?php echo htmlspecialchars($news[$i]['published_at'], ENT_QUOTES, 'UTF-8'); ?></small>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="side-panel">
            <!-- Must Read Section -->
            <div class="must-read">
                <h3>Must Read</h3>
                <?php for ($i = 4; $i < 7 && $i < count($news); $i++): ?>
                    <div class="side-news-item">
                        <!-- Validate and sanitize the image URL -->
                        <?php if (isset($news[$i]['url_to_image']) && filter_var($news[$i]['url_to_image'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo htmlspecialchars($news[$i]['url_to_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Thumbnail">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Placeholder Image">
                        <?php endif; ?>

                        <!-- Sanitize the title and ID for HTML output -->
                        <a href="news.php?id=<?php echo htmlspecialchars($news[$i]['id']); ?>">
                            <?php echo htmlspecialchars($news[$i]['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- More Headlines Section -->
            <div class="more-headlines">
                <h3>More Headlines</h3>
                <?php for ($i = 7; $i < 10 && $i < count($news); $i++): ?>
                    <div class="side-news-item">
                        <!-- Validate and sanitize the image URL -->
                        <?php if (isset($news[$i]['url_to_image']) && filter_var($news[$i]['url_to_image'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo htmlspecialchars($news[$i]['url_to_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Thumbnail">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Placeholder Image">
                        <?php endif; ?>

                        <!-- Sanitize the title and ID for HTML output -->
                        <a href="news.php?id=<?php echo htmlspecialchars($news[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($news[$i]['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="signup-box">
                <h3>Sign up for our Newsletter</h3>
                <p>The latest news from around the world. Timely. Accurate. Fair.</p>
                <form action="" method="GET">
                    <input type="email" name="email" placeholder="E-mail address" required>
                    <button type="submit" name="subscribe">Subscribe</button>
                </form>
                <?php
                if (isset($_GET['subscribe']) && !empty($_GET['email'])) {
                    // Sanitize the input to prevent XSS
                    $email = htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8');
                    echo "<p>Thank you for subscribing with: <b>$email</b></p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>