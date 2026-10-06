-- ============================================================
--  07 - Migrations: changing tables that already hold data
-- ============================================================
--
--  Creating an empty table is the easy part. Real schemas change while
--  they are full of rows, and then every change is two jobs: change the
--  structure, and fix the data so it fits. Often the order matters: you
--  cannot make a column NOT NULL while it still contains NULLs, and you
--  cannot add a UNIQUE index while there are duplicates.
--
--  Every task below starts from a table that is created and filled for
--  you. Leave those lines alone; write your statements under each task.
--  The rows must survive unless the task says otherwise.
--
--  You will need (click for the manual):
--    ALTER TABLE          https://mariadb.com/kb/en/alter-table/
--    UPDATE               https://mariadb.com/kb/en/update/
--    UPDATE with JOIN     https://mariadb.com/kb/en/update/#multiple-tables
--    DELETE with JOIN     https://mariadb.com/kb/en/delete/#multiple-table-deletes
--    INSERT ... SELECT    https://mariadb.com/kb/en/insert-select/
--    String functions     https://mariadb.com/kb/en/string-functions/
--      SUBSTRING_INDEX, TRIM, LOWER, REPLACE, CONCAT
--    Generated columns    https://mariadb.com/kb/en/generated-columns/
--    CHECK constraints    https://mariadb.com/kb/en/constraint/#check-constraints
--
-- ============================================================

CREATE TABLE people (id INT AUTO_INCREMENT PRIMARY KEY, full_name VARCHAR(200) NOT NULL, email VARCHAR(255) NULL);
INSERT INTO people (full_name, email) VALUES
  ('Ada Lovelace', 'ada@example.org'),
  ('Alan Turing', NULL),
  ('Grace Hopper', 'grace@example.org');

CREATE TABLE tickets (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(200) NOT NULL, status VARCHAR(20) NOT NULL);
INSERT INTO tickets (title, status) VALUES
  ('Login broken', 'open'),
  ('Typo on home page', 'Closed'),
  ('Slow search', 'In Progress'),
  ('Export fails', ' open'),
  ('Dark mode', 'CLOSED ');

CREATE TABLE posts (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(200) NOT NULL);
INSERT INTO posts (title) VALUES ('Hello World'), ('Second Post');

CREATE TABLE subscribers (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL);
INSERT INTO subscribers (email) VALUES
  ('ada@example.org'), ('alan@example.org'), ('ada@example.org'),
  ('grace@example.org'), ('alan@example.org'), ('ada@example.org');

CREATE TABLE orders (id INT AUTO_INCREMENT PRIMARY KEY, customer_name VARCHAR(100) NOT NULL, customer_email VARCHAR(255) NOT NULL, total DECIMAL(10,2) NOT NULL);
INSERT INTO orders (customer_name, customer_email, total) VALUES
  ('Ada Lovelace', 'ada@example.org', 25.00),
  ('Alan Turing', 'alan@example.org', 99.99),
  ('Ada Lovelace', 'ada@example.org', 12.50),
  ('Grace Hopper', 'grace@example.org', 7.00);

CREATE TABLE comments (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  body    TEXT NOT NULL,
  CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
);
INSERT INTO comments (post_id, body) VALUES (1, 'First!'), (1, 'Second'), (2, 'Nice');

CREATE TABLE loans (id INT AUTO_INCREMENT PRIMARY KEY, borrowed_on DATE NOT NULL, due_on DATE NOT NULL);
INSERT INTO loans (borrowed_on, due_on) VALUES ('2024-03-01', '2024-03-22'), ('2024-03-05', '2024-03-26');


-- ---------------------------------------------- EASY --------

-- Task 1: split a column
--
-- `people.full_name` holds "Ada Lovelace". Replace it with two columns,
-- `first_name` and `last_name`, both VARCHAR(100) NOT NULL, filled from
-- the existing names (the part before the space, the part after it).
-- Then drop `full_name`.
--
-- Hint: SUBSTRING_INDEX(full_name, ' ', 1) is the first word and
-- SUBSTRING_INDEX(full_name, ' ', -1) the last.

-- your code here


-- Task 2: make a column required
--
-- `people.email` allows NULL and one row has none. Make it NOT NULL.
-- The database will refuse while a NULL is still there, so first give
-- every person without an email the address
-- <first name in lower case>@example.org

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 3: from free text to a fixed list
--
-- `tickets.status` was typed by hand: 'open', 'Closed', ' open',
-- 'In Progress', 'CLOSED '. Clean the values up (trim spaces, lower
-- case, and 'in progress' becomes 'in_progress'), then change the column
-- to ENUM('open', 'in_progress', 'closed') NOT NULL.
-- Afterwards there are 2 open, 1 in_progress and 2 closed tickets.

-- your code here


-- Task 4: add a derived column
--
-- Add `slug` VARCHAR(200) NOT NULL UNIQUE to `posts`, filled from the
-- title in lower case with spaces replaced by dashes:
-- 'Hello World' -> 'hello-world'. Add it nullable, fill it, then make
-- it NOT NULL and UNIQUE.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 5: remove duplicates, then forbid them
--
-- `subscribers` has the same email several times. Delete the duplicates
-- so that only the row with the LOWEST id survives for each email, then
-- add a UNIQUE index named `idx_subscribers_email` on email.
-- Three rows remain: ids 1, 2 and 4.
--
-- Hint: a DELETE can join a table to itself:
--   DELETE s FROM subscribers s JOIN subscribers t ON ... AND s.id > t.id;

-- your code here


-- Task 6: pull repeated data into its own table
--
-- `orders` repeats the customer's name and email on every row. Fix it:
--   1. Create `customers` (id INT AUTO_INCREMENT PRIMARY KEY,
--      name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE).
--   2. Fill it with one row per distinct email from orders
--      (INSERT ... SELECT DISTINCT).
--   3. Add `customer_id` INT to orders, fill it by matching emails
--      (UPDATE orders JOIN customers ON ...), then make it NOT NULL.
--   4. Add a foreign key from orders.customer_id to customers(id).
--   5. Drop customer_name and customer_email from orders.
-- Ada's two orders must end up pointing at the same customer row.

-- your code here


-- Task 7: change the type of a key that a foreign key points at
--
-- `posts.id` and `comments.post_id` are INT. They must become
-- BIGINT UNSIGNED. The foreign key `fk_comments_post` will not let you
-- change either column while it exists: drop it, change both columns,
-- and add it back with the same name and ON DELETE CASCADE. All three
-- comments must survive.

-- your code here


-- Task 8: a rule between two columns, and a computed column
--
-- A loan can never be due before it was borrowed. Add a CHECK constraint
-- named `chk_loans_dates` to `loans` that enforces due_on >= borrowed_on.
-- Then add a generated column `days_out` INT, computed as
-- DATEDIFF(due_on, borrowed_on) and STORED, so nobody has to remember
-- the formula. Both existing loans have days_out = 21.

-- your code here
