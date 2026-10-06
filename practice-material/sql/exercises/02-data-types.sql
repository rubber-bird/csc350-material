-- ============================================================
--  02 - Data types
-- ============================================================
--
--  Every column has a type, and the type decides what the database will
--  accept, how much space it uses, and how it compares and sorts. Picking
--  the right one is most of the job of designing a table.
--
--  The tests run this file against a fresh database, then look at each
--  column's type and try to insert values that must be accepted or
--  rejected. The database runs in strict mode: a value that does not fit
--  is an error, not a silent conversion.
--
--  Types you will need (click for the manual):
--    INT, BIGINT, TINYINT   https://mariadb.com/kb/en/int/
--    UNSIGNED               https://mariadb.com/kb/en/numeric-data-type-overview/
--    DECIMAL(p, s)          https://mariadb.com/kb/en/decimal/
--    VARCHAR, CHAR, TEXT    https://mariadb.com/kb/en/string-data-types/
--    DATE, DATETIME,        https://mariadb.com/kb/en/date-and-time-data-types/
--    TIMESTAMP
--    BOOLEAN                https://mariadb.com/kb/en/boolean/
--    ENUM                   https://mariadb.com/kb/en/enum/
--    NOT NULL, DEFAULT      https://mariadb.com/kb/en/create-table/#column-definitions
--    CHECK                  https://mariadb.com/kb/en/constraint/#check-constraints
--
-- ============================================================

-- ---------------------------------------------- EASY --------

-- Task 1: money
--
-- Create `prices` with:
--   item    VARCHAR(100)
--   price   a number with 2 decimal places and up to 10 digits in total
--
-- Never store money in FLOAT or DOUBLE: 0.1 + 0.2 is not 0.3 in binary
-- floating point. DECIMAL stores exact decimal digits.

-- your code here


-- Task 2: dates and times
--
-- Create `events` with:
--   title      VARCHAR(100)
--   starts_at  a date AND a time of day
--   on_date    a date only, no time

-- your code here


-- Task 3: yes or no
--
-- Create `flags` with:
--   name       VARCHAR(50)
--   is_active  a true/false value
--
-- MariaDB has no real boolean type. BOOLEAN is a shorthand that the
-- server turns into TINYINT(1); the tests expect exactly that.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: required and optional
--
-- Create `people` with:
--   name   VARCHAR(100), required: a row without a name must be rejected
--   bio    long text of any length, optional

-- your code here


-- Task 5: defaults
--
-- Create `accounts` with:
--   id          INT
--   balance     DECIMAL(12,2), required, 0 when not given
--   created_at  TIMESTAMP, required, the current time when not given
--
-- Then INSERT INTO accounts (id) VALUES (1) must work and fill in the
-- other two columns by itself.

-- your code here


-- Task 6: one of a fixed list
--
-- Create `sizes` with:
--   label   required, and only ever one of 'S', 'M', 'L' or 'XL'
--
-- Look up ENUM. Inserting 'XXL' must be rejected.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: never negative, sometimes huge
--
-- Create `counters` with:
--   name   VARCHAR(50)
--   hits   a whole number that can never be negative, required, 0 by default
--   total  a whole number that may exceed 2 billion
--
-- INT tops out at about 2.1 billion. Inserting -1 into hits must fail.

-- your code here


-- Task 8: a rule about the value
--
-- Create `people_ages` with:
--   name   VARCHAR(100)
--   age    a small whole number, never negative, and never above 150
--
-- The "never above 150" part is not a type. It is a CHECK constraint.

-- your code here


-- Task 9: fixed length
--
-- Create `codes` with:
--   code   exactly 3 characters, required
--
-- Airport codes, currency codes, country codes: when every value has the
-- same length, CHAR(n) is the type. Inserting 'ABCD' must be rejected.

-- your code here
