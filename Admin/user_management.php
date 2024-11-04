<?php
// Include database configuration and other components
include_once '../CoreAPI/database_config.php';
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';

// Fetch users from the database with error handling
$query = "SELECT UserID, Username, EmployeeName, Role, Status, LastLogin, AccountCreated FROM users";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching users: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Add the correct CSS file path -->
    <style>
        /* General styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
        }

        /* Content layout */
        .content {
            margin-left: 270px; /* Adjust for the sidebar width */
            padding: 20px;
            margin-top: 70px; /* Adjust for the header height */
        }

        /* Page title styling */
        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

/* Updated Add User Button Styling */
.add-user-button {
    padding: 8px 16px; /* Reduced padding */
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px; /* Slightly smaller border radius */
    font-weight: 500; /* Reduced font weight */
    font-size: 14px; /* Smaller font size */
    transition: background-color 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Lighter shadow */
    cursor: pointer;
}
        /* Table styling */
        .add-user-button:hover {
            background-color: #218838;
        }


        /* Table styling */
        .user-table {
            width: 100%; /* Occupies full content width */
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .user-table th {
            background-color: #007ACC;
            color: #ffffff;
            font-weight: bold;
        }

        .user-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .user-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Action links styling */
        .action-links a {
            margin: 0 8px;
            text-decoration: none;
            color: #007ACC;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .action-links a:hover {
            color: #005999;
        }

        /* Status color styling */
        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Page Title and Add Button -->
        <div class="page-title">
            <span>User Management</span>
            <a href="add_user.php" class="add-user-button">Add User</a>
        </div>

        <!-- User Table -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Employee Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Account Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['UserID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Username']); ?></td>
                        <td><?php echo htmlspecialchars($row['EmployeeName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Role']); ?></td>
                        <td>
                            <?php if ($row['Status'] === 'Active'): ?>
                                <span class="status-active"><?php echo htmlspecialchars($row['Status']); ?></span>
                            <?php else: ?>
                                <span class="status-inactive"><?php echo htmlspecialchars($row['Status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['LastLogin'] ?? 'Never'); ?></td>
                        <td><?php echo htmlspecialchars($row['AccountCreated']); ?></td>
                        <td class="action-links">
                            <a href="update_user.php?id=<?php echo urlencode($row['UserID']); ?>">Update</a>
                            <a href="delete_user.php?id=<?php echo urlencode($row['UserID']); ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
