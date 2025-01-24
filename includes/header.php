<?php
if (session_status() === PHP_SESSION_NONE) {
    // include('./includes/sessionmanagement.php'); ----------FOR DOCKER--------------------
    include('./includes/sessionmanagement.php');
}
?>
<header>
    <div class="navbar">
        <h1 class="logo">
            <a href="main.php" style="text-decoration: none; color: inherit;">Kyiv News</a>
        </h1>

        <div class="search-container">
            <form action="main.php" method="GET">
                <input type="text" name="query" placeholder="Search news..." class="search-input" value="<?php echo isset($searchQuery) ? $searchQuery : ''; ?>">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
        <div class="buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
                <a href="../scripts/logoutProcess.php" class="btn btn-login">Logout</a>
                <!-- <a href="./scripts/logoutProcess.php" class="btn btn-login">Logout</a>-------FOR DOCKER -->
            <?php else: ?>
                <a href="register.php" class="btn btn-register">Register</a>
                <a href="login.php" class="btn btn-login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>