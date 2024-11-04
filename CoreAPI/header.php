<?php
session_start(); // Ensure session is started here or in your main file

// Assuming you have session variables for the user's name and role
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Add your CSS file path -->
    <style>
        /* General Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        .header {
            background-color: #007ACC; /* Professional blue color */
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 15px; /* Space between elements */
            font-size: 1rem;
        }

        .status .status-label {
            color: #ffffff; /* Keep the date/time label white */
            font-weight: 600;
        }

        /* Logout Button Styling */
        .logout-button {
            background-color: #E74C3C; /* Professional red color */
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            margin-left: 15px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #C0392B; /* Darker red on hover */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.2rem;
            }
            .status {
                flex-direction: column;
                align-items: flex-end;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div>
            <h1>INVENTORY SYSTEM</h1>
        </div>
        <div class="status">
            <span class="status-label"><?= date('F j, Y, g:i a') ?></span>
            <span><?= htmlspecialchars($username) ?></span>
            <form action="../CoreAPI/logout.php" method="post" style="display: inline;">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
