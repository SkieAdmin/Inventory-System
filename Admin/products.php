<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM products WHERE product_id = '$delete_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color: green;'>Product deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting product: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Inventory System</title>
    <style>
        /* Main content styling */
        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 70px;
            font-family: Arial, sans-serif;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .add-new-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        .add-new-btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        thead th {
            background-color: #f0f8ff;
            color: #333;
            font-weight: bold;
            padding: 12px;
            border: 1px solid #ddd;
        }
        tbody td {
            padding: 10px;
            border: 1px solid #ddd;
            color: #555;
            text-align: center;
            vertical-align: middle; /* Ensures alignment in the center for all cells */
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #e9f5ff;
        }
        tbody td img {
            border-radius: 4px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center; /* Align buttons in the center of the Actions column */
            height: 100%; /* Ensures the container takes up full cell height */
        }
        .edit-btn, .delete-btn {
            cursor: pointer;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 13px;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center; /* Center-aligns text within buttons */
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #b02a37;
        }
        .no-products {
            padding: 15px;
            text-align: center;
            font-size: 16px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Products</h2>
        <a href="add_product.php" class="add-new-btn">ADD NEW</a>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Product Title</th>
                        <th>Categories</th>
                        <th>In-Stock</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Product Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch products and their categories from the database
                    $query = "SELECT 
                                p.product_id, 
                                p.product_title, 
                                p.photo, 
                                c.category_name, 
                                p.in_stock, 
                                p.buying_price, 
                                p.selling_price, 
                                p.date_added 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.category_id";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $product_id = $row['product_id'];
                            $product_title = $row['product_title'];
                            $photo = $row['photo'] ? '../images/' . $row['photo'] : '../images/default_photo.png';
                            $category_name = $row['category_name'];
                            $in_stock = $row['in_stock'];
                            $buying_price = number_format($row['buying_price'], 2);
                            $selling_price = number_format($row['selling_price'], 2);
                            $date_added = date("F j, Y, g:i a", strtotime($row['date_added']));
                            
                            echo "<tr>
                                    <td>{$count}</td>
                                    <td><img src='$photo' alt='Product Photo' width='40'></td>
                                    <td>{$product_title}</td>
                                    <td>{$category_name}</td>
                                    <td>{$in_stock}</td>
                                    <td>\${$buying_price}</td>
                                    <td>\${$selling_price}</td>
                                    <td>{$date_added}</td>
                                    <td class='actions'>
                                        <button class='edit-btn' onclick=\"window.location.href='edit_product.php?id={$product_id}'\">Edit</button>
                                        <button class='delete-btn' onclick=\"window.location.href='products.php?delete_id={$product_id}'\">Delete</button>
                                    </td>
                                  </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan='9' class='no-products'>No products found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
