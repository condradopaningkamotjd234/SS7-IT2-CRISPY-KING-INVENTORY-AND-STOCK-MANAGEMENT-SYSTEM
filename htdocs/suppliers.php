<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();


// Handle adding a new supplier
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_supplier'])) {
    $name = trim($_POST['name']);
    $contact_info = trim($_POST['contact_info']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($contact_info) || empty($address)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Suppliers (name, contact_info, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $contact_info, $address);
        if ($stmt->execute()) {
            header("Location: suppliers.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle deleting a supplier
if (isset($_GET['delete'])) {
    $name = urldecode($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM Suppliers WHERE name = ?");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        header("Location: suppliers.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
}
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
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td>
                <a href="suppliers.php?delete=<?php echo urlencode($row['name']); ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this supplier?');">
   Delete
</a>

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
