<?php
include('db.php');

// SQL query to get all products
$sql = "SELECT * FROM product_information";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['variety']}</td>
                <td>{$row['seasonality']}</td>
                <td class='actions'>
                    <button class='button edit-button' onclick='editProduct({$row['product_id']})'>Edit</button>
                    <button class='button delete-button' onclick='deleteProduct({$row['product_id']})'>Delete</button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No products found!</td></tr>";
}

$stmt->close();
$conn->close();
?>
