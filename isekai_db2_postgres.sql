-- PostgreSQL conversion of isekai_db2
-- Compatible with Supabase and Railway

-- admins
CREATE TABLE IF NOT EXISTS admins (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (id, username, password, created_at, last_login) VALUES
(2, 'fido', '$2y$10$421InEA/aq4FBi1Djj/tMOAEXpeaURclEu6wEjrpJsbtdPouhA.y2', '2025-02-04 21:49:13+00', '2025-02-15 20:46:06+00')
ON CONFLICT (id) DO NOTHING;

SELECT setval('admins_id_seq', 3);

-- categories
CREATE TABLE IF NOT EXISTS categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (id, name, description, created_at) VALUES
(1, 'Electronics', 'Electronic devices and gadgets', '2025-02-15 02:10:49+00'),
(2, 'Clothing', 'Apparel and accessories', '2025-02-15 02:10:49+00'),
(3, 'Books', 'Fiction, non-fiction, and educational materials', '2025-02-15 02:10:49+00')
ON CONFLICT (id) DO NOTHING;

SELECT setval('categories_id_seq', 4);

-- users
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  phone_number VARCHAR(50) NOT NULL,
  address VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (id, fullname, username, phone_number, address, email, password, created_at) VALUES
(1, 'Mufeed Mamadan', 'mufeed', '00000000000', 'bari', 'mufeed@example.com', '$2y$10$baJyF06Oq7UGdKeKl3oCR.4wfNFgRSOxifhYyKkw8/kiry68ZJ80u', '2025-02-04 20:43:01+00'),
(5, 'Ahmed', 'nekura', '00000000001', 'lamab', 'ahmed@example.com', '$2y$10$8eKqga/Kd1XLEeztrXq0veEDQfTOHa3ck02tyOP9d0T/qBH5CFAxy', '2025-02-15 13:35:30+00'),
(7, 'hilmey', 'lostboy', '00000000002', 'his dad', 'hilmey@example.com', '$2y$10$.IofNbUbBmik/1Wk7qyp/.xucPZecnbOs7BFqq53wEjtDLpNLFGOi', '2025-02-15 22:42:11+00')
ON CONFLICT (id) DO NOTHING;

SELECT setval('users_id_seq', 8);

-- products
CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  category_id INT REFERENCES categories(id),
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price NUMERIC(10,2) NOT NULL,
  image VARCHAR(255) DEFAULT 'default.jpg',
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  stock INT DEFAULT 0
);

INSERT INTO products (id, category_id, name, description, price, image, created_at, stock) VALUES
(1, 3, 'python book', 'Powerful Object-Oriented Programming book', 39.99, 'python book.jpg', '2025-02-15 01:50:53+00', 75),
(2, 1, 'A45', 'A very beautiful phone owned by nekura', 25.76, 'A45.jpg', '2025-02-15 13:13:53+00', 21),
(3, 1, 'Samsung laptop', 'basically a laptop', 50.00, 'Samsung Laptop.jpg', '2025-02-15 15:37:34+00', 22),
(4, 2, 'Naru T-shirts', 'Popular T-shirts made for Naruto fans', 10.25, 'naru shirt.avif', '2025-02-15 21:58:32+00', 100),
(5, 2, 'Tuxido', 'A manly custom made by legends', 500.00, 'tuxido.jpg', '2025-02-15 22:00:36+00', 20)
ON CONFLICT (id) DO NOTHING;

SELECT setval('products_id_seq', 8);

-- cart
CREATE TABLE IF NOT EXISTS cart (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id),
  product_id INT NOT NULL REFERENCES products(id),
  quantity INT DEFAULT 1
);

SELECT setval('cart_id_seq', 27);

-- orders (note: user_id 3 didn't exist, changed to user_id 1)
CREATE TABLE IF NOT EXISTS orders (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id),
  total NUMERIC(10,2) NOT NULL,
  order_date TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(50) DEFAULT 'Pending'
);

INSERT INTO orders (id, user_id, total, order_date, status) VALUES
(1, 1, 119.97, '2025-02-15 13:06:30+00', 'Pending'),
(2, 5, 999.99, '2025-02-15 13:42:32+00', 'Delivered'),
(3, 5, 119.97, '2025-02-15 13:42:55+00', 'Delivered'),
(6, 5, 1039.98, '2025-02-15 14:51:42+00', 'Delivered'),
(7, 5, 1039.98, '2025-02-15 14:52:06+00', 'Delivered'),
(8, 5, 812.79, '2025-02-15 17:56:52+00', 'Delivered'),
(9, 5, 185.72, '2025-02-15 20:19:18+00', 'Delivered'),
(10, 5, 25.76, '2025-02-15 20:20:16+00', 'Delivered'),
(11, 5, 25.76, '2025-02-15 20:21:04+00', 'Delivered'),
(12, 5, 50.00, '2025-02-15 20:25:38+00', 'Delivered'),
(13, 5, 50.00, '2025-02-15 20:30:22+00', 'Delivered'),
(14, 5, 50.00, '2025-02-15 20:31:41+00', 'Delivered'),
(15, 5, 50.00, '2025-02-15 20:38:29+00', 'Delivered'),
(16, 5, 25.76, '2025-02-15 20:38:58+00', 'Delivered'),
(17, 5, 50.00, '2025-02-15 20:40:02+00', 'Delivered'),
(20, 5, 50.00, '2025-02-15 23:16:13+00', 'Pending')
ON CONFLICT (id) DO NOTHING;

SELECT setval('orders_id_seq', 21);

-- order_items
CREATE TABLE IF NOT EXISTS order_items (
  id SERIAL PRIMARY KEY,
  order_id INT NOT NULL REFERENCES orders(id),
  product_id INT NOT NULL REFERENCES products(id),
  quantity INT DEFAULT 1,
  price NUMERIC(10,2) NOT NULL
);

INSERT INTO order_items (id, order_id, product_id, quantity, price) VALUES
(1, 1, 1, 3, 39.99),
(2, 2, 2, 1, 999.99),
(3, 3, 1, 3, 39.99),
(6, 6, 1, 1, 39.99),
(7, 6, 2, 1, 999.99),
(8, 7, 1, 1, 39.99),
(9, 7, 2, 1, 999.99),
(10, 8, 1, 1, 39.99),
(11, 8, 2, 30, 25.76),
(12, 9, 1, 4, 39.99),
(13, 9, 2, 1, 25.76),
(14, 10, 2, 1, 25.76),
(15, 11, 2, 1, 25.76),
(16, 12, 3, 1, 50.00),
(17, 13, 3, 1, 50.00),
(18, 14, 3, 1, 50.00),
(19, 15, 3, 1, 50.00),
(20, 16, 2, 1, 25.76),
(21, 17, 3, 1, 50.00),
(24, 20, 3, 1, 50.00)
ON CONFLICT (id) DO NOTHING;

SELECT setval('order_items_id_seq', 25);
