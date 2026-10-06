-- ============================================================
--  05 - Indexes
-- ============================================================
--
--  Without an index, WHERE last_name = 'Lovelace' reads every row of the
--  table. With an index on last_name, the database jumps straight to the
--  matching rows, like the index at the back of a book. Indexes make
--  reads fast and writes a little slower, and they take space, so you
--  add them where queries need them: columns you filter, join or sort on.
--
--  The shapes:
--    CREATE INDEX idx_name ON table_name (column);
--    CREATE UNIQUE INDEX idx_name ON table_name (column);
--    CREATE INDEX idx_name ON table_name (first_column, second_column);
--    DROP INDEX idx_name ON table_name;
--
--  Name your indexes: idx_<table>_<columns>. The tests look them up by
--  the names the tasks give.
--
--  You will need (click for the manual):
--    CREATE INDEX      https://mariadb.com/kb/en/create-index/
--    DROP INDEX        https://mariadb.com/kb/en/drop-index/
--    Composite index   https://mariadb.com/kb/en/getting-started-with-indexes/#composite-index
--    Prefix index      https://mariadb.com/kb/en/create-index/#index-prefixes
--    FULLTEXT          https://mariadb.com/kb/en/full-text-index-overview/
--
--  The tables below are created and filled for you. Leave them alone.
--
-- ============================================================

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

-- Somebody added this years ago. No query uses it any more.
CREATE INDEX idx_customers_city_old ON customers (city);


-- ---------------------------------------------- EASY --------

-- Task 1: a plain index
--
-- Queries often filter customers by last name. Create an index named
-- `idx_customers_last_name` on customers(last_name).

-- your code here


-- Task 2: a unique index
--
-- No two customers may share an email address. Create a UNIQUE index
-- named `idx_customers_email` on customers(email). Inserting a second
-- 'ada@example.org' must then be rejected.

-- your code here


-- Task 3: index the join column
--
-- orders.customer_id is used in every join to customers and in every
-- "orders of customer X" query. Create `idx_orders_customer_id` on it.
-- (A FOREIGN KEY would have created this index for you. This table has
-- none, so you do it by hand.)

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: two columns, in the right order
--
-- The customer list is sorted by last name, then first name, and searched
-- by last name alone. One index on (last_name, first_name), in that
-- order, serves both. Name it `idx_customers_name`.
--
-- Order matters: an index on (a, b) helps queries on a, and on a and b,
-- but not queries on b alone.

-- your code here


-- Task 5: an index for a specific query
--
-- The most common query is:
--   SELECT * FROM orders WHERE status = 'pending' ORDER BY placed_at;
-- Create the one index that lets the database find the status AND read
-- the rows already in placed_at order. Name it `idx_orders_status_placed_at`.

-- your code here


-- Task 6: drop an index
--
-- `idx_customers_city_old` (created for you at the top) is dead weight:
-- every insert into customers pays to maintain it. Drop it.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: indexes inside CREATE TABLE
--
-- Create `sessions` with:
--   token       CHAR(64), the primary key
--   user_id     INT, required
--   expires_at  DATETIME, required
-- and two indexes declared inside the CREATE TABLE itself:
--   idx_sessions_user_id     on user_id     (find all sessions of a user)
--   idx_sessions_expires_at  on expires_at  (delete expired sessions)
-- Look up the INDEX clause of CREATE TABLE.

-- your code here


-- Task 8: index only the start of a long column
--
-- Titles are up to 200 characters, but the first 20 are enough to tell
-- them apart. Create `idx_articles_title` on the first 20 characters of
-- articles(title). This is a prefix index; it is smaller and just as
-- useful for WHERE title LIKE 'Why%'.

-- your code here


-- Task 9: full-text search
--
-- WHERE body LIKE '%index%' cannot use a normal index at all. Create a
-- FULLTEXT index named `ft_articles_body` on articles(body), so that
--   SELECT * FROM articles WHERE MATCH(body) AGAINST('database')
-- works and finds the articles that mention a database.

-- your code here
