<?php
include "db_connect.php"; 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

  $sql = "SELECT * FROM Stock";
  $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFAE5;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .button {
            background-color: #FFA500;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #FF8C00;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #FFA500;
            color: white;
        }
    </style>
</head>
<body>
<ul>
                    <li><a href="main_admin Dashboard.php">Dashboard</a></li>
                    <li><a href="stock.php">Stock</a></li>
                    <!-- Added sample order_id parameter -->
                    <li><a href="orderdetails.php">Order Details</a></li>
                    <li><a href="order.php">order</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>

<div class="container" style="margin-top: 20px;">
        <a href="add_stock.php" class="button">Add New Stock</a>
    </div>

    <div class="container" style="margin-top: 20px;">
        <h2>Stock List</h2>
        <table>
            <tr>
                <th>Stock ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Date Added</th>
                <th>Last Updated</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["StockID"]; ?></td>
                    <td><?php echo $row["ProductID"]; ?></td>
                    <td><?php echo $row["Quantity"]; ?></td>
                    <td><?php echo $row["Status"]; ?></td>
                    <td><?php echo $row["DateAdded"]; ?></td>
                    <td><?php echo $row["LastUpdated"]; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
<?php $conn->close(); ?>
