<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Set default date range (last 30 days)
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

// Fetch Sales Report
$sales_query = "SELECT * FROM Sales WHERE sale_date BETWEEN '$start_date' AND '$end_date'";
$sales_result = $conn->query($sales_query);
$total_sales = $conn->query("SELECT SUM(total_price) AS total FROM Sales WHERE sale_date BETWEEN '$start_date' AND '$end_date'")->fetch_assoc()['total'];

// Fetch Inventory Report
$inventory_result = $conn->query("SELECT * FROM Products");

// Fetch Orders Report
$orders_query = "SELECT * FROM Orders WHERE order_date BETWEEN '$start_date' AND '$end_date'";
$orders_result = $conn->query($orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Crispy King</title>
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
        <h2 class="text-center">Reports</h2>

        <h4>Generate Reports</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Generate Report</button>
                </div>
            </div>
        </form>

        <!-- Sales Report -->
        <h4>Sales Report</h4>
        <p><strong>Total Sales: </strong>₱<?php echo number_format($total_sales ?: 0, 2); ?></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sales_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity_sold']; ?></td>
                    <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo $row['sale_date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Inventory Report -->
        <h4>Inventory Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Stock Quantity</th>
                    <th>Expiry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $inventory_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td><?php echo $row['expiry_date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Orders Report -->
        <h4>Orders Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Product Name</th>
                    <th>Quantity Ordered</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['supplier_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity_ordered']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
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
