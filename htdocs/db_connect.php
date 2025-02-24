<?php
$servername = "localhost";  // Use 'localhost' if connecting locally
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
}
echo "Connected successfully";
?>
