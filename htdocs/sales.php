<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle new sale transaction
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_sale'])) {
    $product_name = $_POST['product_name'];
    $quantity_sold = $_POST['quantity_sold'];

    // Get product price and check stock
    $product = $conn->query("SELECT price, stock_quantity FROM Products WHERE name = '$product_name'")->fetch_assoc();
    if ($product && $product['stock_quantity'] >= $quantity_sold) {
        $total_price = $product['price'] * $quantity_sold;

        // Insert into Sales table
        $conn->query("INSERT INTO Sales (product_name, quantity_sold, total_price) 
                      VALUES ('$product_name', '$quantity_sold', '$total_price')");

        // Update inventory (reduce stock)
        $conn->query("UPDATE Products SET stock_quantity = stock_quantity - $quantity_sold WHERE name = '$product_name'");
    } else {
        echo "<script>alert('Not enough stock available!');</script>";
    }

    header("Location: sales.php");
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

    header("Location: sales.php");
}

// Fetch all sales records
$sales = $conn->query("SELECT * FROM Sales");

// Fetch available products for sales entry
$products = $conn->query("SELECT * FROM Products WHERE stock_quantity > 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Crispy King</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Crispy King</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
                <li class="nav-item"><a class="nav-link" href="sales.php">Sales</a></li>
                <li class="nav-item"><a class="nav-link" href="suppliers.php">Suppliers</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-5">
        <h2 class="text-center">Sales Management</h2>


        <h4>New Sale</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <select name="product_name" class="form-control" required>
                        <option value="">Select Product</option>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?> - ₱<?php echo number_format($row['price'], 2); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantity_sold" class="form-control" placeholder="Quantity Sold" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_sale" class="btn btn-success">Record Sale</button>
                </div>
            </div>
        </form>

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
                        <a href="sales.php?delete=<?php echo $row['product_name']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this sale?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    <footer class="bg-dark text-white text-center py-3 fixed-bottom">
        <p>&copy; <?php echo date('Y'); ?> Crispy King. All rights reserved.</p>
    </footer>

</body>
</html>
