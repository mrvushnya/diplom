<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

require 'db.php';
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Замовлення</title>
  <style>
    body { font-family: Arial; background: #f2f2f2; padding: 2rem; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    h1 { text-align: center; }
    a { display: inline-block; margin-bottom: 1rem; text-decoration: none; color: #007bff; }

    select, button {
      padding: 6px 10px;
      border-radius: 4px;
    }

    .delete-btn {
      background-color: #f44336;
      color: white;
      border: none;
      cursor: pointer;
    }

    .delete-btn:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <h1>Замовлення</h1>
  <a href="admin.php">⬅ Назад в адмінку</a>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Ім’я</th>
        <th>Прізвище</th>
        <th>Телефон</th>
        <th>Email</th>
        <th>ID Товару</th>
        <th>Статус</th>
        <th>Дія</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['name']) ?></td>
          <td><?= htmlspecialchars($order['lastName']) ?></td>
          <td><?= htmlspecialchars($order['phone']) ?></td>
          <td><?= htmlspecialchars($order['email']) ?></td>
          <td><?= $order['productId'] ?></td>
          <td>
            <select onchange="changeStatus(<?= $order['id'] ?>, this.value)">
              <?php
              $statuses = ['нове', 'виконується', 'виконано'];
              foreach ($statuses as $status) {
                $selected = ($order['status'] === $status) ? 'selected' : '';
                echo "<option value=\"$status\" $selected>$status</option>";
              }
              ?>
            </select>
          </td>
          <td>
            <button
              class="delete-btn"
              onclick="deleteOrder(<?= $order['id'] ?>)"
              <?= $order['status'] !== 'виконано' ? 'disabled' : '' ?>
            >🗑 Видалити</button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <script>
    function changeStatus(id, status) {
      fetch('update_order_status.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id, status })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('✅ Статус оновлено');
          location.reload();
        } else {
          alert('❌ Помилка: ' + data.error);
        }
      });
    }

    function deleteOrder(id) {
      if (!confirm("Ви впевнені, що хочете видалити це замовлення?")) return;

      fetch('delete_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ Замовлення видалено");
          location.reload();
        } else {
          alert("❌ Помилка видалення: " + data.error);
        }
      });
    }
  </script>
</body>
</html>
