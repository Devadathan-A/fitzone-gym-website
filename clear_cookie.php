<?php
/**
 * FitZone Gym - Cookie Removal
 * 
 * Demonstrates Module 4 concepts (Page 3):
 * - Deleting a Cookie in PHP
 * - Setting expiration time in the past: time() - 3600
 */

// Module 4, Page 3: Deleting cookies by expiring them in the past
setcookie("fitzone_visitor", "", time() - 3600, "/");
setcookie("fitzone_last_plan", "", time() - 3600, "/");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['flash_message'] = "Cookie data deleted successfully. The browser will no longer store your previous visit.";
$_SESSION['flash_type'] = "info";

header("Location: index.php");
exit();
?>

