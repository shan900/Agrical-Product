<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$sql = "UPDATE farmer_info SET name=?, age=?, address=?, city=?, zip=? WHERE farmerId=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sissss", $data['name'], $data['age'], $data['address'], $data['city'], $data['zip'], $data['farmerId']);
$stmt->execute();

echo json_encode(["status" => "updated"]);
?>
