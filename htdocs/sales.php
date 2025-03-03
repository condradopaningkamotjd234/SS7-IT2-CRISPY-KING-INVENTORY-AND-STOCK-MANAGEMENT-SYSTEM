<?php
include 'db_connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle Add Sale
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_sale'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $total_price = floatval($_POST['total_price']);
    $sale_date = date('Y-m-d H:i:s');

    if ($product_id > 0 && $quantity > 0 && $total_price > 0) {
        // Insert Sale
        $stmt = $mysqli->prepare("INSERT INTO sales (product_id, quantity, total_price, sale_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $product_id, $quantity, $total_price, $sale_date);

        if ($stmt->execute()) {
            // Update Stock Level
            $stmt = $mysqli->prepare("UPDATE product SET StockLevel = StockLevel - ? WHERE ProductID = ?");
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();

            header("Location: sales.php?success=Sale added successfully");
            exit();
        } else {
            $error = "Error adding sale: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $error = "Invalid input. Please fill all fields correctly.";
    }
}

// Handle Delete Sale
if (isset($_GET['delete'])) {
    $sale_id = intval($_GET['delete']);
    if ($sale_id > 0) {
        $stmt = $mysqli->prepare("DELETE FROM sales WHERE sale_id = ?");
        $stmt->bind_param("i", $sale_id);
        if ($stmt->execute()) {
            header("Location: sales.php?success=Sale deleted successfully");
            exit();
        } else {
            $error = "Error deleting sale: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $error = "Invalid sale ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link rel="stylesheet" href="dashboard.css" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
    <aside class="sidebar">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="stock.php">Stock</a></li>
                    <!-- Added sample order_id parameter -->
                    <li><a href="orderdetails.php">Order Details</a></li>
                    <li><a href="order.php">order</a></li>
                    <li><a href="sales.php">Sales</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </aside>

        <main class="dashboard-content">
            <header class="dashboard-header">
                <h1>Sales Management</h1>
            </header>

            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (isset($_GET['success'])) echo "<p class='success-msg'>" . htmlspecialchars($_GET['success']) . "</p>"; ?>

            <section class="sales-form">
                <h2>Add New Sale</h2>
                <form method="POST" action="sales.php">
                    <input type="number" name="product_id" placeholder="Product ID" required>
                    <input type="number" name="quantity" placeholder="Quantity" required>
                    <input type="text" name="total_price" placeholder="Total Price" required>
                    <button type="submit" name="add_sale" class="btn">Add Sale</button>
                </form>
            </section>

            <section class="sales-list">
                <h2>Sales Records</h2>
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Sale Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM sales";
                        $result = $mysqli->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['sale_id']) . "</td>
                                        <td>" . htmlspecialchars($row['product_id']) . "</td>
                                        <td>" . htmlspecialchars($row['quantity']) . "</td>
                                        <td>$" . number_format($row['total_price'], 2) . "</td>
                                        <td>" . htmlspecialchars($row['sale_date']) . "</td>
                                        <td><a href='sales.php?delete=" . $row['sale_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No sales records found.</td></tr>";
                        }
                        $mysqli->close();
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
