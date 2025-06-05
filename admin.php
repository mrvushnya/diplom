<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Адмін-панель</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 2rem;
    }

    nav ul {
      display: flex;
      list-style: none;
      justify-content: center;
      background: #333;
      margin: 0;
      padding: 0;
    }

    nav ul li {
      margin: 0 1rem;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 1rem;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 800px;
      margin: 0 auto 2rem auto;
    }

    .header h1 {
      margin: 0;
    }

    #userInfo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #userInfo img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    #userInfo button {
      padding: 6px 10px;
      background: #f44336;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .admin-links-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .admin-link {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .admin-link:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    .search-container {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }

    .search-box {
      position: relative;
      width: 100%;
      max-width: 450px;
    }

    #searchInput {
      width: 100%;
      padding: 14px 20px 14px 45px;
      font-size: 16px;
      border: 2px solid #ccc;
      border-radius: 40px;
      transition: 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      background-color: #fff;
      background-image: url('data:image/svg+xml;utf8,<svg fill="gray" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 5L20.49 19l-5-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>');
      background-repeat: no-repeat;
      background-position: 14px center;
      background-size: 20px 20px;
    }

    #searchInput:focus {
      border-color: #3b82f6;
      outline: none;
      box-shadow: 0 0 10px rgba(59,130,246,0.3);
    }

    form, .product-item {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      max-width: 800px;
      margin: 1rem auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    input, textarea, button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      background: #007bff;
      color: white;
      font-weight: bold;
    }

    .product-item img {
      max-width: 100px;
      margin: 5px;
    }

    .action-btns {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .action-btns button {
      flex: 1;
      padding: 8px;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .edit-btn {
      background: #007bff;
      color: white;
    }

    .delete-btn {
      background: #f44336;
      color: white;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Адмін-панель</h1>
  <div id="userInfo">
    <img src="img/6596121.png" alt="Avatar">
    <span><?= $_SESSION['username'] ?></span>
    <button onclick="logout()">Вийти</button>
  </div>
</div>

<nav>
  <ul>
    <li><a href="index.html">Головна</a></li>
  </ul>
</nav>

<div class="admin-links-container">
  <a href="admin_users.php" class="admin-link">👥 Користувачі</a>
  <a href="admin_orders.php" class="admin-link">📦 Замовлення</a>
</div>

<div class="search-container">
  <div class="search-box">
    <input type="text" id="searchInput" placeholder=" Введіть ID або назву товару...">
  </div>
</div>

<form id="product-form">
  <input type="hidden" id="editId">
  <input type="text" id="name" placeholder="Назва товару" required>
  <input type="text" id="imageMain" placeholder="URL головного зображення" required>
  <textarea id="imagesExtra" placeholder="URL інших фото через кому"></textarea>
  <textarea id="description" placeholder="Опис" required></textarea>
  <input type="text" id="price" placeholder="Ціна" required>
  <button type="submit">💾 Зберегти товар</button>
</form>

<h2 style="text-align:center;">Список товарів</h2>
<div id="product-list"></div>

<script>
function logout() {
  fetch('logout.php').then(() => {
    window.location.href = 'login.html';
  });
}

async function renderProducts(filter = "") {
  const res = await fetch('get_products.php');
  const products = await res.json();
  const list = document.getElementById("product-list");

  const filtered = products.filter(p =>
    p.name.toLowerCase().includes(filter.toLowerCase()) ||
    p.id.toString().includes(filter)
  );

  list.innerHTML = filtered.map(product => {
    const imagesHtml = product.images
      ? product.images.split(',').map(url => `<img src="${url.trim()}" alt="extra" style="max-width: 100px; margin: 5px;">`).join("")
      : "";

    const safeProduct = JSON.stringify(product).replace(/'/g, "&apos;");

    return `
      <div class="product-item">
        <h3>${product.name}</h3>
        <p><strong>Ціна:</strong> ${product.price}</p>
        <img src="${product.image}" alt="${product.name}" style="max-width: 150px;">

        <button onclick="toggleDescription(this)" style="background:#007bff;color:#fff;border:none;padding:10px 20px;cursor:pointer;border-radius:4px;margin:10px 0;">🔽 Показати опис</button>
        <p class="product-description" style="display:none;">${product.description}</p>

        <div style="margin-top:10px;">${imagesHtml}</div>

        <div class="action-btns">
          <button class="edit-btn" data-product='${safeProduct}'>✏️ Редагувати</button>
          <button class="delete-btn" onclick="deleteProduct(${product.id})">🗑️ Видалити</button>
        </div>
      </div>
    `;
  }).join("");
}

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("edit-btn")) {
    const product = JSON.parse(e.target.dataset.product.replace(/&apos;/g, "'"));
    editProduct(product);
  }
});

function toggleDescription(btn) {
  const desc = btn.nextElementSibling;
  const isVisible = desc.style.display === "block";
  desc.style.display = isVisible ? "none" : "block";
  btn.textContent = isVisible ? "🔽 Показати опис" : "🔼 Сховати опис";
}

function editProduct(product) {
  document.getElementById("editId").value = product.id;
  document.getElementById("name").value = product.name;
  document.getElementById("imageMain").value = product.image;
  document.getElementById("imagesExtra").value = (product.images || "").trim();
  document.getElementById("description").value = product.description;
  document.getElementById("price").value = product.price;
  document.getElementById("product-form").scrollIntoView({ behavior: "smooth" });
}

async function deleteProduct(id) {
  if (confirm("Ви впевнені, що хочете видалити цей товар?")) {
    const res = await fetch('delete_product.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ id })
    });
    const result = await res.json();
    if (result.success) {
      renderProducts(document.getElementById("searchInput").value);
    } else {
      alert("Помилка видалення: " + result.error);
    }
  }
}

document.getElementById("product-form").addEventListener("submit", async function(e) {
  e.preventDefault();

  const product = {
    id: document.getElementById("editId").value || null,
    name: document.getElementById("name").value,
    image: document.getElementById("imageMain").value,
    images: document.getElementById("imagesExtra").value,
    description: document.getElementById("description").value,
    price: document.getElementById("price").value
  };

  const url = product.id ? 'update_product.php' : 'save_product.php';

  const res = await fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(product)
  });

  const result = await res.json();
  if (result.success) {
    e.target.reset();
    document.getElementById("editId").value = "";
    renderProducts(document.getElementById("searchInput").value);
  } else {
    alert("Помилка збереження: " + result.error);
  }
});

document.getElementById("searchInput").addEventListener("input", function() {
  renderProducts(this.value);
});

document.addEventListener("DOMContentLoaded", () => renderProducts());
</script>

</body>
</html>
