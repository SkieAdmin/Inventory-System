<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Fetch categories for the dropdown
$category_query = "SELECT category_id, category_name FROM categories";
$category_result = mysqli_query($conn, $category_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_title = mysqli_real_escape_string($conn, $_POST['product_title']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $in_stock = (int) $_POST['in_stock'];
    $buying_price = (float) $_POST['buying_price'];
    $selling_price = (float) $_POST['selling_price'];
    
    // Handle image upload
    $photo = null; // Initialize photo variable
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . $photo_extension; // Create a unique filename
        $photo_destination = '../images/' . $photo_name;

        // Move the uploaded file to the images directory
        if (move_uploaded_file($photo_tmp_name, $photo_destination)) {
            $photo = $photo_name; // Set photo variable to the filename if upload is successful
        } else {
            $error_message = "Error uploading image.";
        }
    }

    // Insert product data into the database, including photo if available
    $insert_query = "INSERT INTO products (product_title, category_id, in_stock, buying_price, selling_price, photo) 
                     VALUES ('$product_title', '$category_id', '$in_stock', '$buying_price', '$selling_price', '$photo')";

    if (mysqli_query($conn, $insert_query)) {
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Error adding product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Inventory System</title>
    <style>
        /* Main content styling */
        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 70px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 800px;
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
        .form-control-select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            appearance: none;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            display: block;
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
            <h3>ADD NEW PRODUCT</h3>
            
            <?php if (isset($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="message error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" name="product_title" class="form-control" placeholder="Product Title" required>
                
                <select name="category_id" class="form-control-select" required>
                    <option value="" disabled selected>Select Product Category</option>
                    <?php while ($category = mysqli_fetch_assoc($category_result)) : ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo $category['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="number" name="in_stock" class="form-control" placeholder="Product Quantity" min="0" required>
                
                <input type="number" name="buying_price" class="form-control" placeholder="Buying Price" step="0.01" min="0" required>
                
                <input type="number" name="selling_price" class="form-control" placeholder="Selling Price" step="0.01" min="0" required>

                <input type="file" name="photo" class="form-control" accept="image/*">

                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
