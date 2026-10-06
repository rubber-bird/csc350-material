-- 02 - Data types: reference solution

-- Task 1
CREATE TABLE prices (
  item  VARCHAR(100),
  price DECIMAL(10,2)
);

-- Task 2
CREATE TABLE events (
  title     VARCHAR(100),
  starts_at DATETIME,
  on_date   DATE
);

-- Task 3
CREATE TABLE flags (
  name      VARCHAR(50),
  is_active BOOLEAN
);

-- Task 4
CREATE TABLE people (
  name VARCHAR(100) NOT NULL,
  bio  TEXT
);

-- Task 5
CREATE TABLE accounts (
  id         INT,
  balance    DECIMAL(12,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Task 6
CREATE TABLE sizes (
  label ENUM('S', 'M', 'L', 'XL') NOT NULL
);

-- Task 7
CREATE TABLE counters (
  name  VARCHAR(50),
  hits  INT UNSIGNED NOT NULL DEFAULT 0,
  total BIGINT
);

-- Task 8
CREATE TABLE people_ages (
  name VARCHAR(100),
  age  TINYINT UNSIGNED,
  CHECK (age <= 150)
);

-- Task 9
CREATE TABLE codes (
  code CHAR(3) NOT NULL
);
