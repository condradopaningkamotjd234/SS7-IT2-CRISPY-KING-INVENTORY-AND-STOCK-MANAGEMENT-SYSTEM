<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle adding new stock
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "INSERT INTO Products (name, category, price, stock_quantity, expiry_date) 
            VALUES ('$name', '$category', '$price', '$stock_quantity', '$expiry_date')";
    $conn->query($sql);
    header("Location: inventory.php");
}

// Handle updating stock
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_stock'])) {
    $name = $_POST['name'];
    $new_stock = $_POST['new_stock'];

    $sql = "UPDATE Products SET stock_quantity = stock_quantity + $new_stock WHERE name = '$name'";
    $conn->query($sql);
    header("Location: inventory.php");
}

// Handle deleting a product
if (isset($_GET['delete'])) {
    $name = $_GET['delete'];
    $conn->query("DELETE FROM Products WHERE name = '$name'");
    header("Location: inventory.php");
}

// Fetch all products
$products = $conn->query("SELECT * FROM Products");

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
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


    <div class="container mt-5">
        <h2 class="text-center">Inventory Management</h2>

        <h4>Add New Product</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="category" class="form-control" placeholder="Category" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock_quantity" class="form-control" placeholder="Stock Quantity" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                </div>
            </div>
        </form>

        <h4>Update Stock</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <select name="name" class="form-control" required>
                        <option value="">Select Product</option>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="new_stock" class="form-control" placeholder="Add Quantity" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update_stock" class="btn btn-warning">Update Stock</button>
                </div>
            </div>
        </form>

        <h4>Product List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = $conn->query("SELECT * FROM Products");
                while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td><?php echo $row['expiry_date']; ?></td>
                    <td>
                        <a href="inventory.php?delete=<?php echo $row['name']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
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
