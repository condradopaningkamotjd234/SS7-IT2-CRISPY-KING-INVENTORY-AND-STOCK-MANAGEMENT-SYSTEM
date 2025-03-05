<?php
    include "db.php";
    session_start();

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Ensure ID is an integer

        // Delete the supplier from the database
        $query = "DELETE FROM Suppliers WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Supplier deleted successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting supplier!";
            $_SESSION['msg_type'] = "danger";
        }

        $stmt->close();
        $conn->close();

        // Redirect back to suppliers page
        header("Location: suppliers.php");
        exit();
    }
?>
