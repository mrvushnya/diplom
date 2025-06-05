<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        $product['id'] = intval($product['id']); // ← переконаємось, що id точно число
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Товар не знайдено"]);
    }
} else {
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $row['id'] = intval($row['id']); // ← теж саме для масиву
        $products[] = $row;
    }

    echo json_encode($products);
}
?>
