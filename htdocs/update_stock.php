<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $new_stock = $_POST['new_stock'];

    $sql = "UPDATE Products SET stock_quantity = stock_quantity + $new_stock WHERE name = '$name'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Stock updated successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
