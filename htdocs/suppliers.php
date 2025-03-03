<?php
    include "db.php";

    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();


    
// Handle adding a new supplier
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_supplier'])) {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];

    $sql = "INSERT INTO Suppliers (name, contact_info, address) 
            VALUES ('$name', '$contact_info', '$address')";
    $conn->query($sql);
    header("Location: suppliers.php");
}

// Handle updating a supplier
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_supplier'])) {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];

    $sql = "UPDATE Suppliers SET contact_info = '$contact_info', address = '$address' WHERE name = '$name'";
    $conn->query($sql);
    header("Location: suppliers.php");
}

// Handle deleting a supplier
if (isset($_GET['delete'])) {
    $name = $_GET['delete'];
    $conn->query("DELETE FROM Suppliers WHERE name = '$name'");
    header("Location: suppliers.php");
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM Suppliers");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - Crispy King</title>
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
        <h2 class="text-center">Suppliers Management</h2>     

        <h4>Add New Supplier</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Supplier Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="contact_info" class="form-control" placeholder="Contact Info" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="address" class="form-control" placeholder="Address" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_supplier" class="btn btn-primary">Add Supplier</button>
                </div>
            </div>
        </form>

        <h4>Update Supplier</h4>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="name" class="form-control" required>
                        <option value="">Select Supplier</option>
                        <?php while ($row = $suppliers->fetch_assoc()): ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="contact_info" class="form-control" placeholder="New Contact Info" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="address" class="form-control" placeholder="New Address" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update_supplier" class="btn btn-warning">Update Supplier</button>
                </div>
            </div>
        </form>

        <h4>Supplier List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $suppliers = $conn->query("SELECT * FROM Suppliers");
                while ($row = $suppliers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['contact_info']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td>
                        <a href="suppliers.php?delete=<?php echo $row['name']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
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
