<?php
require 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id = intval($data['id']);

if (!$id) {
    echo json_encode(["success" => false, "error" => "ID відсутній"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
