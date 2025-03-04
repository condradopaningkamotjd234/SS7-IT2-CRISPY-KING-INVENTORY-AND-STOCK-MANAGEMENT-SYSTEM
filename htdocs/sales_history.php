<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mariadb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deleting a sale
if (isset($_GET['delete'])) {
    $sale_id = $_GET['delete'];
    $sale = $conn->query("SELECT * FROM Sales WHERE product_name = '$sale_id'")->fetch_assoc();
    
    if ($sale) {
        // Restore stock before deleting the sale record
        $conn->query("UPDATE Products SET stock_quantity = stock_quantity + {$sale['quantity_sold']} WHERE name = '{$sale['product_name']}'");
        $conn->query("DELETE FROM Sales WHERE product_name = '$sale_id'");
    }

    header("Location: sales_history.php");
}

// Fetch all sales records
$sales = $conn->query("SELECT * FROM Sales");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History - Crispy King</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">Sales History</h2>

        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <h4>Sales Records</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sales->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity_sold']; ?></td>
                    <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo $row['sale_date']; ?></td>
                    <td>
                        <a href="sales_history.php?delete=<?php echo $row['product_name']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this sale?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

</body>
</html>
