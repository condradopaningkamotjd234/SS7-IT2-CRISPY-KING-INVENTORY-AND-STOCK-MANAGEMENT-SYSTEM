<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "INSERT INTO Products (name, category, price, stock_quantity, expiry_date) 
            VALUES ('$name', '$category', '$price', '$stock_quantity', '$expiry_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
