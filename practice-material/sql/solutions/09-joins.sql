-- 09 - Joins: reference solution

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
CREATE VIEW v_catalog AS
SELECT c.code, c.title, d.name AS department
FROM courses AS c
INNER JOIN departments AS d ON c.department_id = d.department_id
ORDER BY c.code;

-- Task 2
CREATE VIEW v_roster AS
SELECT c.code, CONCAT(s.first_name, ' ', s.last_name) AS student
FROM enrollments AS e
INNER JOIN students AS s ON e.student_id = s.student_id
INNER JOIN courses  AS c ON e.course_id  = c.course_id
ORDER BY c.code, student;

-- Task 3
CREATE VIEW v_majors AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, d.name AS major
FROM students AS s
INNER JOIN departments AS d USING (department_id)
ORDER BY student;

-- Task 4
CREATE VIEW v_never_enrolled AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student
FROM students AS s
LEFT JOIN enrollments AS e ON s.student_id = e.student_id
WHERE e.student_id IS NULL
ORDER BY student;

-- Task 5
CREATE VIEW v_course_rosters AS
SELECT c.code, CONCAT(s.first_name, ' ', s.last_name) AS student
FROM courses AS c
LEFT JOIN enrollments AS e ON c.course_id = e.course_id
LEFT JOIN students    AS s ON e.student_id = s.student_id
ORDER BY c.code, student;

-- Task 6
CREATE VIEW v_prerequisites AS
SELECT c.code, p.code AS prerequisite
FROM courses AS c
LEFT JOIN courses AS p ON c.prerequisite_id = p.course_id
ORDER BY c.code;

-- Task 7
CREATE VIEW v_transcript AS
SELECT CONCAT(s.first_name, ' ', s.last_name) AS student, c.code, d.name AS department, e.grade
FROM enrollments AS e
INNER JOIN students    AS s ON e.student_id    = s.student_id
INNER JOIN courses     AS c ON e.course_id     = c.course_id
INNER JOIN departments AS d ON c.department_id = d.department_id
WHERE e.grade IS NOT NULL
ORDER BY student, c.code;

-- Task 8
CREATE VIEW v_classmates AS
SELECT c.code,
       CONCAT(a.first_name, ' ', a.last_name) AS student_a,
       CONCAT(b.first_name, ' ', b.last_name) AS student_b
FROM enrollments AS e1
INNER JOIN enrollments AS e2 ON e1.course_id = e2.course_id AND e1.student_id < e2.student_id
INNER JOIN courses  AS c ON e1.course_id  = c.course_id
INNER JOIN students AS a ON e1.student_id = a.student_id
INNER JOIN students AS b ON e2.student_id = b.student_id
ORDER BY c.code, student_a, student_b;

-- Task 9
CREATE VIEW v_not_taken_by_ada AS
SELECT c.code
FROM courses AS c
LEFT JOIN enrollments AS e ON c.course_id = e.course_id AND e.student_id = 1
WHERE e.student_id IS NULL
ORDER BY c.code;
