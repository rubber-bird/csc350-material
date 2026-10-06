-- ============================================================
--  11 - Conditional values, subqueries and text search
-- ============================================================
--
--  Three more tools for the SELECT list and the WHERE clause.
--
--  Conditional values pick what to show without any PHP:
--    COALESCE(a, b, 'fallback')      the first value that is not NULL
--    IF(condition, yes, no)          a two-way choice
--    CASE WHEN c1 THEN x WHEN c2 THEN y ELSE z END   several branches
--
--  A subquery is a SELECT inside another statement:
--    WHERE grade > (SELECT AVG(grade) FROM enrollments)     one value
--    WHERE student_id NOT IN (SELECT student_id FROM ...)   a list
--  UNION stacks two SELECTs with the same columns into one result.
--
--  FULLTEXT search finds words, not substrings. It needs an index:
--    ALTER TABLE t ADD FULLTEXT (col1, col2);
--    ... WHERE MATCH (col1, col2) AGAINST ('word')
--    ... WHERE MATCH (col1, col2) AGAINST ('+must -never' IN BOOLEAN MODE)
--  The columns in MATCH must be exactly the indexed columns.
--
--  HOW THE TASKS WORK: as in modules 09 and 10, each task asks for a VIEW
--  whose column names match the task exactly. ORDER BY goes in the view.
--
--  You will need (click for the manual):
--    COALESCE             https://dev.mysql.com/doc/refman/8.4/en/comparison-operators.html#function_coalesce
--    IF and CASE          https://dev.mysql.com/doc/refman/8.4/en/flow-control-functions.html
--    Subqueries           https://dev.mysql.com/doc/refman/8.4/en/subqueries.html
--    UNION                https://dev.mysql.com/doc/refman/8.4/en/union.html
--    FULLTEXT search      https://dev.mysql.com/doc/refman/8.4/en/fulltext-search.html
--    Boolean mode         https://dev.mysql.com/doc/refman/8.4/en/fulltext-boolean.html
--
--  The tables below are created and filled for you. Leave them alone.
--
-- ============================================================

CREATE TABLE departments (
  department_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(60) NOT NULL UNIQUE
);
INSERT INTO departments (department_id, name) VALUES
  (1, 'Computer Science'), (2, 'Mathematics'), (3, 'History'), (4, 'Music');

CREATE TABLE students (
  student_id    INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  first_name    VARCHAR(40) NOT NULL,
  last_name     VARCHAR(40) NOT NULL,
  email         VARCHAR(80) NULL,
  phone         VARCHAR(20) NULL,
  department_id INT UNSIGNED NULL,
  FOREIGN KEY (department_id) REFERENCES departments (department_id)
);
INSERT INTO students (student_id, first_name, last_name, email, phone, department_id) VALUES
  (1, 'Ada',   'Lovelace', 'ada@college.edu',   NULL,       1),
  (2, 'Alan',  'Turing',   NULL,                '555-0102', 1),
  (3, 'Grace', 'Hopper',   'grace@college.edu', '555-0103', 2),
  (4, 'Linus', 'Torvalds', NULL,                NULL,       1),
  (5, 'Emmy',  'Noether',  'emmy@college.edu',  NULL,       2),
  (6, 'Mary',  'Beard',    'mary@college.edu',  NULL,       3);

CREATE TABLE courses (
  course_id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code            CHAR(6) NOT NULL UNIQUE,
  title           VARCHAR(80) NOT NULL,
  description     TEXT NOT NULL,
  credits         TINYINT UNSIGNED NOT NULL,
  department_id   INT UNSIGNED NOT NULL,
  prerequisite_id INT UNSIGNED NULL,
  FOREIGN KEY (department_id)   REFERENCES departments (department_id),
  FOREIGN KEY (prerequisite_id) REFERENCES courses (course_id)
);
INSERT INTO courses (course_id, code, title, description, credits, department_id, prerequisite_id) VALUES
  (1, 'CSC101', 'Intro to Programming', 'Variables, loops and functions in Python.',                         3, 1, NULL),
  (2, 'CSC350', 'Software Development', 'Building a web application with PHP and a MySQL database.',        4, 1, 1),
  (3, 'CSC360', 'Database Systems',     'Designing relational databases, SQL queries and normalization.',   3, 1, 1),
  (4, 'MAT201', 'Linear Algebra',       'Vectors, matrices and linear transformations.',                    4, 2, NULL),
  (5, 'MAT301', 'Statistics',           'Probability, distributions and analysing data.',                   3, 2, 4),
  (6, 'HIS101', 'World History',        'From the first cities to the modern web of nations.',              3, 3, NULL),
  (7, 'CSC499', 'Senior Project',       'An independent software project with a faculty advisor.',          3, 1, 2);

