<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin' && $_SESSION['role'] === 'admin') {
    echo json_encode(['access' => true]);
} else {
    echo json_encode(['access' => false]);
}
ф