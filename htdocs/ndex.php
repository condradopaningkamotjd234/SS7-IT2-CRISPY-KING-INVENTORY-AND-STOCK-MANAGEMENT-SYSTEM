<?php
  $title = "Welcome to Crispy King";
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
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        <p>Manage your inventory with ease and efficiency.</p>
        <a href="dashboard.php" class="button">Get Started</a>
    </div>
<ul>
    <li>
    <a href="login.php" class="button">Login</a>
    <a href="register.php" class="button">Register</a>
    </li>
</ul>


</body>
</html>
