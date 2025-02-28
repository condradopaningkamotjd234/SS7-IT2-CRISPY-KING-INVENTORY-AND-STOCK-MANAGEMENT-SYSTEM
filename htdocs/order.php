
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders List</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <div class="container">
    <header class="main-header">
      <h1>Orders</h1>
    </header>
    <main class="dashboard-content">
      <section class="orders-list">
        <h2>Order List</h2>
        <?php if ($result->num_rows > 0): ?>
        <table class="inventory-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Supplier ID</th>
              <th>Order Date</th>
              <th>Total Cost</th>
              <th>Status</th>
              <th>User ID</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
              <td><?php echo htmlspecialchars($order['SupplierID']); ?></td>
              <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
              <td>$<?php echo number_format($order['TotalCost'], 2); ?></td>
              <td><?php echo htmlspecialchars($order['Status']); ?></td>
              <td><?php echo htmlspecialchars($order['UserID']); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
        <p>No orders found.</p>
        <?php endif; ?>
      </section>
    </main>
    <footer class="site-footer">
      <p>&copy; 2025 Crispy King. All rights reserved.</p>
    </footer>
  </div>
</body>
</html>
<?php
$result->free();
$mysqli->close();
