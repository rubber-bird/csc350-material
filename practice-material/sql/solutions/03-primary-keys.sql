-- 03 - Primary keys: reference solution

-- Task 1
CREATE TABLE users (
  id       INT PRIMARY KEY,
  username VARCHAR(50)
);

-- Task 2
CREATE TABLE posts (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200)
);

-- Task 3
CREATE TABLE countries (
  code CHAR(2) PRIMARY KEY,
  name VARCHAR(100)
);

-- Task 4
CREATE TABLE enrollments (
  student_id INT,
  course_id  INT,
  PRIMARY KEY (student_id, course_id)
);

-- Task 5
CREATE TABLE emails (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  address VARCHAR(255) NOT NULL UNIQUE
);

-- Task 6
CREATE TABLE legacy (id INT NOT NULL, note VARCHAR(100));
INSERT INTO legacy (id, note) VALUES (1, 'one'), (2, 'two'), (3, 'three');
ALTER TABLE legacy ADD PRIMARY KEY (id);

-- Task 7
CREATE TABLE seats (
  flight_no   CHAR(6),
  seat_row    TINYINT UNSIGNED,
  seat_letter CHAR(1),
  passenger   VARCHAR(100),
  PRIMARY KEY (flight_no, seat_row, seat_letter)
);

-- Task 8
CREATE TABLE tickets (
  id    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event VARCHAR(100)
) AUTO_INCREMENT = 1000;

-- Task 9
CREATE TABLE bad_pk (name VARCHAR(100) NOT NULL, PRIMARY KEY (name));
INSERT INTO bad_pk (name) VALUES ('Ada'), ('Alan'), ('Grace');
ALTER TABLE bad_pk
  DROP PRIMARY KEY,
  ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;
