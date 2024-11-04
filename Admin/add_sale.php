<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity = (int)$_POST['quantity'];
    $total = (float)$_POST['total'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $insert_query = "INSERT INTO sales (product_name, quantity, total, date) 
                     VALUES ('$product_name', '$quantity', '$total', '$date')";

    if (mysqli_query($conn, $insert_query)) {
        $success_message = "Sale added successfully!";
    } else {
        $error_message = "Error adding sale: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Sale - Inventory System</title>
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
            <h3>ADD NEW SALE</h3>
            
            <?php if (isset($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="message error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" min="1" required>
                
                <input type="number" name="total" class="form-control" placeholder="Total" step="0.01" min="0" required>
                
                <input type="date" name="date" class="form-control" required>
                
                <button type="submit" class="btn">Add Sale</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
