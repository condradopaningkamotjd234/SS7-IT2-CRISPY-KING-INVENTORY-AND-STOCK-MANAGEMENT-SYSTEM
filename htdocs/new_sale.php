<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle new sale transaction
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $product_name = $_POST['product_name'];
    $quantity_sold = $_POST['quantity_sold'];

    // Get product details
    $product = $conn->query("SELECT price, stock_quantity FROM Products WHERE name = '$product_name'")->fetch_assoc();
    
    if ($product && $product['stock_quantity'] >= $quantity_sold) {
        $total_price = $product['price'] * $quantity_sold;

        // Insert into Sales table
        $conn->query("INSERT INTO Sales (product_name, quantity_sold, total_price) 
                      VALUES ('$product_name', '$quantity_sold', '$total_price')");

        // Update inventory (reduce stock)
        $conn->query("UPDATE Products SET stock_quantity = stock_quantity - $quantity_sold WHERE name = '$product_name'");

        echo "<script>alert('Sale recorded successfully!'); window.location.href='sales_history.php';</script>";
    } else {
        echo "<script>alert('Error: Not enough stock available!'); window.location.href='new_sale.php';</script>";
    }
}

$conn->close();
?>
