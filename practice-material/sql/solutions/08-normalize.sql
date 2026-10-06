-- 08 - Normalizing a flat table: reference solution

CREATE TABLE sales_flat (
  order_ref      VARCHAR(10)   NOT NULL,
  ordered_on     DATE          NOT NULL,
  customer_email VARCHAR(255)  NOT NULL,
  customer_name  VARCHAR(100)  NOT NULL,
  customer_city  VARCHAR(100)  NULL,
  product_sku    CHAR(8)       NOT NULL,
  product_name   VARCHAR(200)  NOT NULL,
  unit_price     DECIMAL(8,2)  NOT NULL,
  quantity       INT           NOT NULL
);
INSERT INTO sales_flat VALUES
  ('ORD-1001', '2024-01-05', 'ada@example.org',   'Ada Lovelace', 'London',     'SKU-0001', 'Keyboard',  49.00, 1),
  ('ORD-1001', '2024-01-05', 'ada@example.org',   'Ada Lovelace', 'London',     'SKU-0002', 'Mouse',     19.50, 2),
  ('ORD-1002', '2024-01-20', 'alan@example.org',  'Alan Turing',  'Manchester', 'SKU-0003', 'Monitor',  189.99, 1),
  ('ORD-1002', '2024-01-20', 'alan@example.org',  'Alan Turing',  'Manchester', 'SKU-0004', 'Cable',      4.25, 3),
  ('ORD-1003', '2024-02-01', 'ada@example.org',   'Ada Lovelace', 'London',     'SKU-0004', 'Cable',      4.25, 10),
  ('ORD-1004', '2024-02-14', 'grace@example.org', 'Grace Hopper', NULL,         'SKU-0002', 'Mouse',     21.00, 1),
  ('ORD-1004', '2024-02-14', 'grace@example.org', 'Grace Hopper', NULL,         'SKU-0001', 'Keyboard',  49.00, 1),
  ('ORD-1004', '2024-02-14', 'grace@example.org', 'Grace Hopper', NULL,         'SKU-0003', 'Monitor',  189.99, 2);

-- Task 1
CREATE TABLE customers (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  name  VARCHAR(100) NOT NULL,
  city  VARCHAR(100) NULL
);
INSERT INTO customers (email, name, city)
  SELECT DISTINCT customer_email, customer_name, customer_city FROM sales_flat;

-- Task 2
CREATE TABLE products (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  sku        CHAR(8) NOT NULL UNIQUE,
  name       VARCHAR(200) NOT NULL,
  unit_price DECIMAL(8,2) NOT NULL
);
INSERT INTO products (sku, name, unit_price)
  SELECT DISTINCT product_sku, product_name, unit_price
  FROM sales_flat s1
  WHERE ordered_on = (SELECT MAX(ordered_on) FROM sales_flat s2 WHERE s2.product_sku = s1.product_sku);

-- Task 3
CREATE TABLE orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  ref         VARCHAR(10) NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  ordered_on  DATE NOT NULL,
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers (id)
);
INSERT INTO orders (ref, customer_id, ordered_on)
  SELECT DISTINCT f.order_ref, c.id, f.ordered_on
  FROM sales_flat f JOIN customers c ON c.email = f.customer_email;

-- Task 4
CREATE TABLE order_items (
  order_id   INT,
  product_id INT,
  quantity   INT UNSIGNED NOT NULL,
  unit_price DECIMAL(8,2) NOT NULL,
  line_total DECIMAL(10,2) AS (quantity * unit_price) STORED,
  PRIMARY KEY (order_id, product_id),
  CONSTRAINT fk_items_order   FOREIGN KEY (order_id)   REFERENCES orders (id) ON DELETE CASCADE,
  CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products (id),
  CONSTRAINT chk_items_quantity CHECK (quantity > 0)
);
INSERT INTO order_items (order_id, product_id, quantity, unit_price)
  SELECT o.id, p.id, f.quantity, f.unit_price
  FROM sales_flat f
  JOIN orders o   ON o.ref = f.order_ref
  JOIN products p ON p.sku = f.product_sku;

-- Task 5
CREATE VIEW order_totals AS
  SELECT o.ref, c.name AS customer_name, o.ordered_on, SUM(i.line_total) AS total
  FROM orders o
  JOIN customers c   ON c.id = o.customer_id
  JOIN order_items i ON i.order_id = o.id
  GROUP BY o.id, o.ref, c.name, o.ordered_on;

-- Task 6
CREATE INDEX idx_orders_customer_ordered_on ON orders (customer_id, ordered_on);

-- Task 7
DROP TABLE sales_flat;
