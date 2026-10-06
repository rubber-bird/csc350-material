-- 11 - Conditional values, subqueries and text search: reference solution

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

-- Task 1
CREATE VIEW v_contacts AS
SELECT CONCAT(first_name, ' ', last_name) AS student,
       COALESCE(email, phone, 'no contact') AS contact
FROM students
ORDER BY student;

-- Task 2
CREATE VIEW v_results AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, c.code,
       IF(e.grade >= 60, 'pass', 'fail') AS result
FROM enrollments AS e
INNER JOIN students AS s USING (student_id)
INNER JOIN courses  AS c USING (course_id)
WHERE e.grade IS NOT NULL
ORDER BY student, c.code;

-- Task 3
CREATE VIEW v_letter_grades AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, c.code,
       CASE
         WHEN e.grade >= 90 THEN 'A'
         WHEN e.grade >= 80 THEN 'B'
         WHEN e.grade >= 70 THEN 'C'
         WHEN e.grade >= 60 THEN 'D'
         ELSE 'F'
       END AS letter
FROM enrollments AS e
INNER JOIN students AS s USING (student_id)
INNER JOIN courses  AS c USING (course_id)
WHERE e.grade IS NOT NULL
ORDER BY student, c.code;

-- Task 4
CREATE VIEW v_above_average AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, c.code, e.grade
FROM enrollments AS e
INNER JOIN students AS s USING (student_id)
INNER JOIN courses  AS c USING (course_id)
WHERE e.grade > (SELECT AVG(grade) FROM enrollments)
ORDER BY e.grade DESC;

-- Task 5
CREATE VIEW v_idle_students AS
SELECT CONCAT(first_name, ' ', last_name) AS student
FROM students
WHERE student_id NOT IN (SELECT student_id FROM enrollments)
ORDER BY student;

-- Task 6
CREATE VIEW v_people AS
SELECT CONCAT(first_name, ' ', last_name) AS name, 'student' AS role FROM students
UNION
SELECT name, 'staff' FROM staff
ORDER BY name;

-- Task 7
ALTER TABLE courses ADD FULLTEXT (title, description);

CREATE VIEW v_search_database AS
SELECT code, title
FROM courses
WHERE MATCH (title, description) AGAINST ('database')
ORDER BY code;

-- Task 8
CREATE VIEW v_search_web_not_php AS
SELECT code, title
FROM courses
WHERE MATCH (title, description) AGAINST ('+web -php' IN BOOLEAN MODE)
ORDER BY code;

-- Task 9
CREATE VIEW v_department_summary AS
SELECT d.name AS department,
       COUNT(DISTINCT s.student_id) AS students,
       COUNT(DISTINCT c.course_id)  AS courses
FROM departments AS d
LEFT JOIN students AS s USING (department_id)
LEFT JOIN courses  AS c USING (department_id)
GROUP BY d.department_id
ORDER BY department;
