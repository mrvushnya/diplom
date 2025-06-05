<?php
header("Content-Type: application/json");
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $productId = $_POST['productId'] ?? '';

    if (!$name || !$lastName || !$phone || !$email || !$productId) {
        echo json_encode(["success" => false, "error" => "Усі поля обов'язкові"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO orders (name, lastName, phone, email, productId) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $lastName, $phone, $email, $productId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Невірний метод запиту"]);
}
?>
