<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $expiry = $_POST['expiry'];
    $description = trim($_POST['description']);
    $supplier = trim($_POST['supplier']);

    if (empty($name) || empty($category) || $price <= 0 || $stock < 0 || empty($expiry) || empty($description) || empty($supplier)) {
        $error = "Invalid input. Please fill all fields correctly.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO Product (Name, Category, UnitPrice, StockLevel, ExpiryDate, Description, Supplier) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisis", $name, $category, $price, $stock, $expiry, $description, $supplier);

        if ($stmt->execute()) {
            header("Location: products.php?success=Product added");
            exit();
        } else {
            $error = "Error adding product: " . $mysqli->error;
        }
        $stmt->close();
    }
}

// Handle search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";

// Handle sorting
$allowedSortFields = ['UnitPrice', 'StockLevel', 'ExpiryDate'];
$sortField = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortFields) ? $_GET['sort'] : 'ProductID';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Crispy King</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>Dashboard</h2>
      <ul>
        <li><a href="products.php">Products</a></li>
        <li><a href="sales.php">Sales</a></li>
        <li><a href="suppliers.php">Suppliers</a></li>
        <li><a href="orders.php">Orders</a></li>
      </ul>
    </aside>

    <main class="dashboard-content">
      <header class="dashboard-header">
        <h1>Product Manage</h1>
      </header>

      <section class="product-form">
        <h2>Add New Product</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="products.php" method="POST">
          <input type="text" name="name" placeholder="Product Name" required>
          <input type="text" name="category" placeholder="Category" required>
          <input type="number" name="price" step="0.01" placeholder="Price" required>
          <input type="number" name="stock" placeholder="Stock Level" required>
          <input type="date" name="expiry" placeholder="Expiry Date" required>
          <input type="text" name="description" placeholder="Description" required>
          <input type="text" name="supplier" placeholder="Supplier" required>
          <button type="submit" name="add" class="btn">Add Product</button>
        </form>
      </section>

      <section class="product-list">
        <h2>Product List</h2>
        
        <!-- Search & Sorting Form -->
        <form action="products.php" method="GET">
          <input type="text" name="search" placeholder="Search by Name or Category" value="<?= htmlspecialchars($searchQuery) ?>">
          <select name="sort">
            <option value="UnitPrice" <?= $sortField === 'UnitPrice' ? 'selected' : '' ?>>Price</option>
            <option value="StockLevel" <?= $sortField === 'StockLevel' ? 'selected' : '' ?>>Stock</option>
            <option value="ExpiryDate" <?= $sortField === 'ExpiryDate' ? 'selected' : '' ?>>Expiry Date</option>
          </select>
          <select name="order">
            <option value="asc" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Ascending</option>
            <option value="desc" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Descending</option>
          </select>
          <button type="submit" class="btn">Apply</button>
        </form>

        <table class="inventory-table">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock Level</th>
              <th>Expiry Date</th>
              <th>Description</th>
              <th>Supplier</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($searchQuery)) {
                $stmt = $mysqli->prepare("SELECT ProductID, Name, Category, UnitPrice, StockLevel, ExpiryDate, Description, Supplier FROM Product WHERE Name LIKE ? OR Category LIKE ? ORDER BY $sortField $sortOrder");
                $searchTerm = "%" . $searchQuery . "%";
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $query = "SELECT ProductID, Name, Category, UnitPrice, StockLevel, ExpiryDate, Description, Supplier FROM Product ORDER BY $sortField $sortOrder";
                $result = $mysqli->query($query);
            }

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['ProductID']) . "</td>
                        <td>" . htmlspecialchars($row['Name']) . "</td>
                        <td>" . htmlspecialchars($row['Category']) . "</td>
                        <td>$" . number_format($row['UnitPrice'], 2) . "</td>
                        <td>" . htmlspecialchars($row['StockLevel']) . "</td>
                        <td>" . htmlspecialchars($row['ExpiryDate']) . "</td>
                        <td>" . htmlspecialchars($row['Description']) . "</td>
                        <td>" . htmlspecialchars($row['Supplier']) . "</td>
                        <td>
                          <a href='edit_product.php?id=" . $row['ProductID'] . "' class='edit-btn'>Edit</a> |
                          <a href='product_actions.php?delete=" . $row['ProductID'] . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='9'>No products found.</td></tr>";
            }
            $mysqli->close();
            ?>
            <tr>
              <form action="products.php" method="POST">
                <td>New</td>
                <td><input type="text" name="name" placeholder="Product Name" required></td>
                <td><input type="text" name="category" placeholder="Category" required></td>
                <td><input type="number" name="price" step="0.01" placeholder="Price" required></td>
                <td><input type="number" name="stock" placeholder="Stock Level" required></td>
                <td><input type="date" name="expiry" placeholder="Expiry Date" required></td>
                <td><input type="text" name="description" placeholder="Description" required></td>
                <td><input type="text" name="supplier" placeholder="Supplier" required></td>
                <td><button type="submit" name="add" class="btn">Add</button></td>
              </form>
            </tr>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>
</html>

