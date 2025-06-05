<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = intval($data['id']);
$name = $data['name'];
$image = $data['image'];
$images = $data['images']; // рядок, а не масив
$description = $data['description'];
$price = $data['price'];

$stmt = $conn->prepare("UPDATE products SET name=?, image=?, images=?, description=?, price=? WHERE id=?");
$stmt->bind_param("ssssdi", $name, $image, $images, $description, $price, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
