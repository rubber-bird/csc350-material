-- 01 - Creating tables: reference solution

-- Task 1
CREATE TABLE students (
  id   INT,
  name VARCHAR(100)
);

-- Task 2
CREATE TABLE courses (
  id      INT,
  title   VARCHAR(200),
  credits INT
);

-- Task 3
CREATE TABLE scratch (x INT);
DROP TABLE scratch;

-- Task 4
ALTER TABLE students ADD COLUMN email VARCHAR(255);

-- Task 5
CREATE TABLE employees (id INT, name VARCHAR(100), salary INT, fax_number VARCHAR(20));
ALTER TABLE employees DROP COLUMN fax_number;

-- Task 6
CREATE TABLE rooms (id INT, building VARCHAR(50), capacity INT);
RENAME TABLE rooms TO classrooms;

-- Task 7
CREATE TABLE students_archive LIKE students;

-- Task 8
INSERT INTO courses (id, title, credits) VALUES (1, 'Databases', 4), (2, 'Networks', 3), (3, 'Algorithms', 4);
CREATE TABLE course_titles AS SELECT id, title FROM courses;

-- Task 9
CREATE TABLE tweets (id INT, body VARCHAR(140));
ALTER TABLE tweets MODIFY body VARCHAR(280);
