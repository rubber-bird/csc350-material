-- ============================================================
--  03 - Primary keys
-- ============================================================
--
--  A primary key is the column (or columns) that identify one row: no
--  two rows may share it, and it can never be NULL. Every table you
--  design should have one.
--
--  Two ways to write it. On the column:
--    id INT PRIMARY KEY
--  Or as its own line, which is the only way for more than one column:
--    PRIMARY KEY (student_id, course_id)
--
--  You will need (click for the manual):
--    PRIMARY KEY       https://mariadb.com/kb/en/getting-started-with-indexes/#primary-key
--    AUTO_INCREMENT    https://mariadb.com/kb/en/auto_increment/
--    UNIQUE            https://mariadb.com/kb/en/getting-started-with-indexes/#unique-index
--    ALTER TABLE       https://mariadb.com/kb/en/alter-table/
--
-- ============================================================

-- ---------------------------------------------- EASY --------

-- Task 1: a simple key
--
-- Create `users` with:
--   id        INT, the primary key
--   username  VARCHAR(50)
--
-- Two users with the same id must be rejected. So must a NULL id.

-- your code here


-- Task 2: let the database number the rows
--
-- Create `posts` with:
--   id     INT, the primary key, numbered automatically by the database
--   title  VARCHAR(200)
--
-- Then INSERT INTO posts (title) VALUES ('Hello') must work with no id
-- given, and each new post gets the next number.

-- your code here


-- Task 3: a natural key
--
-- Create `countries` with:
--   code   exactly 2 characters, the primary key ('US', 'FR', ...)
--   name   VARCHAR(100)
--
-- Not every key is a number. When a real-world value is short, fixed and
-- unique, it can be the key itself.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: a composite key
--
-- Create `enrollments` with:
--   student_id  INT
--   course_id   INT
-- The primary key is BOTH columns together, in that order. A student may
-- be in many courses and a course has many students, but the same student
-- in the same course twice must be rejected.

-- your code here


-- Task 5: primary key versus UNIQUE
--
-- Create `emails` with:
--   id       INT, the primary key, auto-numbered
--   address  VARCHAR(255), required, and no two rows may share it
--
-- A table has one primary key, but any column can be declared UNIQUE.

-- your code here


-- Task 6: add a key to an existing table
--
-- The next lines create `legacy` without a primary key and fill it.
-- Add a primary key on `id` with ALTER TABLE. Do not recreate the table:
-- the three rows must survive.
CREATE TABLE legacy (id INT NOT NULL, note VARCHAR(100));
INSERT INTO legacy (id, note) VALUES (1, 'one'), (2, 'two'), (3, 'three');

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: a three-column key
--
-- Create `seats` with:
--   flight_no    CHAR(6)
--   seat_row     TINYINT UNSIGNED
--   seat_letter  CHAR(1)
--   passenger    VARCHAR(100)
-- One seat on one flight is identified by flight, row and letter together,
-- in that order. Seat 12A on flight BA0117 can be sold once.

-- your code here


-- Task 8: start counting at 1000
--
-- Create `tickets` with:
--   id     BIGINT UNSIGNED, primary key, auto-numbered starting at 1000
--   event  VARCHAR(100)
-- The first ticket ever inserted must get id 1000.
-- Look up the AUTO_INCREMENT table option (it goes after the closing
-- parenthesis of CREATE TABLE).

-- your code here


-- Task 9: replace a bad key
--
-- The next lines create `bad_pk`, where somebody made `name` the primary
-- key. Two people can share a name, so that was wrong. Fix the table with
-- ONE ALTER TABLE statement: drop the primary key, and add a new first
-- column `id` INT that is auto-numbered and is the new primary key. The
-- three rows must survive and receive ids 1, 2 and 3.
CREATE TABLE bad_pk (name VARCHAR(100) NOT NULL, PRIMARY KEY (name));
INSERT INTO bad_pk (name) VALUES ('Ada'), ('Alan'), ('Grace');

-- your code here
