<?php
ob_start(); // Start output buffering

include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include_once '../CoreAPI/database_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $fullName = $conn->real_escape_string($_POST['full_name']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']); // Store password as plain text (for educational purposes only)
    $role = $conn->real_escape_string($_POST['role']);
    $status = 'Active'; // Set default status to Active
    $accountCreated = date("Y-m-d H:i:s"); // Current date and time

    // Insert the new user into the database
    $query = "INSERT INTO users (EmployeeName, Username, Password, Role, Status, AccountCreated) 
              VALUES ('$fullName', '$username', '$password', '$role', '$status', '$accountCreated')";

    if ($conn->query($query) === TRUE) {
        // Redirect to user_management.php after successful insertion
        header("Location: user_management.php");
        exit();
    } else {
        $error = "Error adding user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        /* General styling */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        /* Main content styling */
        .content {
            margin-left: 270px; /* Adjust for sidebar width */
            padding: 20px;
            margin-top: 70px; /* Adjust for header height */
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 90px); /* Adjust for header and padding */
            background-color: #f0f2f5;
        }

        /* Form container styling */
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .form-container label {
            display: block;
            text-align: left;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            transition: border 0.3s;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="password"]:focus,
        .form-container select:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-block;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container button:active {
            transform: scale(0.98);
        }

        .error {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="content">
    <div class="form-container">
        <h2>Add New User</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="add_user.php" method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="Administrator">Admin</option>
                <option value="Employee">Staff</option>
            </select>

            <button type="submit">Create User</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
ob_end_flush(); // End output buffering to send everything to the browser
?>
