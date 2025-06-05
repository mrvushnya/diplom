CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(10) NOT NULL DEFAULT 'user'
);

INSERT INTO users (username, password, role)
VALUES ('mukutyk', '$2y$10$Zy8iY5NsFzMZ9LZK4BOEie2EYPVO9fQ2wZRxDE0UqaZ3O9nMH0tyK', 'admin');

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
