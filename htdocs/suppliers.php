<?php
    include "db.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL); 
    session_start();

// Handle updating stock supplies for Magnolia only
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_stock'])) {
    $contact_info = trim($_POST['contact_info']);
    $address = trim($_POST['address']);
    $name = "Magnolia"; // Fixed supplier name

    if (empty($contact_info) || empty($address)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
    } else {
        // Update existing supplier info
        $stmt = $conn->prepare("UPDATE Suppliers SET contact_info = ?, address = ? WHERE name = ?");
        $stmt->bind_param("sss", $contact_info, $address, $name);

        if ($stmt->execute()) {
            header("Location: suppliers.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier - Magnolia</title>
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
                <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Magnolia Supplier Details</h2>
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" value="Magnolia" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" name="contact_info" class="form-control" placeholder="Contact Info" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="address" class="form-control" placeholder="Address" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="update_stock" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>

    <h4>Supplier Information</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Contact Info</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM Suppliers WHERE name = 'Magnolia'");
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
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