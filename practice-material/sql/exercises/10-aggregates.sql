-- ============================================================
--  10 - Aggregates and GROUP BY
-- ============================================================
--
--  An aggregate function turns a whole column of values into one:
--  COUNT, SUM, AVG, MIN, MAX and GROUP_CONCAT. On its own it gives one
--  row for the whole table. With GROUP BY it gives one row per group:
--
--    SELECT d.name AS department, COUNT(*) AS students
--    FROM students AS s
--    INNER JOIN departments AS d USING (department_id)
--    GROUP BY d.department_id
--
--  Every selected column must be either in the GROUP BY or inside an
--  aggregate. WHERE filters rows before grouping; HAVING filters the
--  groups afterwards, so a condition on a COUNT or SUM goes in HAVING.
--
--  Two traps: COUNT(*) counts rows, COUNT(column) counts non-NULL values.
--  And AVG ignores NULLs. Wrap averages in ROUND(..., 1).
--
--  HOW THE TASKS WORK: as in module 09, each task asks for a VIEW whose
--  column names match the task exactly. Put the ORDER BY in the view.
--
--  You will need (click for the manual):
--    Aggregate functions   https://dev.mysql.com/doc/refman/8.4/en/aggregate-functions.html
--    GROUP BY              https://dev.mysql.com/doc/refman/8.4/en/group-by-handling.html
--    HAVING                https://dev.mysql.com/doc/refman/8.4/en/select.html
--    GROUP_CONCAT          https://dev.mysql.com/doc/refman/8.4/en/aggregate-functions.html#function_group-concat
--    ROUND                 https://dev.mysql.com/doc/refman/8.4/en/mathematical-functions.html#function_round
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

-- Task 1: the whole table in one row
--
-- Create a view `v_enrollment_stats` with one row and the columns
--   enrollments   how many enrollments there are
--   graded        how many of them have a grade
--   average       the average grade, rounded to 1 decimal
--   lowest        the lowest grade
--   highest       the highest grade

-- your code here


-- Task 2: students per department
--
-- Create a view `v_students_per_department` with the columns
--   department, students
-- counting the students in each department. Departments with no students
-- may be left out for now. Order by department.

-- your code here


-- Task 3: the average grade in each course
--
-- Create a view `v_course_averages` with the columns
--   code, average
-- where average is the course's average grade rounded to 1 decimal. Only
-- courses that have enrollments. Order by code.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: popular courses, with HAVING
--
-- Create a view `v_popular_courses` with the columns
--   code, students
-- listing only the courses with 2 or more students. Order by students,
-- most first, then by code.

-- your code here


-- Task 5: every department, including the empty one
--
-- Create a view `v_department_headcount` with the columns
--   department, students
-- like Task 2, but Music must appear with 0. You need a LEFT JOIN, and
-- COUNT of the right thing. Order by department.

-- your code here


-- Task 6: each student's course list in one cell
--
-- Create a view `v_student_courses` with the columns
--   student, courses
-- where courses is the student's course codes in alphabetical order,
-- separated by a comma and a space, e.g. 'CSC101, CSC350'. Only students
-- with at least one enrollment. Order by student.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: credit load
--
-- Create a view `v_credit_load` with the columns
--   student, credits
-- where credits is the total credits of the courses the student is
-- enrolled in. Only students carrying more than 6 credits. Order by
-- credits, highest first, then by student.

-- your code here


-- Task 8: grades by department
--
-- Create a view `v_department_grades` with the columns
--   department, graded, average, lowest, highest
-- grouped by the department that offers the COURSE. graded is the number
-- of graded enrollments, average is rounded to 1 decimal. Only
-- departments with at least one grade. Order by department.

-- your code here


-- Task 9: the top student
--
-- Create a view `v_top_student` with one row and the columns
--   student, average
-- for the student with the highest average grade (rounded to 1 decimal).

-- your code here
