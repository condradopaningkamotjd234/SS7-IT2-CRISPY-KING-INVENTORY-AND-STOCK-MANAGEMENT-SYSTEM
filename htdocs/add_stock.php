<?php
include "db_connect.php"; 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate fields
    if (!isset($_POST['productID'], $_POST['quantity'], $_POST['status'], $_SESSION['userID'])) {
        die("Error: Missing required fields.");
    }

    $stockID = uniqid();
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];
    $dateAdded = date("Y-m-d H:i:s");
    $lastUpdated = date("Y-m-d H:i:s");
    $userID = $_SESSION['userID']; // Use session for user ID

    $sql = "INSERT INTO Stock (StockID, ProductID, Quantity, Status, DateAdded, LastUpdated, UserID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $stockID, $productID, $quantity, $status, $dateAdded, $lastUpdated, $userID);

    if ($stmt->execute()) {
        echo "New stock added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFAE5;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .button {
            background-color: #FFA500;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #FF8C00;
        }
    </style>
</head>
<body>
    <div class="container">
    <form action="add_stock.php" method="POST">
    <label for="productID">Product ID:</label>
    <input type="text" name="productID" required><br><br>
    
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required><br><br>
    
    <label for="status">Status:</label>
    <input type="text" name="status" required><br><br>
    
    <!-- Hidden userID input (Only if session is set) -->
    <?php if (isset($_SESSION['userID'])): ?>
        <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>">
    <?php endif; ?>

    <button type="submit" class="button">Add Stock</button>
</form>


        <br>
        <a href="stock.php" class="button">Back to Home</a>
    </div>
</body>
</html>
