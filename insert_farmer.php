<?php
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);

$farmerId = $data['farmerId'];
$name = $data['name'];
$age = $data['age'];
$address = $data['address'];
$city = $data['city'];
$zip = $data['zip'];

$sql = "INSERT INTO farmer_info (farmerId, name, age, address, city, zip) VALUES ('$farmerId', '$name', '$age', '$address', '$city', '$zip')";

if ($conn->query($sql) === TRUE) {
    echo "New farmer added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
