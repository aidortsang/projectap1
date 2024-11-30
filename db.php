<?php
// Increase memory limit if necessary
ini_set('memory_limit', '512M'); // Set the memory limit to 512MB (adjust as needed)

$host = 'localhost';
$username = 'root'; // Default for XAMPP
$password = ''; // Default for XAMPP
$dbname = 'project_aptstage'; // Your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
