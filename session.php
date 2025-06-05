<?php
session_start();
echo json_encode([
  "loggedIn" => isset($_SESSION['username']),
  "username" => $_SESSION['username'] ?? null,
  "role" => $_SESSION['role'] ?? null
]);
?>
