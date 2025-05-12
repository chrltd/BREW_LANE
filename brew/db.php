<?php
$servername = "localhost";
$username = "root"; // adjust if needed
$password = ""; // adjust if needed
$database = "brewlane";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
