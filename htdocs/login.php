<?php
include 'db.php';
session_start();

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        // Sanitize user input
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']);

        if (empty($username) || empty($password)) {
            $error = "Username and password are required.";
        } else {
            // Query database securely
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['username'] = $user['username'];
                $_SESSION['account_level'] = $user['account_level'];
                $_SESSION['last_login_time'] = time(); // Track session time

                // Implement 'Remember Me' functionality
                if ($remember_me) {
                    setcookie('username', $user['username'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie('account_level', $user['account_level'], time() + (30 * 24 * 60 * 60), "/");
                }

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                sleep(1); // Delay to prevent brute force attacks
                $error = "Invalid username or password.";
            }
        }
    }
}

// Refresh CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<div class="container">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="text-center mb-4">Login</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus autocomplete="username"
                           value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe"
                           <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php" class="text-link">Go to Register</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>