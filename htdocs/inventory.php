<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();

    function addProduct($conn, $name, $category, $price, $stock_quantity, $expiry_date) {
        $stmt = $conn->prepare("INSERT INTO Products (name, category, price, stock_quantity, expiry_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $category, $price, $stock_quantity, $expiry_date);
        $stmt->execute();
        $stmt->close();
    }

    function deleteProduct($conn, $name) {
        $stmt = $conn->prepare("DELETE FROM Products WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    // Handle adding stock
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $expiry_date = $_POST['expiry_date'];

        addProduct($conn, $name, $category, $price, $stock_quantity, $expiry_date);

        header("Location: inventory.php");
        exit();
    }

    // Handle batch updating stock
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_batch'])) {
        $batch_updates = $_POST['batch_update'];
        foreach ($batch_updates as $update) {
            $name = $update['name'];
            $new_stock = $update['new_stock'];

            $stmt = $conn->prepare("UPDATE Products SET stock_quantity = stock_quantity + ? WHERE name = ?");
            $stmt->bind_param("is", $new_stock, $name);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: inventory.php");
        exit();
    }

    // Handle deleting a product
    if (isset($_GET['delete'])) {
        $name = $_GET['delete'];
        deleteProduct($conn, $name);
        header("Location: inventory.php");
        exit();
    }

    // Pagination setup
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $total_products = $conn->query("SELECT COUNT(*) as total FROM Products")->fetch_assoc()['total'];
    $total_pages = ceil($total_products / $limit);

    $products = $conn->query("SELECT * FROM Products LIMIT $limit OFFSET $offset");

    // Fetch product tracking data
    $tracking_data = $conn->query("SELECT name, stock_quantity, expiry_date FROM Products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Crispy King</title>
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
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Inventory Management</h2>

    <!-- Form for batch updating stock -->
    <h4>Update New Stock</h4>
    <form method="POST" class="mb-4">
        <input type="hidden" name="update_batch">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>New Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <input type="hidden" name="batch_update[<?= $row['name'] ?>][name]" value="<?= htmlspecialchars($row['name']) ?>">
                        <input type="number" name="batch_update[<?= $row['name'] ?>][new_stock]" class="form-control" required>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="update_batch" class="btn btn-warning">Update Stock</button>
    </form>

    <!-- Collapsible form for adding product -->
    <h4>
        <a class="btn btn-info" data-bs-toggle="collapse" href="#addProductForm" role="button" aria-expanded="false" aria-controls="addProductForm">
            Add Product
        </a>
    </h4>
    <div class="collapse" id="addProductForm">
        <form method="POST" class="mb-4">
            <input type="hidden" name="add_product">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="category" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="expiry_date" class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
        </form>
    </div>

    <!-- Product list table -->
    <h4>Product List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Reset the result pointer and fetch the products again for the product list
            $products->data_seek(0);
            while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>₱<?= number_format($row['price'], 2) ?></td>
                <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
                <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                <td>
                    <a href="inventory.php?delete=<?= urlencode($row['name']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                    <a class="page-link" href="inventory.php?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Product Tracking and Monitoring Section -->
<div class="container mt-5">
    <h4>Product Tracking and Monitoring</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Stock Quantity</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $tracking_data->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
                <td><?= htmlspecialchars($row['expiry_date']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


