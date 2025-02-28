<?php
include "db_connect.php"; 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Helper: Output error page and exit
function errorOutput($message) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Error - Invalid Order</title>
      <link rel="stylesheet" href="css/dashboard.css">
    </head>
    <body>
      <div class="container">
        <header class="main-header">
          <h1>Error</h1>
        </header>
        <main class="dashboard-content">
          <p><?php echo htmlspecialchars($message); ?></p>
        </main>
        <footer class="site-footer">
          <p>&copy; 2025 Crispy King. All rights reserved.</p>
        </footer>
      </div>
    </body>
    </html>
    <?php
    exit;
}

// Validate order_id
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if ($order_id === false || $order_id === null) {
    errorOutput("Invalid order ID. Please provide a valid order.");
}

// Establish a secure mysqli connection
$mysqli = new mysqli("localhost", "dbuser", "dbpass", "inventory_db");
if ($mysqli->connect_error) {
    errorOutput("Database connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// Fetch order details using the correct column name
$stmt = $mysqli->prepare("SELECT * FROM orders WHERE OrderID = ?");
if (!$stmt) {
    errorOutput("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();
if (!$order) {
    errorOutput("Order not found. Please check the order ID.");
}

/* Updated query using your OrderDetails table.
   Columns: OrderDetailID, OrderID, productID, Quantity, Cost, UserID.
   Also joining the products table to fetch a product name. */
$stmt = $mysqli->prepare("SELECT od.OrderDetailID, od.OrderID, od.productID, od.Quantity, od.Cost, od.UserID, p.name AS product_name 
                          FROM OrderDetails AS od 
                          JOIN products AS p ON od.productID = p.id 
                          WHERE od.OrderID = ?");
if (!$stmt) {
    errorOutput("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details - Order #<?php echo htmlspecialchars($order['OrderID']); ?></title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <div class="container">
    <header class="main-header">
      <h1>Order Details</h1>
    </header>
    <main class="dashboard-content">
      <section class="order-summary">
        <h2>Order ID: <?php echo htmlspecialchars($order['OrderID']); ?></h2>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
      </section>
      <section class="order-items">
        <h2>Order Items</h2>
        <?php if (!empty($order_items)): ?>
        <table class="inventory-table">
          <thead>
            <tr>
              <th>Order Detail ID</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Cost</th>
              <th>User ID</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order_items as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['OrderDetailID']); ?></td>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
              <td>$<?php echo number_format($item['Cost'], 2); ?></td>
              <td><?php echo htmlspecialchars($item['UserID']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <p>No order items found.</p>
        <?php endif; ?>
      </section>
    </main>
    <footer class="site-footer">
      <p>&copy; 2025 Crispy King. All rights reserved.</p>
    </footer>
  </div>
</body>
</html>
