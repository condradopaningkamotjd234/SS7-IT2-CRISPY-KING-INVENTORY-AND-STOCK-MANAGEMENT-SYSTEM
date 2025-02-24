

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crispy King Inventory & Management System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional external CSS file -->
</head>
<body>
    <header>
        <h1>Welcome to Crispy King Inventory & Management System</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add_item.php">Add New Item</a></li>
                <li><a href="update_item.php">Update Item</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Inventory Overview</h2>

            <!-- Search bar for filtering -->
            <div>
                <form method="GET" action="dashboard.php">
                    <input type="text" name="search" placeholder="Search items..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr <?php if ($item['quantity'] < 5) echo 'style="background-color: #f8d7da;"'; ?>>
                                <!-- Highlight low-stock items -->
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <a href="update_item.php?id=<?php echo $item['id']; ?>">Update</a> | 
                                    <a href="delete_item.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No items found in the inventory.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Pagination (if necessary) -->
        <div>
            <!-- You can implement pagination logic here -->
            <p>Page 1 of 1</p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Crispy King. All rights reserved.</p>
    </footer>

    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 2em;
}

nav ul {
    list-style-type: none;
    padding: 0;
    text-align: center;
}

nav ul li {
    display: inline;
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 1.2em;
}

nav ul li a:hover {
    text-decoration: underline;
}

main {
    padding: 20px;
}

/* Form styling */
form {
    margin-bottom: 20px;
    text-align: center;
}

form input[type="text"] {
    padding: 8px;
    font-size: 14px;
    width: 200px;
    margin-right: 10px;
}

form button {
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    font-size: 14px;
    cursor: pointer;
}

form button:hover {
    background-color: #45a049;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Highlight low stock items */
tr[style="background-color: #f8d7da;"] {
    background-color: #f8d7da;
}

/* Footer Styling */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
}

footer p {
    margin: 0;
}

/* Pagination Styling */
div.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    padding: 8px 16px;
    margin: 0 4px;
    border: 1px solid #ddd;
    background-color: #f2f2f2;
    color: #333;
    text-decoration: none;
    font-size: 14px;
}

.pagination a:hover {
    background-color: #ddd;
}

.pagination .active {
    background-color: #4CAF50;
    color: white;
}

    </style>
</body>
</html>
