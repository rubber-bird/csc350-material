-- ============================================================
--  01 - Creating tables
-- ============================================================
--
--  Write the SQL for each task under its comment, where it says
--  "your code here". This file is run from top to bottom against a
--  fresh, empty database every time you refresh the results page, so
--  nothing you create survives between runs and nothing can go stale.
--
--  Run the tests:   open http://localhost/sql-fundamentals/
--                   (or in a terminal:  php run.php 01)
--
--  Statements you will need (click for the manual):
--    CREATE TABLE      https://mariadb.com/kb/en/create-table/
--    DROP TABLE        https://mariadb.com/kb/en/drop-table/
--    ALTER TABLE       https://mariadb.com/kb/en/alter-table/
--    RENAME TABLE      https://mariadb.com/kb/en/rename-table/
--    Data types        https://mariadb.com/kb/en/data-types/
--
--  Every statement ends with a semicolon. Lines that start with -- are
--  comments and are ignored. SQL keywords are not case sensitive, but
--  table and column names must be spelled exactly as the task says.
--
-- ============================================================

-- ---------------------------------------------- EASY --------

-- Task 1: students
--
-- Create a table called `students` with two columns:
--   id     a whole number             INT
--   name   text, up to 100 characters VARCHAR(100)
--
-- This is the shape of a CREATE TABLE, using a different table:
--   CREATE TABLE pets (
--     id       INT,
--     nickname VARCHAR(50)
--   );

-- your code here


-- Task 2: courses
--
-- Create a table called `courses` with three columns:
--   id       INT
--   title    VARCHAR(200)
--   credits  INT

-- your code here


-- Task 3: drop a table
--
-- The next line creates a table nobody wants. Leave that line alone and
-- write the statement that removes the table again.
CREATE TABLE scratch (x INT);

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: add a column
--
-- Add a column called `email` of type VARCHAR(255) to the `students`
-- table from task 1. Do not create the table again: change it.

-- your code here


-- Task 5: drop a column
--
-- The next line creates an `employees` table. Nobody has a fax any more.
-- Remove the `fax_number` column and keep the other three.
CREATE TABLE employees (id INT, name VARCHAR(100), salary INT, fax_number VARCHAR(20));

-- your code here


-- Task 6: rename a table
--
-- The next line creates a table with a name nobody likes. Rename it to
-- `classrooms`. The columns must stay exactly as they are.
CREATE TABLE rooms (id INT, building VARCHAR(50), capacity INT);

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: copy a structure
--
-- Create `students_archive` with exactly the same columns as `students`
-- has by now (after task 4), without typing the columns again.
-- Look up:  CREATE TABLE ... LIKE

-- your code here


-- Task 8: create a table from a query
--
-- The next line puts three rows into `courses`. Create a table called
-- `course_titles` from a query, so that it has only the `id` and `title`
-- columns of `courses` and contains those three rows.
-- Look up:  CREATE TABLE ... AS SELECT
INSERT INTO courses (id, title, credits) VALUES (1, 'Databases', 4), (2, 'Networks', 3), (3, 'Algorithms', 4);

-- your code here


-- Task 9: widen a column
--
-- The next line creates a `tweets` table where `body` holds 140
-- characters. The limit went up. Make `body` a VARCHAR(280) without
-- dropping the column or the table.
-- Look up:  ALTER TABLE ... MODIFY
CREATE TABLE tweets (id INT, body VARCHAR(140));

-- your code here
