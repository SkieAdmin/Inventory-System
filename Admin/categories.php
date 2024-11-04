<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Handle form submission to add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['category_name'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $insert_query = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<p style='color: green;'>Category added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error adding category: " . mysqli_error($conn) . "</p>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM categories WHERE category_id = '$delete_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color: green;'>Category deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting category: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Inventory System</title>
    <style>
        /* Main content styling */
        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 70px;
        }
        .category-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .add-category, .all-categories {
            width: 48%;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .add-category h3, .all-categories h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .edit-btn, .delete-btn {
            cursor: pointer;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Categories</h2>
        <div class="category-container">
            <!-- Add New Category Section -->
            <div class="add-category">
                <h3>Add New Category</h3>
                <form method="POST" action="">
                    <input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
                    <button type="submit" class="btn">Add Category</button>
                </form>
            </div>

            <!-- All Categories Section -->
            <div class="all-categories">
                <h3>All Categories</h3>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch categories from the database
                        $query = "SELECT category_id, category_name FROM categories";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $category_id = $row['category_id'];
                                $category_name = $row['category_name'];
                                
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$category_name}</td>
                                        <td class='actions'>
                                            <button class='edit-btn' onclick=\"window.location.href='edit_category.php?id={$category_id}'\">Edit</button>
                                            <button class='delete-btn' onclick=\"window.location.href='categories.php?delete_id={$category_id}'\">Delete</button>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='3'>No categories found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
