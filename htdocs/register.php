<?php
include 'db.php'; // Include database connection

// Initialize messages
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim($_POST['password']);
    $account_level = trim($_POST['account_level']);

    if (!empty($username) && !empty($password) && !empty($account_level)) {
        if (!preg_match('/^[a-zA-Z0-9_]{5,20}$/', $username)) {
            $error = "Username must be 5-20 characters long and contain only letters, numbers, and underscores.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $error = "Username already exists. Please choose another.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, account_level) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hashed_password, $account_level);
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now <a href='login.php' class='text-link'>login</a>.";
                } else {
                    $error = "Error during registration. Please try again.";
                }
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            background: #ffffff;
            padding: 20px;
            width: 400px;
        }
        .btn-primary {
            background: #6e8efb;
            border: none;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: #5a75e6;
            transform: scale(1.05);
        }
        .text-link {
            text-decoration: none;
            color: #6e8efb;
            font-weight: 600;
        }
        .text-link:hover {
            color: #5a75e6;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-4">Register</h3>
        <?php if (!empty($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='alert alert-success text-center'>$success</div>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <div class="mb-3">
                <label for="account_level" class="form-label">Account Level</label>
                <select name="account_level" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php" class="text-link">Login here</a></small>
        </div>
    </div>
</body>
</html>