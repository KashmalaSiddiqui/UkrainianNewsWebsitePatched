<?php
if (session_status() === PHP_SESSION_NONE) {
    include('adminSession.php');
}
$uploadedFilePath = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : '';

// Display previously uploaded file
if (isset($_SESSION['uploaded_image'])) {
    $uploadedFilePath = $_SESSION['uploaded_image'];
}
?>
<header class="admin-header">
    <div class="header-container">
        <h1>
            <a href="adminDashboard.php" style="text-decoration: none; color: inherit;"> Admin Dashboard</a>
        </h1>
        <!-- Navigation -->
        <nav>
            <a href="manageUsers.php">Manage Users</a>
            <a href="manageComments.php">Manage Comments</a>
            <a href="manageAd.php">Manage Advertisements</a>
        </nav>
        <!-- Uploaded Image Display (Top-Right Corner) -->
        <?php if (!empty($uploadedFilePath)): ?>
            <?php
            // Restrict access to only certain file types
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileMimeType = mime_content_type($uploadedFilePath); // Validate MIME type

            if (in_array($fileMimeType, $allowedMimeTypes)): ?>
                <img src="<?php echo htmlspecialchars($uploadedFilePath, ENT_QUOTES, 'UTF-8'); ?>" alt="Uploaded Image" class="uploaded-image">
            <?php else: ?>
                <p class="non-image-warning">Uploaded file is not a valid image.</p>
            <?php endif; ?>
        <?php endif; ?>
        <button class="logout-button" onclick="location.href='adminlogout.php'">Logout</button>

    </div>
</header>