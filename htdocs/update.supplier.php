<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mariadb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle updating supplier details
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];

    $sql = "UPDATE Suppliers SET contact_info = '$contact_info', address = '$address' WHERE name = '$name'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Supplier updated successfully!'); window.location.href='suppliers.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
