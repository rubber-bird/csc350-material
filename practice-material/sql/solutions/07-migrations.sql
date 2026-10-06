-- 07 - Migrations: reference solution

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

-- Task 1
ALTER TABLE people
  ADD COLUMN first_name VARCHAR(100) NULL,
  ADD COLUMN last_name  VARCHAR(100) NULL;
UPDATE people SET
  first_name = SUBSTRING_INDEX(full_name, ' ', 1),
  last_name  = SUBSTRING_INDEX(full_name, ' ', -1);
ALTER TABLE people
  MODIFY first_name VARCHAR(100) NOT NULL,
  MODIFY last_name  VARCHAR(100) NOT NULL,
  DROP COLUMN full_name;

-- Task 2
UPDATE people SET email = CONCAT(LOWER(first_name), '@example.org') WHERE email IS NULL;
ALTER TABLE people MODIFY email VARCHAR(255) NOT NULL;

-- Task 3
UPDATE tickets SET status = LOWER(TRIM(status));
UPDATE tickets SET status = 'in_progress' WHERE status = 'in progress';
ALTER TABLE tickets MODIFY status ENUM('open', 'in_progress', 'closed') NOT NULL;

-- Task 4
ALTER TABLE posts ADD COLUMN slug VARCHAR(200) NULL;
UPDATE posts SET slug = LOWER(REPLACE(title, ' ', '-'));
ALTER TABLE posts MODIFY slug VARCHAR(200) NOT NULL, ADD UNIQUE INDEX idx_posts_slug (slug);

-- Task 5
DELETE s FROM subscribers s JOIN subscribers t ON s.email = t.email AND s.id > t.id;
CREATE UNIQUE INDEX idx_subscribers_email ON subscribers (email);

-- Task 6
CREATE TABLE customers (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE
);
INSERT INTO customers (name, email)
  SELECT DISTINCT customer_name, customer_email FROM orders;
ALTER TABLE orders ADD COLUMN customer_id INT NULL;
UPDATE orders o JOIN customers c ON c.email = o.customer_email SET o.customer_id = c.id;
ALTER TABLE orders
  MODIFY customer_id INT NOT NULL,
  ADD CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers (id),
  DROP COLUMN customer_name,
  DROP COLUMN customer_email;

-- Task 7
ALTER TABLE comments DROP FOREIGN KEY fk_comments_post;
ALTER TABLE posts    MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE comments MODIFY post_id BIGINT UNSIGNED NOT NULL;
ALTER TABLE comments
  ADD CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE;

-- Task 8
ALTER TABLE loans ADD CONSTRAINT chk_loans_dates CHECK (due_on >= borrowed_on);
ALTER TABLE loans ADD COLUMN days_out INT AS (DATEDIFF(due_on, borrowed_on)) STORED;
