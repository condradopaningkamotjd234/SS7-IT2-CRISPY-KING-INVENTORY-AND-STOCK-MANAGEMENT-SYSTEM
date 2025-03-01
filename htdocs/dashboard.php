<?php
include "db_connect.php"; 
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Crispy King</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 text-center">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSziaErl20gqmnCy3UkUABRLOev7JO29bnqED6jkNOfN2YMCN0uGsQdjcfmHMMFa4od-lM&usqp=CAU" class="logo">
    <h1>Welcome to Crispy King, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Account Level: <?php echo htmlspecialchars($_SESSION['account_level']); ?></p>
    <?php if ($_SESSION['account_level'] === 'admin'): ?>
        <a href="index.php" class="btn btn-danger">Go to Admin Page</a>
    <?php else: ?>
        <a href="index.php" class="btn btn-success">Go to User Page</a>
    <?php endif; ?>
    <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</div>
<div class="image-container">
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.logo {
    width: 150px;
    margin-bottom: 20px;
}

h1 {
    color: #333333;
}

p {
    color: #666666;
}

.btn {
    margin: 10px;
}

.image-container {
    text-align: center;
    margin-top: 20px;
}

.welcome-image {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

</style>
</body>
</html>