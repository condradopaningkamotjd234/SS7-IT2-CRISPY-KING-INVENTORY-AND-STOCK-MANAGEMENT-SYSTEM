<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();


// Fetch products
$products = $conn->query("SELECT * FROM Products");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crispy King - Inventory</title>
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
        <h2 class="text-center">Crispy King Inventory System</h2>

        <div class="d-flex justify-content-between mb-4">
            <a href="sales.php" class="btn btn-primary">Sales</a>
            <a href="suppliers.php" class="btn btn-success">Suppliers</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td><?php echo $row['expiry_date']; ?></td>
                    <td>
                        <?php if ($row['stock_quantity'] <= 5): ?>
                            <span class="badge bg-danger">Low Stock</span>
                        <?php else: ?>
                            <span class="badge bg-success">Available</span>
                        <?php endif; ?>
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
