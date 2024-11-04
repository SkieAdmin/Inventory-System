<?php
include_once '../CoreAPI/database_config.php'; // Include database configuration
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Sanitize the ID to prevent SQL injection
    $query = "DELETE FROM users WHERE UserID = $userId";
    if ($conn->query($query) === TRUE) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    header("Location: user_management.php");
    exit();
}
$conn->close();
?>
