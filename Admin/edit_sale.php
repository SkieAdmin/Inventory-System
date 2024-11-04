<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Check if the sale ID is provided
if (isset($_GET['id'])) {
    $sale_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch sale data
    $sale_query = "SELECT * FROM sales WHERE sale_id = '$sale_id'";
    $sale_result = mysqli_query($conn, $sale_query);

    if ($sale_result && mysqli_num_rows($sale_result) > 0) {
        $sale = mysqli_fetch_assoc($sale_result);
    } else {
        echo "<p style='color: red;'>Sale not found.</p>";
        exit;
    }
} else {
    echo "<p style='color: red;'>Invalid sale ID.</p>";
    exit;
}

// Handle form submission to update the sale
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity = (int)$_POST['quantity'];
    $total = (float)$_POST['total'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $update_query = "UPDATE sales 
                     SET product_name = '$product_name', 
                         quantity = '$quantity', 
                         total = '$total', 
                         date = '$date' 
                     WHERE sale_id = '$sale_id'";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Sale updated successfully!";
    } else {
        $error_message = "Error updating sale: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sale - Inventory System</title>
    <style>
        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 70px;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .form-container h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="form-container">
            <h3>Edit Sale</h3>

            <?php if (isset($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="message error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="<?php echo htmlspecialchars($sale['product_name']); ?>" required>
                
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" min="1" value="<?php echo $sale['quantity']; ?>" required>
                
                <input type="number" name="total" class="form-control" placeholder="Total" step="0.01" min="0" value="<?php echo number_format($sale['total'], 2); ?>" required>
                
                <input type="date" name="date" class="form-control" value="<?php echo $sale['date']; ?>" required>
                
                <button type="submit" class="btn">Update Sale</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
