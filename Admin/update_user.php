<?php
ob_start(); // Start output buffering to prevent header issues

include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include_once '../CoreAPI/database_config.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Sanitize the ID to prevent SQL injection

    // Fetch the user's current information
    $query = "SELECT * FROM users WHERE UserID = $userId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get the user data
        $user = $result->fetch_assoc();
    } else {
        // If no user found, redirect to user_management.php
        header("Location: user_management.php");
        exit();
    }
} else {
    // If no ID is provided, redirect to user_management.php
    header("Location: user_management.php");
    exit();
}

// Update the user data if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $fullName = $conn->real_escape_string($_POST['full_name']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']); // Store password as plain text (for educational purposes only)
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);

    // Prepare the update query
    $updateQuery = "UPDATE users SET 
                    EmployeeName = '$fullName', 
                    Username = '$username', 
                    Password = '$password', 
                    Role = '$role', 
                    Status = '$status' 
                    WHERE UserID = $userId";

    // Execute the query and check if successful
    if ($conn->query($updateQuery) === TRUE) {
        // Redirect to user_management.php after successful update
        header("Location: user_management.php");
        exit();
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
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
        <h2>Update User</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="update_user.php?id=<?php echo $userId; ?>" method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['EmployeeName']); ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['Password']); ?>" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="Administrator" <?php if ($user['Role'] == 'Administrator') echo 'selected'; ?>>Admin</option>
                <option value="Employee" <?php if ($user['Role'] == 'Employee') echo 'selected'; ?>>Staff</option>
            </select>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Active" <?php if ($user['Status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($user['Status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection and flush output buffer
$conn->close();
ob_end_flush();
?>
