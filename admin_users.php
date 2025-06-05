<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

require 'db.php';
$result = $conn->query("SELECT id, username, email, role FROM users ORDER BY id DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Користувачі</title>
  <style>
    body { font-family: Arial; background: #f2f2f2; padding: 2rem; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    h1 { text-align: center; }
    a { display: inline-block; margin-bottom: 1rem; text-decoration: none; color: #007bff; }
  </style>
</head>
<body>
  <h1>Користувачі</h1>
  <a href="admin.php">⬅ Назад в адмінку</a>
  <table>
    <thead>
      <tr><th>ID</th><th>Логін</th><th>Email</th><th>Роль</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</body>
</html>
