<?php
include('db.php');

$sql = "SELECT * FROM product_information";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['farmerId']}</td>
            <td>{$row['name']}</td>
            <td>{$row['age']}</td>
            <td>{$row['address']}</td>
            <td>{$row['city']}</td>
            <td>{$row['zip']}</td>
            <td class='actions'>
                <button class='button edit-button' onclick='editFarmer({$row['farmerId']})'>Edit</button>
                <button class='button delete-button' onclick='deleteFarmer({$row['farmerId']})'>Delete</button>
            </td>
        </tr>";
}

$conn->close();
?>
