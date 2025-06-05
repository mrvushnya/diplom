<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Access denied";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $image = trim($_POST["image"]);

    if ($title && $description && $price && $image) {
        $stmt = $conn->prepare("INSERT INTO products (title, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $title, $description, $price, $image);

        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "DB error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Всі поля обов'язкові";
    }

    $conn->close();
}
?>
