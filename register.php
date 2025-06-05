<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        echo "Будь ласка, заповніть усі поля";
        exit();
    }

    // Перевірка на існуючого користувача
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Користувач з таким імʼям вже існує";
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Вставка користувача
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $email, $hash);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Помилка при реєстрації: " . $stmt->error;
    }
}
?>
