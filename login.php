<?php
/**
 * FitZone Gym - Admin Login
 * 
 * Demonstrates Module 4 concepts:
 * - Starting a session with session_start() (must appear before any HTML tags)
 * - Setting session variables: $_SESSION["admin_user"] and $_SESSION["admin_logged_in"]
 * - Session persistence across pages
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";

$login_error = null;

// If already logged in, redirect to member directory
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: members_list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_submit'])) {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    // Simple demo credentials for web assignment demonstration
    if ($user === "admin" && $pass === "admin123") {
        // Module 4, Page 4: Setting session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        $_SESSION['flash_message'] = "Logged in successfully as Administrator!";
        $_SESSION['flash_type'] = "success";

        header("Location: members_list.php");
        exit();
    } else {
        $login_error = "Invalid username or password. (Demo credentials: admin / admin123)";
    }
}

$page_title = "Admin Login";
require_once "includes/header.php";
?>

<main class="section form-section" style="max-width: 480px; margin: 50px auto;">
    <div class="section-title">
        <p class="tag">STAFF ACCESS</p>
        <h2>FitZone Admin Login</h2>
        <p>Demonstrates Module 4: PHP Sessions (<code>$_SESSION</code>)</p>
    </div>

    <?php if ($login_error): ?>
        <div class="form-error-summary">
            <?php echo htmlspecialchars($login_error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="e.g. admin" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="e.g. admin123" required>

        <small style="display:block; margin: 10px 0; color: #64748b;">
            💡 Default demo credentials: <strong>admin</strong> / <strong>admin123</strong>
        </small>

        <div class="form-buttons">
            <input class="button" type="submit" name="login_submit" value="Log In (Start Session)">
            <a href="index.php" class="reset-button" style="text-decoration:none; text-align:center;">Back to Home</a>
        </div>
    </form>
</main>

<?php require_once "includes/footer.php"; ?>

