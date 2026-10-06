-- 05 - Indexes: reference solution

CREATE TABLE customers (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  city       VARCHAR(100)
);
INSERT INTO customers (email, first_name, last_name, city) VALUES
  ('ada@example.org', 'Ada', 'Lovelace', 'London'),
  ('alan@example.org', 'Alan', 'Turing', 'Manchester'),
  ('grace@example.org', 'Grace', 'Hopper', 'New York');

CREATE TABLE orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  status      VARCHAR(20) NOT NULL,
  placed_at   DATETIME NOT NULL,
  total       DECIMAL(10,2) NOT NULL
);
INSERT INTO orders (customer_id, status, placed_at, total) VALUES
  (1, 'shipped', '2024-01-05 10:00:00', 25.00),
  (1, 'pending', '2024-02-01 09:30:00', 12.50),
  (2, 'shipped', '2024-01-20 16:45:00', 99.99);

CREATE TABLE articles (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  body  TEXT NOT NULL
);
INSERT INTO articles (title, body) VALUES
  ('Why indexes matter', 'A database without indexes reads every row. A database with the right index reads only what it needs.'),
  ('Choosing a primary key', 'Every table needs a primary key. Most of the time an auto-numbered integer is the right choice.');

CREATE INDEX idx_customers_city_old ON customers (city);

-- Task 1
CREATE INDEX idx_customers_last_name ON customers (last_name);

-- Task 2
CREATE UNIQUE INDEX idx_customers_email ON customers (email);

-- Task 3
CREATE INDEX idx_orders_customer_id ON orders (customer_id);

-- Task 4
CREATE INDEX idx_customers_name ON customers (last_name, first_name);

-- Task 5
CREATE INDEX idx_orders_status_placed_at ON orders (status, placed_at);

-- Task 6
DROP INDEX idx_customers_city_old ON customers;

-- Task 7
CREATE TABLE sessions (
  token      CHAR(64) PRIMARY KEY,
  user_id    INT NOT NULL,
  expires_at DATETIME NOT NULL,
  INDEX idx_sessions_user_id (user_id),
  INDEX idx_sessions_expires_at (expires_at)
);

-- Task 8
CREATE INDEX idx_articles_title ON articles (title(20));

-- Task 9
CREATE FULLTEXT INDEX ft_articles_body ON articles (body);
