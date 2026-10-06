-- ============================================================
--  04 - Foreign keys
-- ============================================================
--
--  A foreign key says: the value in this column must exist as a primary
--  key in that other table. The database then refuses orphans (a book
--  whose author does not exist) and decides what happens to the children
--  when a parent row is deleted or its key changes.
--
--  The shape, as its own line in the CREATE TABLE:
--    FOREIGN KEY (author_id) REFERENCES authors (id)
--  With a name, and rules for what happens to children:
--    CONSTRAINT fk_books_author
--      FOREIGN KEY (author_id) REFERENCES authors (id)
--      ON DELETE CASCADE ON UPDATE CASCADE
--
--  The rules:
--    RESTRICT   refuse to delete/update a parent that has children (default)
--    CASCADE    delete/update the children too
--    SET NULL   set the children's column to NULL (it must allow NULL)
--
--  You will need (click for the manual):
--    FOREIGN KEY   https://mariadb.com/kb/en/foreign-keys/
--    ALTER TABLE   https://mariadb.com/kb/en/alter-table/#add-constraint
--
--  The parent tables below are created for you. Leave them alone.
--
-- ============================================================

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


-- ---------------------------------------------- EASY --------

-- Task 1: books belong to authors
--
-- Create `books` with:
--   id         INT, primary key
--   title      VARCHAR(200), required
--   author_id  INT, required, a foreign key to authors(id)
--
-- A book with author_id 999 must be rejected. Deleting an author who
-- still has books must be rejected too (that is the default rule).

-- your code here


-- Task 2: delete the children too
--
-- Create `reviews` with:
--   id       INT, auto-numbered primary key
--   book_id  INT, required, foreign key to books(id)
--   stars    TINYINT UNSIGNED, required
-- When a book is deleted, its reviews must disappear with it.

-- your code here


-- Task 3: keep the children, forget the parent
--
-- Create `profiles` with:
--   id           INT, auto-numbered primary key
--   customer_id  INT, optional, foreign key to customers(id)
--   nickname     VARCHAR(50)
-- When a customer is deleted, their profile stays but its customer_id
-- becomes NULL.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: name the constraint
--
-- Create `orders` with:
--   id           INT, auto-numbered primary key
--   customer_id  INT, required
--   total        DECIMAL(10,2), required
-- The foreign key from customer_id to customers(id) must be named
-- `fk_orders_customer`. Names matter: they show up in error messages and
-- you need them to drop or change the constraint later.

-- your code here


-- Task 5: follow a changed key
--
-- Create `cities` with:
--   id            INT, auto-numbered primary key
--   name          VARCHAR(100), required
--   country_code  CHAR(2), required, foreign key to countries(code)
-- Country codes occasionally change. When a country's code is updated,
-- its cities must follow automatically.

-- your code here


-- Task 6: add a foreign key to an existing table
--
-- The next line creates `payments` without any foreign key. Add one
-- with ALTER TABLE: order_id must reference orders(id), and the
-- constraint must be named `fk_payments_order`.
CREATE TABLE payments (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, amount DECIMAL(10,2) NOT NULL);

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: a table that points at itself
--
-- Create `employees` with:
--   id          INT, auto-numbered primary key
--   name        VARCHAR(100), required
--   manager_id  INT, optional, foreign key to employees(id)
-- A manager is also an employee. When a manager leaves, their reports
-- must stay and simply have no manager.

-- your code here


-- Task 8: a foreign key to a composite key
--
-- Create `grades` with:
--   student_id  INT
--   course_id   INT
--   grade       CHAR(2), required
-- The primary key is (student_id, course_id). The pair must also be a
-- foreign key to enrollments(student_id, course_id): you cannot grade a
-- student in a course they are not enrolled in. When an enrollment is
-- deleted, the grade goes with it.

-- your code here


-- Task 9: a many-to-many join table
--
-- Create `book_tags` with:
--   book_id  INT, foreign key to books(id)
--   tag_id   INT, foreign key to tags(id)
-- The primary key is both columns together. Deleting a book or a tag
-- must remove its rows here.

-- your code here
