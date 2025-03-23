<?php
include "db.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $new_stock = (int) $_POST['new_stock']; // Ensure integer input

    if ($new_stock > 0) {
        // I-update ang stock sa `Products` table
        $update_sql = "UPDATE Products SET stock_quantity = stock_quantity + $new_stock WHERE name = '$name'";

        // I-record ang stock update sa `Inventory` table
        $insert_sql = "INSERT INTO Inventory (product_name, stock_added, stock_removed, date_updated) 
                       VALUES ('$name', $new_stock, 0, NOW())";

        if ($conn->query($update_sql) && $conn->query($insert_sql)) {
            echo "<script>alert('Stock updated successfully!'); window.location.href='inventory.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please enter a valid stock quantity!'); window.history.back();</script>";
    }
}

$conn->close();
?>
