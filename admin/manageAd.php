<?php
include('adminHeader.php');
require_once './includes/config/config.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle Adding External Ads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_ad'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    $ad_title = trim($_POST['ad_title']);
    $ad_link = trim($_POST['ad_link']);

    if (!empty($ad_title) && filter_var($ad_link, FILTER_VALIDATE_URL)) {
        $stmt = $conn->prepare("INSERT INTO ads (title, link) VALUES (?, ?)");
        $stmt->bind_param("ss", $ad_title, $ad_link);
        $stmt->execute();
        $success_message = "Ad added successfully!";
    } else {
        $error_message = "Invalid title or link!";
    }
}
// Handle Removing an Ad
if (isset($_POST['remove_ad'])) {
    $ad_id = intval($_POST['ad_id']); // Get the ad ID from the form
    $stmt = $conn->prepare("DELETE FROM ads WHERE id = ?");
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();

    // Reset AUTO_INCREMENT
    $conn->query("ALTER TABLE ads AUTO_INCREMENT = 1");

    // Reorder IDs
    $conn->query("SET @count = 0");
    $conn->query("UPDATE ads SET id = (@count := @count + 1)");

    $success_message = "Ad removed successfully!";
}

//Fetch Ad preview patched version
$ssrf_content = "";
$ssrf_error = ""; // Variable to store error messages

if (isset($_POST['fetch_preview'])) {
    $url = trim($_POST['ad_preview_url']); // User-supplied input

    // Validate URL format
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Extract the host and scheme from the URL
        $host = parse_url($url, PHP_URL_HOST);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        // Enforce HTTPS
        if ($scheme !== 'https') {
            $ssrf_error = "Only HTTPS URLs are allowed.";
        } else {
            // Whitelist allowed domains
            $allowed_domains = ['trusted-domain.com', 'another-safe-domain.com'];

            if (in_array($host, $allowed_domains)) {
                // Resolve the URL to an IP address
                $ip = gethostbyname($host);

                // Prevent access to private/internal IPs
                $private_ip_patterns = [
                    '/^127\./',             // Loopback
                    '/^10\./',              // Private class A
                    '/^192\.168\./',        // Private class C
                    '/^172\.(1[6-9]|2[0-9]|3[0-1])\./' // Private class B
                ];

                $is_private_ip = false;
                foreach ($private_ip_patterns as $pattern) {
                    if (preg_match($pattern, $ip)) {
                        $is_private_ip = true;
                        break;
                    }
                }

                if ($is_private_ip) {
                    $ssrf_error = "Request to private/internal IPs is not allowed.";
                } else {
                    // Initialize cURL
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enforce SSL
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                    // Execute cURL
                    $ssrf_content = curl_exec($ch);

                    if (curl_errno($ch)) {
                        $ssrf_error = "Error fetching content: " . curl_error($ch);
                    } elseif ($ssrf_content === false || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
                        $ssrf_error = "Failed to fetch content. Invalid or inaccessible URL.";
                    }

                    curl_close($ch);
                }
            } else {
                $ssrf_error = "This URL's domain is not allowed.";
            }
        }
    } else {
        $ssrf_error = "Invalid URL format.";
    }
}


// Fetch All Ads
$result = $conn->query("SELECT id, title, link FROM ads");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ads</title>
    <link rel="stylesheet" href="./styles/main.css">
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>

</head>

<body class="manage-ads">
    <div class="container">
        <h1>Manage Ads</h1>
        <div class="ads-row">
            <!-- Add New Ad Section -->
            <div class="add-news">
                <h2>Add New Ad</h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <label for="ad_title">Ad Title:</label>
                    <input type="text" name="ad_title" id="ad_title" placeholder="Enter Ad Title" required>
                    <label for="ad_link">Ad Link (External URL):</label>
                    <input type="url" name="ad_link" id="ad_link" placeholder="https://example.com/ad" required>
                    <button type="submit" name="add_ad">Add Ad</button>
                </form>
                <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
                <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
            </div>
            <!-- Fetch Ad Preview Section -->
            <div class="ad-preview">
                <h2>Fetch Ad Preview</h2>
                <form method="POST">
                    <label for="ad_preview_url">Enter URL to Preview Ad:</label>
                    <input type="url" name="ad_preview_url" id="ad_preview_url" placeholder="https://example.com/image.jpg" required>
                    <button type="submit" name="fetch_preview">Fetch Preview</button>
                </form>
                <?php if (!empty($ssrf_content)): ?>
                    <div class="preview-box">
                        <h3>Fetched Content:</h3>
                        <img src="<?php echo htmlspecialchars($url); ?>" alt="Ad Preview" class="preview-image">
                    </div>
                <?php elseif (isset($ssrf_error)): ?>
                    <p style="color: red;"><?php echo $ssrf_error; ?></p>
                <?php endif; ?>
            </div>

        </div>

        <div class="ads-row">
            <!-- Current Ads Section -->
            <div class="display-ads">
                <h2>Current Ads</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($row['link']); ?>
                                </a>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="ad_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="remove_ad" style="background-color: #e74c3c; color: #fff; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>