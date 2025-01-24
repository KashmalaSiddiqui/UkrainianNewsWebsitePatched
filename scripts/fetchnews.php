<?php
// include('../includes/config/config.php');

/* -----------------------------------For Docker----------------------------------*/
include('./includes/config/config.php');



// Enable error reporting (optional for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*Vulnerability 4: Solved SSRF (Server-Side Request Forgery)
Fixes:
- Validate user input strictly to avoid accepting URLs for SSRF exploits.using htmlspecialchars() and htmlspecialchars_decode
 function to escape the user input
- do not allow the user inputted URL to be directly fetched
- also checked if the url are trying to access internal source and if it is then it is replaced with #.
*/

// News API setup
$searchQuery = $_GET['query'] ?? null;
$apiKey = "e9ce1e6f24774aa0b15e46c02be021b2";

if ($searchQuery) {
    // Strictly sanitize the query input
    $searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');
    // Fetch news based on the sanitized search query
    $apiUrl = "https://newsapi.org/v2/everything?q=" . urlencode($searchQuery) . "&apiKey=$apiKey";
} else {
    // Default: Fetch top headlines
    $apiUrl = "https://newsapi.org/v2/top-headlines?q=ukrain&apiKey=$apiKey";
}

// Initialize cURL with improved security settings
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Enforce SSL verification-----CHANGE IT while deployment 
curl_setopt($ch, CURLOPT_TIMEOUT, 40); // Set timeout (in seconds)

// Add User-Agent header for better identification
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: MyNewsApp/1.0",
]);

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    error_log("cURL Error: " . curl_error($ch)); // Log the error instead of displaying it
    curl_close($ch);
    die("Failed to fetch news due to a server issue.");
}
curl_close($ch);

// Decode the JSON response
$newsData = json_decode($response, true);

// Check the API response status
if ($newsData['status'] === 'ok') {
    $articles = $newsData['articles'];

    // Prepare SQL query to insert news
    $stmt = $conn->prepare(
        "INSERT INTO news (title, content, url, url_to_image, source_name, published_at)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE title=VALUES(title), content=VALUES(content)"
    );
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Iterate through the articles
    foreach ($articles as $article) {
        // Sanitize article fields
        $title = $article['title'] ?? 'No title';
        $content = $article['description'] ?? 'No description available.';
        $url = $article['url'] ?? '#';
        $urlToImage = $article['urlToImage'] ?? null;
        $sourceName = $article['source']['name'] ?? 'Unknown';
        $publishedAt = $article['publishedAt'] ?? date('Y-m-d H:i:s');


        // Validate and sanitize URLs
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = '#'; // Replace invalid URLs
        }

        if ($urlToImage && !filter_var($urlToImage, FILTER_VALIDATE_URL)) {
            $urlToImage = null; // Ignore invalid image URLs
        }

        // Ensure URLs do not resolve to internal IPs (to mitigate SSRF)
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host'])) {
            $ip = gethostbyname($parsedUrl['host']);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                error_log("Potentially harmful URL resolved to private IP: $url");
                $url = '#';
            }
        }

        if ($urlToImage) {
            $parsedImageUrl = parse_url($urlToImage);
            if (isset($parsedImageUrl['host'])) {
                $ip = gethostbyname($parsedImageUrl['host']);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                    error_log("Potentially harmful Image URL resolved to private IP: $urlToImage");
                    $urlToImage = null;
                }
            }
        }

        // Fix publishedAt format to match MySQL DATETIME
        if ($publishedAt) {
            $publishedAt = str_replace("Z", "", $publishedAt); // Remove 'Z'
            $publishedAt = str_replace("T", " ", $publishedAt); // Replace 'T' with a space
        }

        // Skip articles missing essential data
        if (empty($urlToImage) || stripos($title, 'removed') !== false) {
            continue;
        }

        // Before binding parameters
        error_log("Title: $title, Content: $content, URL: $url, URLToImage: $urlToImage, SourceName: $sourceName, PublishedAt: $publishedAt");

        // Bind parameters and execute the prepared statement
        $stmt->bind_param("ssssss", $title, $content, $url, $urlToImage, $sourceName, $publishedAt);
        if (!$stmt->execute()) {
            error_log("Error inserting data: " . $stmt->error); // Log errors instead of displaying
        }
    }
} else {
    // Log API errors instead of displaying them
    error_log("API Error: " . ($newsData['message'] ?? 'Unknown error'));
    die("Failed to fetch news from the API.");
}
