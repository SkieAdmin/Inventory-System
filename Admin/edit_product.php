<?php
include '../CoreAPI/header.php';
include '../CoreAPI/sidebar.php';
include '../CoreAPI/database_config.php'; // Database connection file

// Get product ID from URL
if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch product data
    $product_query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $product_result = mysqli_query($conn, $product_query);

    if ($product_result && mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
    } else {
        echo "<p style='color: red;'>Product not found.</p>";
        exit;
    }
} else {
    echo "<p style='color: red;'>Invalid product ID.</p>";
    exit;
}

// Fetch categories for dropdown
$category_query = "SELECT category_id, category_name FROM categories";
$category_result = mysqli_query($conn, $category_query);

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_title = mysqli_real_escape_string($conn, $_POST['product_title']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $in_stock = (int) $_POST['in_stock'];
    $buying_price = (float) $_POST['buying_price'];
    $selling_price = (float) $_POST['selling_price'];
    
    // Handle new image upload
    $photo = $product['photo']; // Keep the existing photo by default
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . $photo_extension; // Create a unique filename
        $photo_destination = '../images/' . $photo_name;

        // Move the uploaded file to the images directory
        if (move_uploaded_file($photo_tmp_name, $photo_destination)) {
            // Delete old image if it exists
            if ($photo && file_exists('../images/' . $photo)) {
                unlink('../images/' . $photo);
            }
            $photo = $photo_name; // Set new photo filename
        } else {
            $error_message = "Error uploading new image.";
        }
    }

    // Update product data in the database
    $update_query = "UPDATE products 
                     SET product_title = '$product_title', 
                         category_id = '$category_id', 
                         in_stock = '$in_stock', 
                         buying_price = '$buying_price', 
                         selling_price = '$selling_price', 
                         photo = '$photo' 
                     WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Product updated successfully!";
    } else {
        $error_message = "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Inventory System</title>
    <style>
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
            <h3>EDIT PRODUCT</h3>
            
            <?php if (isset($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="message error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" name="product_title" class="form-control" placeholder="Product Title" value="<?php echo htmlspecialchars($product['product_title']); ?>" required>
                
                <select name="category_id" class="form-control-select" required>
                    <option value="" disabled>Select Product Category</option>
                    <?php while ($category = mysqli_fetch_assoc($category_result)) : ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $product['category_id']) echo 'selected'; ?>>
                            <?php echo $category['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="number" name="in_stock" class="form-control" placeholder="Quantity" min="0" value="<?php echo $product['in_stock']; ?>" required>
                
                <input type="number" name="buying_price" class="form-control" placeholder="Buying price" step="0.01" min="0" value="<?php echo number_format($product['buying_price'], 2); ?>" required>
                
                <input type="number" name="selling_price" class="form-control" placeholder="Selling price" step="0.01" min="0" value="<?php echo number_format($product['selling_price'], 2); ?>" required>
                
                <?php if ($product['photo']): ?>
                    <p>Current Image:</p>
                    <img src="../images/<?php echo $product['photo']; ?>" alt="Product Image" width="100">
                <?php endif; ?>

                <input type="file" name="photo" class="form-control" accept="image/*">

                <button type="submit" class="btn">Update</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
