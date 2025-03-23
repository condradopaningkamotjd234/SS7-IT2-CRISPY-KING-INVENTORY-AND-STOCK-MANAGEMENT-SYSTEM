<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mariadb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle batch sales submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sales'])) {
    $sales = $_POST['sales']; // Expecting an array of sales

    foreach ($sales as $sale) {
        $product_name = htmlspecialchars($sale['product_name']);
        $quantity_sold = intval($sale['quantity_sold']);
        $total_price = floatval($sale['total_price']);
        $sale_date = date('Y-m-d H:i:s'); // Capture current date and time

        // Check available stock
        $product_check = $conn->prepare("SELECT stock_quantity FROM Products WHERE name = ?");
        $product_check->bind_param("s", $product_name);
        $product_check->execute();
        $product_check->bind_result($stock_quantity);
        $product_check->fetch();
        $product_check->close();

        if ($stock_quantity >= $quantity_sold) {
            // Deduct stock
            $update_stock = $conn->prepare("UPDATE Products SET stock_quantity = stock_quantity - ? WHERE name = ?");
            $update_stock->bind_param("is", $quantity_sold, $product_name);
            $update_stock->execute();
            $update_stock->close();

            // Insert sale record
            $insert_sale = $conn->prepare("INSERT INTO Sales (product_name, quantity_sold, total_price, sale_date) VALUES (?, ?, ?, ?)");
            $insert_sale->bind_param("sids", $product_name, $quantity_sold, $total_price, $sale_date);
            $insert_sale->execute();
            $insert_sale->close();
        } else {
            $error = "Not enough stock for product: $product_name";
        }
    }
    $success = "Batch sales recorded successfully!";
}

// Handle deleting a sale
if (isset($_GET['delete'])) {
    $sale_id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT product_name, quantity_sold FROM Sales WHERE id = ?");
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $stmt->bind_result($product_name, $quantity_sold);
    $stmt->fetch();
    $stmt->close();

    if ($product_name) {
        // Restore stock before deleting sale record
        $restore_stock = $conn->prepare("UPDATE Products SET stock_quantity = stock_quantity + ? WHERE name = ?");
        $restore_stock->bind_param("is", $quantity_sold, $product_name);
        $restore_stock->execute();
        $restore_stock->close();

        // Delete sale record
        $delete_sale = $conn->prepare("DELETE FROM Sales WHERE id = ?");
        $delete_sale->bind_param("i", $sale_id);
        $delete_sale->execute();
        $delete_sale->close();
    }

    header("Location: sales_history.php");
    exit();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background: #ffffff;
            color: black;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Sales History</h2>

    <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <!-- Display messages -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <h4>Record New Batch Sales</h4>
    <form method="POST" action="">
        <div id="batchSales">
            <div class="sale-entry">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="sales[0][product_name]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity Sold</label>
                        <input type="number" name="sales[0][quantity_sold]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Price</label>
                        <input type="number" step="0.01" name="sales[0][total_price]" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-entry">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-success mt-2" id="addSale">Add Another Sale</button>
        <button type="submit" class="btn btn-primary mt-2">Record Sales</button>
    </form>

    <h4 class="mt-5">Sales Records</h4>
    <table class="table table-bordered bg-white text-dark">
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
                    <a href="sales_history.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this sale?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('addSale').addEventListener('click', function () {
    let batchSales = document.getElementById('batchSales');
    let index = batchSales.children.length;
    let newEntry = batchSales.children[0].cloneNode(true);

    newEntry.querySelectorAll("input").forEach(input => {
        input.name = input.name.replace(/\d+/, index);
        input.value = "";
    });

    newEntry.querySelector(".remove-entry").addEventListener('click', function () {
        this.parentNode.parentNode.parentNode.remove();
    });

    batchSales.appendChild(newEntry);
});

document.querySelectorAll('.remove-entry').forEach(button => {
    button.addEventListener('click', function () {
        this.parentNode.parentNode.parentNode.remove();
    });
});
</script>

</body>
</html>
