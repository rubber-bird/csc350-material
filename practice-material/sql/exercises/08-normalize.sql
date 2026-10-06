-- ============================================================
--  08 - Normalizing a flat table
-- ============================================================
--
--  Somebody kept the shop's sales in one spreadsheet, and that
--  spreadsheet is now the table `sales_flat` below: one row per line
--  item, with the customer and the product typed out again on every
--  row. Your job is to design proper tables, move the data across, and
--  finally drop the spreadsheet.
--
--  Read all of sales_flat first. Notice that a product's price is not
--  always the same: the Mouse was 19.50 in January and 21.00 in
--  February. An order must remember what was paid at the time, and the
--  product table holds the current (most recent) price.
--
--  You will need, on top of the last module:
--    CREATE VIEW          https://mariadb.com/kb/en/create-view/
--    Subqueries           https://mariadb.com/kb/en/subqueries/
--    GROUP BY, SUM, MAX   https://mariadb.com/kb/en/aggregate-functions/
--    Generated columns    https://mariadb.com/kb/en/generated-columns/
--
-- ============================================================

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


-- Task 1: customers
--
-- Create `customers`:
--   id     INT AUTO_INCREMENT PRIMARY KEY
--   email  VARCHAR(255) NOT NULL UNIQUE
--   name   VARCHAR(100) NOT NULL
--   city   VARCHAR(100) NULL
-- and fill it from sales_flat: one row per distinct email (3 rows).

-- your code here


-- Task 2: products
--
-- Create `products`:
--   id          INT AUTO_INCREMENT PRIMARY KEY
--   sku         CHAR(8) NOT NULL UNIQUE
--   name        VARCHAR(200) NOT NULL
--   unit_price  DECIMAL(8,2) NOT NULL   (the CURRENT price)
-- and fill it with one row per SKU (4 rows). The current price is the
-- one on the most recent order of that product: the Mouse is 21.00.
--
-- Hint: a subquery can find the latest date per SKU:
--   WHERE ordered_on = (SELECT MAX(ordered_on) FROM sales_flat s2
--                       WHERE s2.product_sku = s1.product_sku)

-- your code here


-- Task 3: orders
--
-- Create `orders`:
--   id           INT AUTO_INCREMENT PRIMARY KEY
--   ref          VARCHAR(10) NOT NULL UNIQUE
--   customer_id  INT NOT NULL, foreign key to customers(id)
--                (a customer with orders cannot be deleted)
--   ordered_on   DATE NOT NULL
-- and fill it: one row per distinct order_ref (4 rows), joined to the
-- right customer by email.

-- your code here


-- Task 4: order_items
--
-- Create `order_items`:
--   order_id    INT, foreign key to orders(id), ON DELETE CASCADE
--   product_id  INT, foreign key to products(id)
--   quantity    INT UNSIGNED NOT NULL, with a CHECK that it is above 0
--   unit_price  DECIMAL(8,2) NOT NULL   (the price paid on THAT order)
--   line_total  DECIMAL(10,2), a STORED generated column: quantity * unit_price
--   primary key (order_id, product_id)
-- and fill it from sales_flat (8 rows). Do not insert line_total: the
-- database computes it.

-- your code here


-- Task 5: a reporting view
--
-- Create a view named `order_totals` with one row per order:
--   ref, customer_name, ordered_on, total   (total = SUM of line_total)
-- ORD-1001 comes to 88.00 and ORD-1004 to 449.98.

-- your code here


-- Task 6: the index the reports need
--
-- "Orders of customer X, newest first" is the most common query.
-- Add an index named `idx_orders_customer_ordered_on` on orders over
-- (customer_id, ordered_on).

-- your code here


-- Task 7: retire the spreadsheet
--
-- Everything now lives in proper tables. Drop `sales_flat`.

-- your code here
