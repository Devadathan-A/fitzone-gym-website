<?php
/**
 * FitZone Gym - Admin Logout
 * 
 * Demonstrates Module 4 concepts (Page 5):
 * - Destroy a PHP Session
 * - session_start()
 * - session_unset(): remove all global session variables
 * - session_destroy(): destroy the session completely
 */

session_start();

// Module 4, Page 5: Remove all session variables
session_unset();

// Module 4, Page 5: Destroy the session
session_destroy();

// Restart a temporary session to pass a logout success message
session_start();
$_SESSION['flash_message'] = "You have been logged out successfully. Session destroyed.";
$_SESSION['flash_type'] = "info";

header("Location: index.php");
exit();
?>

