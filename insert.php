<?php
include('db.php');

if (isset($_POST['productId'], $_POST['productName'], $_POST['price'], $_POST['variety'], $_POST['seasonality'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $variety = $_POST['variety'];
    $seasonality = $_POST['seasonality'];

    // Using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO product_information (product_id, product_name, price, variety, seasonality) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $productId, $productName, $price, $variety, $seasonality); // "sssss" means 5 string parameters

    if ($stmt->execute()) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing required fields!";
}

$conn->close();
?>
