<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mariadb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a new supplier
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];

    $sql = "INSERT INTO Suppliers (name, contact_info, address) 
            VALUES ('$name', '$contact_info', '$address')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Supplier added successfully!'); window.location.href='suppliers.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>