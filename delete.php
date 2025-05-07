<?php
include('db.php');

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM product_information WHERE product_id = ?");
    $stmt->bind_param("s", $productId); // "s" means the parameter is a string (adjust if necessary)

    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Product ID not provided!";
}

$conn->close();
?>
