
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Crispy King</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="welcome.php">Crispy King</a>
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

    <div class="container mt-5 text-center">
        <h1>Welcome to Crispy King, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="lead">Your go-to system for managing inventory, sales, and orders.</p>

        <div class="row">
            <div class="col-md-4">
                <a href="inventory.php" class="btn btn-primary btn-lg">Go to Inventory</a>
            </div>
            <div class="col-md-4">
                <a href="sales.php" class="btn btn-success btn-lg">Go to Sales</a>
            </div>
            <div class="col-md-4">
                <a href="reports.php" class="btn btn-info btn-lg">View Reports</a>
            </div>
        </div>

        <footer class="bg-dark text-white text-center py-3 fixed-bottom">
    <p>&copy; <?php echo date('Y'); ?> Crispy King. All rights reserved.</p>
</footer>
    </div>

</body>
</html>
