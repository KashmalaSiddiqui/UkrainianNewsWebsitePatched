<?php
include('adminHeader.php');
include('./includes/config/config.php'); // Include your database connection


/*
Solved Vulnerability: CWE-434: restricted the file types that can be uploaded to the server.
*/
$uploadedFilePath = ""; //stores the uploaded file path

if (isset($_POST['upload'])) {
    $uploadDir = "uploads/"; // Save uploaded files in the uploads directory
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    if (isset($_FILES["file"]["tmp_name"])) {
        // Check file size
        if ($_FILES['file']['size'] > 2 * 1024 * 1024) {
            $uploadError = "File size exceeds 2MB. Please upload a smaller file.";
        } else {
            // Check the MIME type of the file
            $fileMimeType = mime_content_type($_FILES["file"]["tmp_name"]);
            $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if (in_array($fileMimeType, $allowedMimeTypes) && in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('upload_', true) . '.' . $fileExtension;
                $uploadedFile = $uploadDir . $newFileName;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadedFile)) {
                    $uploadedFilePath = $uploadedFile; // Store uploaded file path
                    $_SESSION['uploaded_image'] = $uploadedFile; // Store in session
                } else {
                    $uploadError = "Error uploading file!";
                }
            } else {
                $uploadError = "Unsupported file type or extension. Please upload JPEG or PNG files only.";
            }
        }
    } else {
        $uploadError = "No file selected.";
    }
}


$userCount = 0;
$commentCount = 0;

// Fetch the number of users
$result = $conn->query("SELECT COUNT(*) AS totalUsers FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    $userCount = $row['totalUsers'];
}

// Fetch the number of comments
$result = $conn->query("SELECT COUNT(*) AS totalComments FROM comments");
if ($result) {
    $row = $result->fetch_assoc();
    $commentCount = $row['totalComments'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./styles/main.css">
    <style>
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-box {
            flex: 1;
            text-align: center;
            padding: 10px;
            margin: 0 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .stat-box h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .stat-box p {
            font-size: 2rem;
            font-weight: bold;
            color: #004080;
        }
    </style>
</head>

<body class="admin-dashboard">

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Users</h3>
            <p><?php echo $userCount; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Comments</h3>
            <p><?php echo $commentCount; ?></p>
        </div>

    </div>
    <!-- Container Section -->
    <div class="container">
        <h2>Welcome to the Admin Panel</h2>
        <p>This is your admin dashboard where you can manage users, comments, and upload files.</p>

        <!-- Vulnerable Upload Section -->
        <div class="upload-section">
            <h2>Upload File</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="file">Select a file to upload:</label>
                <input type="file" name="file" id="file" required>
                <button type="submit" name="upload">Upload</button>
            </form>

            <?php if (!empty($uploadedFilePath)): ?>
                <p style='color: green;'>File uploaded successfully:
                    <a href="<?php echo htmlspecialchars($uploadedFilePath); ?>" target="_blank">
                        <?php echo htmlspecialchars(basename($uploadedFilePath)); ?>
                    </a>
                </p>
            <?php elseif (isset($uploadError)): ?>
                <p style='color: red;'><?php echo $uploadError; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>


</html>