CREATE TABLE enrollments (
  student_id INT UNSIGNED NOT NULL,
  course_id  INT UNSIGNED NOT NULL,
  grade      DECIMAL(4,1) NULL,
  PRIMARY KEY (student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES students (student_id),
  FOREIGN KEY (course_id)  REFERENCES courses (course_id)
);
INSERT INTO enrollments (student_id, course_id, grade) VALUES
  (1, 1, 95.0), (1, 2, 88.5), (1, 3, NULL),
  (2, 1, 72.0), (2, 3, 91.0),
  (3, 4, 85.0), (3, 5, 58.0),
  (5, 4, 100.0), (5, 2, 79.0),
  (6, 6, 66.0);

-- Linus (4) has no enrollments. CSC499 has no students. Music has neither.

CREATE TABLE staff (
  staff_id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(80) NOT NULL,
  email         VARCHAR(80) NOT NULL UNIQUE,
  department_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (department_id) REFERENCES departments (department_id)
);
INSERT INTO staff (staff_id, name, email, department_id) VALUES
  (1, 'Donald Knuth',   'knuth@college.edu',  1),
  (2, 'Barbara Liskov', 'liskov@college.edu', 1),
  (3, 'Howard Zinn',    'zinn@college.edu',   3);


-- ---------------------------------------------- EASY --------

-- Task 1: the best way to reach each student
--
-- Create a view `v_contacts` with the columns
--   student, contact
-- where contact is the email if there is one, otherwise the phone,
-- otherwise the text 'no contact'. Order by student.

-- your code here


-- Task 2: pass or fail
--
-- Create a view `v_results` with the columns
--   student, code, result
-- where result is 'pass' for a grade of 60 or more and 'fail' below.
-- Graded enrollments only. Order by student, then code.

-- your code here


-- Task 3: letter grades
--
-- Create a view `v_letter_grades` with the columns
--   student, code, letter
-- where letter is A for 90 and up, B for 80 and up, C for 70 and up,
-- D for 60 and up, and F otherwise. Graded enrollments only. Order by
-- student, then code.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: above the overall average
--
-- Create a view `v_above_average` with the columns
--   student, code, grade
-- for the enrollments whose grade is above the average of ALL grades.
-- Compute that average with a subquery, not by hand. Order by grade,
-- highest first.

-- your code here


-- Task 5: never enrolled, with a subquery
--
-- Create a view `v_idle_students` with one column, student, listing the
-- students who have no enrollments, this time with NOT IN and a
-- subquery instead of a LEFT JOIN. Order by student.

-- your code here


-- Task 6: everyone on campus, with UNION
--
-- Create a view `v_people` with the columns
--   name, role
-- listing every student (role 'student') and every staff member (role
-- 'staff') in one list. Order by name.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: a FULLTEXT index and a word search
--
-- Add a FULLTEXT index on courses (title, description). Then create a
-- view `v_search_database` with the columns
--   code, title
-- for the courses whose title or description contains the word
-- 'database'. Order by code.

-- your code here


-- Task 8: a boolean search
--
-- Create a view `v_search_web_not_php` with the columns
--   code, title
-- for the courses that mention 'web' but do not mention 'php', using
-- boolean mode. Order by code.

-- your code here


-- Task 9: a department summary with two different counts
--
-- Create a view `v_department_summary` with the columns
--   department, students, courses
-- counting each department's students and its courses. Every department
-- appears, with 0 where there is nothing. Joining both tables at once
-- multiplies the rows, so a plain COUNT is wrong. Order by department.

-- your code here
