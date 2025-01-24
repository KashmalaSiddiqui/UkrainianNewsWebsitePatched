<?php

// include('../includes/sessionmanagement.php');
//--------------------------FOR DOCKER--------------------------------
include('./includes/sessionmanagement.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body class="registration-page">
    <!-- Back Arrow -->
    <?php if (isset($_SERVER['HTTP_REFERER'])): ?>
        <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8'); ?>" class="back-arrow" style="text-decoration: none;">&larr; Back</a>
    <?php endif; ?>
    <div class=" container">
        <h2>
            <a href="main.php" style="text-decoration: none; color: inherit;"> User Registration</a>
        </h2>
        <form action="./scripts/registerProcess.php" method="POST">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-register">Register</button>
        </form>
        <!-- Add the message below the button -->
        <p style="text-align: center; margin-top: 1rem;">
            Already have an account?
            <a href="login.php" style="text-decoration: none; color: #004080;">Login here</a>
        </p>
    </div>
</body>

</html>