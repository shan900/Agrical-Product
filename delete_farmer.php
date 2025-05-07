<?php
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);
$farmerId = $data['farmerId'];

$sql = "DELETE FROM farmer_info WHERE farmerId = '$farmerId'";

if ($conn->query($sql) === TRUE) {
    echo "Farmer deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
