-- 04 - Foreign keys: reference solution

CREATE TABLE authors (id INT PRIMARY KEY, name VARCHAR(100) NOT NULL);
INSERT INTO authors VALUES (1, 'Ursula K. Le Guin'), (2, 'Octavia Butler');

CREATE TABLE customers (id INT PRIMARY KEY, name VARCHAR(100) NOT NULL);
INSERT INTO customers VALUES (1, 'Ada'), (2, 'Alan');

CREATE TABLE countries (code CHAR(2) PRIMARY KEY, name VARCHAR(100) NOT NULL);
INSERT INTO countries VALUES ('US', 'United States'), ('FR', 'France');

CREATE TABLE enrollments (student_id INT, course_id INT, PRIMARY KEY (student_id, course_id));
INSERT INTO enrollments VALUES (1, 1), (1, 2);

CREATE TABLE tags (id INT PRIMARY KEY, name VARCHAR(50) NOT NULL);
INSERT INTO tags VALUES (1, 'sci-fi'), (2, 'classic');

-- Task 1
CREATE TABLE books (
  id        INT PRIMARY KEY,
  title     VARCHAR(200) NOT NULL,
  author_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES authors (id)
);

-- Task 2
CREATE TABLE reviews (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  stars   TINYINT UNSIGNED NOT NULL,
  FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE
);

-- Task 3
CREATE TABLE profiles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NULL,
  nickname    VARCHAR(50),
  FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE SET NULL
);

-- Task 4
CREATE TABLE orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  total       DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers (id)
);

-- Task 5
CREATE TABLE cities (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100) NOT NULL,
  country_code CHAR(2) NOT NULL,
  FOREIGN KEY (country_code) REFERENCES countries (code) ON UPDATE CASCADE
);

-- Task 6
CREATE TABLE payments (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, amount DECIMAL(10,2) NOT NULL);
ALTER TABLE payments
  ADD CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders (id);

-- Task 7
CREATE TABLE employees (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  manager_id INT NULL,
  FOREIGN KEY (manager_id) REFERENCES employees (id) ON DELETE SET NULL
);

-- Task 8
CREATE TABLE grades (
  student_id INT,
  course_id  INT,
  grade      CHAR(2) NOT NULL,
  PRIMARY KEY (student_id, course_id),
  FOREIGN KEY (student_id, course_id) REFERENCES enrollments (student_id, course_id) ON DELETE CASCADE
);

-- Task 9
CREATE TABLE book_tags (
  book_id INT,
  tag_id  INT,
  PRIMARY KEY (book_id, tag_id),
  FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
);
