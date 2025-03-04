<?php
include "db.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Handle ordering from a supplier
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['place_order'])) {
    $supplier_id = $_POST['supplier_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // Insert into Orders table
    $sql_order = "INSERT INTO Orders (supplier_id, product_name, quantity, order_date) 
                  VALUES ('$supplier_id', '$product_name', '$quantity', NOW())";
    if ($conn->query($sql_order)) {
        
        // Check if product exists in inventory
        $check_inventory = $conn->query("SELECT * FROM Inventory WHERE product_name = '$product_name'");
        
        if ($check_inventory->num_rows > 0) {
            // Update existing product quantity
            $conn->query("UPDATE Inventory SET quantity = quantity + $quantity WHERE product_name = '$product_name'");
        } else {
            // Insert new product into inventory
            $conn->query("INSERT INTO Inventory (product_name, quantity) VALUES ('$product_name', '$quantity')");
        }

        echo "<script>alert('Order placed successfully! Inventory updated.'); window.location.href='suppliers.php';</script>";
    } else {
        echo "<script>alert('Error placing order. Please try again.');</script>";
    }
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM Suppliers");
?>
