<?php
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);
$farmerId = $data['farmerId'];

$sql = "SELECT * FROM farmer_info WHERE farmerId = '$farmerId'";
$result = $conn->query($sql);

$farmer = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $farmer[] = $row;
    }
}

echo json_encode($farmer);
$conn->close();
?>
