<?php
    include "db.php";
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    ini_set('display_errors', 1);
    error_reporting(E_ALL); 

    // Get total sales
    $total_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales")->fetch_assoc()['count'];

    // Get today's sales
    $today_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales WHERE DATE(sale_date) = CURDATE()")->fetch_assoc()['count'];

    // Get weekly sales
    $weekly_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales WHERE YEARWEEK(sale_date, 1) = YEARWEEK(CURDATE(), 1)")->fetch_assoc()['count'];

    // Get monthly sales
    $monthly_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = MONTH(CURDATE())")->fetch_assoc()['count'];

    // Get yearly sales
    $yearly_sales = $conn->query("SELECT SUM(quantity_sold) AS count FROM Sales WHERE YEAR(sale_date) = YEAR(CURDATE())")->fetch_assoc()['count'];
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
            border-radius: 0; /* Change border-radius to 0 for box shape */
            padding: 20px;
            margin-bottom: 20px;
            text-align: left; /* Align text to the left */
        }
        .card h3 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 1.1em;
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
        <a class="navbar-brand" href="dashboard.php">Crispy King Dashboard</a>
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
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard</h2>

    <div class="row mb-4"> <!-- Removed text-center class -->
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <h3><?php echo number_format($total_sales ?: 0); ?></h3>
                <p>Total Sales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <h3><?php echo number_format($today_sales ?: 0); ?></h3>
                <p>Today's Sales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <h3><?php echo number_format($weekly_sales ?: 0); ?></h3>
                <p>Weekly Sales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <h3><?php echo number_format($monthly_sales ?: 0); ?></h3>
                <p>Monthly Sales</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <h3><?php echo number_format($yearly_sales ?: 0); ?></h3>
                <p>Yearly Sales</p>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 fixed-bottom">
    <p>&copy; <?php echo date('Y'); ?> Crispy King. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

