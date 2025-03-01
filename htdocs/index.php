<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/dashboard.css">
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
        <h1>Welcome to the Dashboard</h1>
      </header>
    </main>
  </div>
<style> 
  /* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

body {
  display: flex;
  height: 100vh;
  background-color: #f4f4f4;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background: #2c3e50;
  color: white;
  padding: 20px;
  position: fixed;
  height: 100%;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 22px;
}

.sidebar ul {
  list-style: none;
}

.sidebar ul li {
  margin: 15px 0;
}

.sidebar ul li a {
  text-decoration: none;
  color: white;
  display: block;
  padding: 10px;
  border-radius: 5px;
  transition: background 0.3s;
}

.sidebar ul li a:hover {
  background: #34495e;
}

/* Main Dashboard Content */
.dashboard-container {
  display: flex;
  width: 100%;
}

.dashboard-content {
  margin-left: 250px;
  padding: 20px;
  flex-grow: 1;
}

.dashboard-header {
  background: white;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  text-align: center;
}

</style>


</body>
</html>
