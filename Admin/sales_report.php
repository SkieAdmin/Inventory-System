<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM sales WHERE sale_id = '$delete_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color: green;'>Sale deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting sale: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Inventory System</title>
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
        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
        }
        .edit-btn, .delete-btn {
            cursor: pointer;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 13px;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
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
        .no-sales {
            padding: 15px;
            text-align: center;
            font-size: 16px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>All Sales</h2>
        <a href="add_sale.php" class="add-new-btn">ADD SALE</a>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch sales records from the database
                    $query = "SELECT * FROM sales ORDER BY date DESC";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $sale_id = $row['sale_id'];
                            $product_name = $row['product_name'];
                            $quantity = $row['quantity'];
                            $total = number_format($row['total'], 2);
                            $date = $row['date'];

                            echo "<tr>
                                    <td>{$count}</td>
                                    <td>{$product_name}</td>
                                    <td>{$quantity}</td>
                                    <td>\${$total}</td>
                                    <td>{$date}</td>
                                    <td class='actions'>
                                        <button class='edit-btn' onclick=\"window.location.href='edit_sale.php?id={$sale_id}'\">&#9998;</button>
                                        <button class='delete-btn' onclick=\"window.location.href='sales_report.php?delete_id={$sale_id}'\">&#128465;</button>
                                    </td>
                                  </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan='6' class='no-sales'>No sales records found.</td></tr>";
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
