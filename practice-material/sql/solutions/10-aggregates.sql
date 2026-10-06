-- 10 - Aggregates and GROUP BY: reference solution

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

-- Task 1
CREATE VIEW v_enrollment_stats AS
SELECT COUNT(*)             AS enrollments,
       COUNT(grade)         AS graded,
       ROUND(AVG(grade), 1) AS average,
       MIN(grade)           AS lowest,
       MAX(grade)           AS highest
FROM enrollments;

-- Task 2
CREATE VIEW v_students_per_department AS
SELECT d.name AS department, COUNT(*) AS students
FROM students AS s
INNER JOIN departments AS d USING (department_id)
GROUP BY d.department_id
ORDER BY department;

-- Task 3
CREATE VIEW v_course_averages AS
SELECT c.code, ROUND(AVG(e.grade), 1) AS average
FROM courses AS c
INNER JOIN enrollments AS e USING (course_id)
GROUP BY c.course_id
ORDER BY c.code;

-- Task 4
CREATE VIEW v_popular_courses AS
SELECT c.code, COUNT(*) AS students
FROM courses AS c
INNER JOIN enrollments AS e USING (course_id)
GROUP BY c.course_id
HAVING students >= 2
ORDER BY students DESC, c.code;

-- Task 5
CREATE VIEW v_department_headcount AS
SELECT d.name AS department, COUNT(s.student_id) AS students
FROM departments AS d
LEFT JOIN students AS s USING (department_id)
GROUP BY d.department_id
ORDER BY department;

-- Task 6
CREATE VIEW v_student_courses AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student,
       GROUP_CONCAT(c.code ORDER BY c.code SEPARATOR ', ') AS courses
FROM students AS s
INNER JOIN enrollments AS e USING (student_id)
INNER JOIN courses     AS c USING (course_id)
GROUP BY s.student_id
ORDER BY student;

-- Task 7
CREATE VIEW v_credit_load AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, SUM(c.credits) AS credits
FROM students AS s
INNER JOIN enrollments AS e USING (student_id)
INNER JOIN courses     AS c USING (course_id)
GROUP BY s.student_id
HAVING credits > 6
ORDER BY credits DESC, student;

-- Task 8
CREATE VIEW v_department_grades AS
SELECT d.name AS department,
       COUNT(e.grade)         AS graded,
       ROUND(AVG(e.grade), 1) AS average,
       MIN(e.grade)           AS lowest,
       MAX(e.grade)           AS highest
FROM departments AS d
INNER JOIN courses     AS c USING (department_id)
INNER JOIN enrollments AS e USING (course_id)
GROUP BY d.department_id
HAVING graded > 0
ORDER BY department;

-- Task 9
CREATE VIEW v_top_student AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, ROUND(AVG(e.grade), 1) AS average
FROM students AS s
INNER JOIN enrollments AS e USING (student_id)
GROUP BY s.student_id
ORDER BY AVG(e.grade) DESC
LIMIT 1;
