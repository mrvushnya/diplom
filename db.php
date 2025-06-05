<?php
$host = 'localhost';
$db = 'mukutyk23';         // Назва бази
$user = 'mukutyk234';      // Користувач MySQL
$pass = 'Ozde2324'; // Пароль, який ти вказав при створенні користувача

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>
