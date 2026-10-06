-- ============================================================
--  09 - Joins
-- ============================================================
--
--  Normalization split the data into tables; joins put it back together.
--  A join reads from two tables where a condition holds, almost always
--  "this foreign key equals that primary key":
--
--    SELECT c.code, d.name
--    FROM courses AS c
--    INNER JOIN departments AS d ON c.department_id = d.department_id
--
--  INNER JOIN keeps only rows with a match on both sides. LEFT JOIN keeps
--  every row of the left table and fills the right side with NULL where
--  there is no match. USING (col) replaces ON when both columns have the
--  same name.
--
--  HOW THE TASKS WORK: each task asks for a VIEW. Write your query inside
--    CREATE VIEW v_name AS SELECT ... ;
--  and the tests read the view's rows. Column names must match the task
--  exactly, so use AS to name them. Where the task gives an order, put the
--  ORDER BY inside the view.
--
--  You will need (click for the manual):
--    JOIN syntax    https://dev.mysql.com/doc/refman/8.4/en/join.html
--    CREATE VIEW    https://dev.mysql.com/doc/refman/8.4/en/create-view.html
--    CONCAT         https://dev.mysql.com/doc/refman/8.4/en/string-functions.html#function_concat
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


-- ---------------------------------------------- EASY --------

-- Task 1: the course catalog
--
-- Create a view `v_catalog` with one row per course and the columns
--   code, title, department
-- where department is the department's name. Order by code.

-- your code here


-- Task 2: who is taking what
--
-- Create a view `v_roster` with one row per enrollment and the columns
--   code, student
-- where student is the first and last name joined with a space, e.g.
-- 'Ada Lovelace'. You need three tables. Order by code, then student.

-- your code here


-- Task 3: majors, with USING
--
-- Create a view `v_majors` with the columns
--   student, major
-- where major is the name of the student's department. Both tables call
-- the column department_id, so join them with USING. Order by student.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: students who have never enrolled
--
-- Create a view `v_never_enrolled` with one column, student, listing the
-- students who have no row in enrollments. Use a LEFT JOIN and keep the
-- rows where the right side is NULL. Order by student.

-- your code here


-- Task 5: every course, even the empty one
--
-- Create a view `v_course_rosters` with the columns
--   code, student
-- like v_roster, but a course with no students must still appear, once,
-- with student NULL. Order by code, then student.

-- your code here


-- Task 6: prerequisites (a self join)
--
-- Create a view `v_prerequisites` with the columns
--   code, prerequisite
-- where prerequisite is the code of the course's prerequisite, or NULL
-- when it has none. Join courses to itself under two aliases. Order by
-- code.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: the transcript (four tables)
--
-- Create a view `v_transcript` with the columns
--   student, code, department, grade
-- where department is the department that offers the COURSE (not the
-- student's own). Only graded enrollments (grade IS NOT NULL). Order by
-- student, then code.

-- your code here


-- Task 8: classmates
--
-- Create a view `v_classmates` with the columns
--   code, student_a, student_b
-- with one row for every pair of students who share a course. Each pair
-- appears once: student_a is the one with the smaller student_id. Join
-- enrollments to itself. Order by code, then student_a, then student_b.

-- your code here


-- Task 9: what Ada has not taken
--
-- Create a view `v_not_taken_by_ada` with one column, code, listing the
-- courses student 1 (Ada) has no enrollment in. Do it with a LEFT JOIN
-- whose ON clause also checks the student_id, then keep the unmatched
-- rows. (A WHERE on the student_id would throw away the wrong rows; try
-- it and see.) Order by code.

-- your code here
