<?php
session_start();
$userRole = $_SESSION['user']['Role'] ?? 'User'; // Default to 'User' if not set
?>

<style>
    /* Sidebar specific styles */
    .sidebar {
        width: 250px;
        background-color: #1e1e1e; /* Dark sidebar color */
        color: #ffffff;
        padding-top: 20px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar ul li {
        padding: 10px 20px;
    }

    .sidebar ul li a {
        color: #ffffff;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .sidebar ul li a:hover {
        background-color: #333333;
        color: #dcdcdc;
    }

    .sidebar ul li a.active {
        background-color: #555555;
        color: #ffffff;
    }

    .sidebar ul li a .emoji {
        margin-right: 12px; /* Space between emoji and text */
    }
</style>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <?php if ($userRole === 'Administrator'): ?>
            <li><a href="index.php"><span class="emoji">📊</span> Dashboard</a></li>
            <li><a href="user_management.php"><span class="emoji">👥</span> User Management</a></li>
            <li><a href="categories.php"><span class="emoji">🏷️</span> Categories</a></li>
            <li><a href="products.php"><span class="emoji">📦</span> Products</a></li>
            <li><a href="media_files.php"><span class="emoji">🖼️</span> Media Files</a></li>
            <li><a href="sales.php"><span class="emoji">🛒</span> Sales</a></li>
            <li><a href="sales_report.php"><span class="emoji">📈</span> Sales Report</a></li>
        <?php elseif ($userRole === 'Employee'): ?>
            <li><a href="dashboard.php"><span class="emoji">📊</span> Dashboard</a></li>
            <li><a href="categories.php"><span class="emoji">🏷️</span> Categories</a></li>
            <li><a href="products.php"><span class="emoji">📦</span> Products</a></li>
            <li><a href="sales.php"><span class="emoji">🛒</span> Sales</a></li>
            <li><a href="sales_report.php"><span class="emoji">📈</span> Sales Report</a></li>
        <?php endif; ?>
    </ul>
</div>
