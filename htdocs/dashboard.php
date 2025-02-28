<?php
// Ensure the inventory data is fetched before rendering the HTML
$inventory = []; // Fetch inventory data from the database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crispy King Inventory & Stock Management System</title>
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Changed to external CSS for a professional look -->
</head>
<body>
    <!-- Added container wrapper -->
    <div class="container">
        <header class="main-header">
            <h1>Welcome to Crispy King Inventory & Stock Management System</h1>
            <!-- Removed navigation from header -->
        </header>
        <div class="content-wrapper">
            <aside class="sidebar">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <!-- Added sample order_id parameter -->
                    <li><a href="orderdetails.php">Order Details</a></li>
                    <li><a href="order.php">order</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </aside>
            <main class="dashboard-content">
                <section class="inventory-overview">
                    <h2>Inventory Overview</h2>

                    <!-- Search bar for filtering -->
                    <div class="search-bar">
                        <form method="GET" action="dashboard.php">
                            <input type="text" name="search" placeholder="Search items..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit">Search</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="inventory-table">
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
                                        <tr class="<?php echo ($item['quantity'] < 5) ? 'low-stock' : ''; ?>">
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
                    </div>

                    <div class="pagination">
                        <!-- Pagination placeholder for professional design -->
                        <p>Page 1 of 1</p>
                    </div>
                </section>

                <!-- New Flowchart section for full screen view -->
                <section class="flowchart-section">
                    <h2>Flowchart Overview</h2>
                    <div id="flowchart-container">
                        <!-- Flowchart visualization placeholder -->
                        <p>Flowchart goes here...</p>
                    </div>
                </section>
            </main>
        </div>
        <footer class="site-footer">
            <p>&copy; 2025 Crispy King. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
