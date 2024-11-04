<?php
session_start();
include 'CoreAPI/database_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Improved security with password verification
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify hashed password
    if ($user && $password === $user['Password']) {
        $_SESSION['user'] = $user;
        if ($user['Role'] == 'Administrator') {
            header('Location: Admin/index.php');
        } else {
            header('Location: Employee/index.php');
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Login</title>
    <style>
        /* Body styling for background blur and dark theme */
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #ffffff;
            backdrop-filter: blur(5px);
        }

        /* Container styling */
        .login-container {
            background: rgba(30, 30, 30, 0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }

        /* Heading styling */
        .login-container h2 {
            margin-bottom: 20px;
            color: #ffffff;
            font-weight: bold;
        }

        /* Input styling */
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background-color: #333;
            color: #ffffff;
            font-size: 16px;
        }

        /* Button styling */
        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #6200ea;
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container input[type="submit"]:hover {
            background-color: #3700b3;
        }

        /* Error message styling */
        .error {
            color: #ff6b6b;
            font-weight: bold;
            margin: 10px 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .login-container {
                padding: 20px;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
