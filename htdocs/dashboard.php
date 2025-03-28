<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    // Get total counts
    $total_products = $conn->query("SELECT COUNT(*) AS count FROM Products")->fetch_assoc()['count'];
    $total_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales")->fetch_assoc()['count'];

    // Get low stock products
    $low_stock = $conn->query("SELECT * FROM Products WHERE stock_quantity <= 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Crispy King</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h3 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 1.1em;
        }
        .btn {
            width: auto;
            padding: 10px 15px;
            font-size: 1rem;
        }
        .navbar-brand {
            font-size: 1.5rem;
        }
        .fixed-bottom p {
            margin: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Crispy King Inventory</a>
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
    <h2 class="text-center mb-4">Crispy King Dashboard</h2>

    <div class="row text-center mb-4">
        <!-- Total Products Card -->
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <h3><?php echo $total_products; ?></h3>
                <p>Total Products</p>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <h3><?php echo number_format($total_sales ?: 0); ?></h3>
                <p>Total Sales</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <a href="inventory.php" class="btn btn-primary">Manage Inventory</a>
        <a href="sales.php" class="btn btn-secondary">Sales</a>
        <a href="suppliers.php" class="btn btn-success">Suppliers</a>
    </div>

    <h4>Low Stock Products</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Stock Quantity</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $low_stock->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="text-danger"><?php echo htmlspecialchars($row['stock_quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer class="bg-dark text-white text-center py-3 fixed-bottom">
    <p>&copy; <?php echo date('Y'); ?> Crispy King. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>