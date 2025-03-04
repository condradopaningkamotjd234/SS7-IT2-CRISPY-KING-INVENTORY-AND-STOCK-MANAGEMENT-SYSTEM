<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();
// Handle product deletion
if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "DELETE FROM Products WHERE name = '$name'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
