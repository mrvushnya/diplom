<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$image = $data['image'];
$images = isset($data['images']) ? $data['images'] : "";
$description = $data['description'];
$price = $data['price'];

if (!empty($data['id'])) {
    // ✏️ Якщо передано id — оновлення
    $id = intval($data['id']);
    $stmt = $conn->prepare("UPDATE products SET name=?, image=?, images=?, description=?, price=? WHERE id=?");
    $stmt->bind_param("ssssdi", $name, $image, $images, $description, $price, $id);
} else {
    // ➕ Інакше — додавання
    $stmt = $conn->prepare("INSERT INTO products (name, image, images, description, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $name, $image, $images, $description, $price);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
