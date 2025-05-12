<?php
$servername = "localhost"; // Change if your DB is hosted elsewhere
$username = "root"; // Change if needed
$password = ""; // Enter your actual password if any
$database = "brewlane"; // Ensure this matches your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

