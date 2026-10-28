<?php
/**
 * FitZone Gym - Delete Member Record
 * 
 * Demonstrates Module 4 concepts:
 * - Page 13: Delete data from MySQL
 * - Syntax: DELETE FROM members WHERE id = ...
 * - mysqli_query() execution
 * - mysqli_error() error reporting
 * - mysqli_close() closing connection
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "db_connect.php";

// Module 4: Retrieve ID parameter from GET request
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0 && $db_connected && $conn) {
    // Optional: Fetch member name first for friendly notification
    $name_res = mysqli_query($conn, "SELECT `fullname` FROM `members` WHERE `id` = $id");
    $deleted_name = "Record #$id";
    if ($name_res && $row = mysqli_fetch_assoc($name_res)) {
        $deleted_name = $row['fullname'];
    }

    // Module 4, Page 13: SQL to delete a record
    $sql = "DELETE FROM `members` WHERE `id` = $id";

    if (mysqli_query($conn, $sql)) {
        // Module 4, Page 4: Store success feedback in Session
        $_SESSION['flash_message'] = "Member '" . htmlspecialchars($deleted_name) . "' was deleted successfully from MySQL.";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Error deleting record: " . mysqli_error($conn);
        $_SESSION['flash_type'] = "error";
    }

    // Module 4, Page 13: mysqli_close($conn)
    closeDbConnection($conn);
} else {
    $_SESSION['flash_message'] = "Invalid request or database not connected.";
    $_SESSION['flash_type'] = "error";
}

header("Location: members_list.php");
exit();
?>

