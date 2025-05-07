<?php
include('db.php');

if (isset($_POST['productId'], $_POST['productName'], $_POST['price'], $_POST['variety'], $_POST['seasonality'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $variety = $_POST['variety'];
    $seasonality = $_POST['seasonality'];

    // Using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE product_information SET product_name = ?, price = ?, variety = ?, seasonality = ? WHERE product_id = ?");
    $stmt->bind_param("sssss", $productName, $price, $variety, $seasonality, $productId);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing required fields!";
}

$conn->close();
?>
