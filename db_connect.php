<?php
/**
 * FitZone Gym - Database Connection Handler
 * 
 * Demonstrates Module 4 concepts:
 * - Connecting to MySQL with PHP (mysqli_connect)
 * - Checking connection errors (mysqli_connect_errno, mysqli_connect_error)
 * - Closing connections explicitly (mysqli_close)
 */

// Database configuration settings
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "fitzone_db";

// Module 4, Page 5: Making a Connection
// $mysqli = mysqli_connect("hostname", "username", "password", "database");
$conn = @mysqli_connect($servername, $username, $password, $dbname);

// Module 4, Page 6: Error handling via mysqli_connect_errno() & mysqli_connect_error()
if (mysqli_connect_errno()) {
    $db_connection_error = mysqli_connect_error();
    $db_connected = false;
} else {
    $db_connection_error = null;
    $db_connected = true;
}

/**
 * Helper function to safely close the MySQL connection
 * Demonstrates Module 4, Page 7: mysqli_close()
 */
function closeDbConnection($connection) {
    if ($connection) {
        mysqli_close($connection);
    }
}
?>

