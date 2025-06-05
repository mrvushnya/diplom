<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "error" => "ID відсутній"]);
    exit;
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
