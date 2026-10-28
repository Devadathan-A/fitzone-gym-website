<?php
/**
 * FitZone Gym - Modular Header Template
 * 
 * Demonstrates:
 * - Module 3: File inclusion (include/require)
 * - Module 4: session_start() placed at the very top before any HTML tags
 * - Module 4: Reading cookies with $_COOKIE and checking with isset()
 * - Module 4: Session variables ($_SESSION)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Module 4, Page 2: Retrieving cookies with $_COOKIE and isset()
$cookie_user = isset($_COOKIE['fitzone_visitor']) ? $_COOKIE['fitzone_visitor'] : null;
$cookie_plan = isset($_COOKIE['fitzone_last_plan']) ? $_COOKIE['fitzone_last_plan'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="FitZone Gym and Fitness dynamic website with PHP & MySQL">
    <meta name="keywords" content="gym, fitness, workout, training, nutrition, php, mysql">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " | " . GYM_NAME : GYM_NAME . " | Gym & Fitness"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Module 4, Page 2: Cookie Banner Demonstration -->
    <?php if ($cookie_user): ?>
        <div class="cookie-banner">
            <span>👋 Welcome back, <strong><?php echo htmlspecialchars($cookie_user); ?></strong>! 
            <?php if ($cookie_plan): ?>
                Your selected plan is <em><?php echo htmlspecialchars($cookie_plan); ?></em>.
            <?php endif; ?>
            </span>
            <a href="clear_cookie.php" class="cookie-clear-btn" title="Delete cookie demonstration">Clear Cookie</a>
        </div>
    <?php endif; ?>

    <!-- Header and Navigation -->
    <header id="home">
        <nav class="navbar">
            <h1 class="logo"><a href="index.php" style="color:inherit; text-decoration:none;">FIT<span>ZONE</span></a></h1>

            <div class="nav-links">
                <a href="index.php#home">Home</a>
                <a href="index.php#workouts">Workouts</a>
                <a href="index.php#plans">Plans</a>
                <a href="index.php#nutrition">Nutrition</a>
                <a href="index.php#join">Join</a>
                <a href="members_list.php" class="nav-highlight">Members Directory</a>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="logout.php" style="color: #ff6b6b;">Logout (<?php echo htmlspecialchars($_SESSION['admin_user']); ?>)</a>
                <?php else: ?>
                    <a href="login.php">Admin Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Module 4, Page 4: Session Flash Notification Bar -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-alert flash-<?php echo htmlspecialchars($_SESSION['flash_type'] ?? 'info'); ?>">
            <p><?php echo htmlspecialchars($_SESSION['flash_message']); ?></p>
        </div>
        <?php 
            // Unset after displaying so it only appears once
            unset($_SESSION['flash_message']); 
            unset($_SESSION['flash_type']); 
        ?>
    <?php endif; ?>

