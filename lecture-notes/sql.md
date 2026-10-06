# 2026-09-24 - Lecture notes

## Contents

- [Overview](#overview)
- [Core Concepts](#core-concepts)
- [Important Facts & Definitions](#important-facts--definitions)
- [Practical Examples](#practical-examples)
- [Nice to know](#nice-to-know)
- [Practice exercises](#practice-exercises)
- [Further Reading](#further-reading)

---

## Overview

SQL is the language you use to create a database, define its tables, and then add, read, change, and remove the data inside them. Almost every query in these notes is built from the same few pieces: a command (`SELECT`, `INSERT`, `UPDATE`, `DELETE`), a table, and optional clauses that filter, sort, or limit the rows involved. Built-in functions and aliases then let you reshape the results before they reach your PHP code.

---

## Core Concepts

### Structure comes first, then data

Before you can store anything, you need a database and a table. `CREATE DATABASE` makes the database, and `USE` tells MySQL which database the following commands apply to. `CREATE TABLE` then defines each column: its name, its data type, and any rules it must follow.

Here's a table we'll use throughout these notes:

```sql
CREATE TABLE students (
  student_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  email VARCHAR(80) NOT NULL,
  major VARCHAR(40),
  gpa DECIMAL(3,2),
  enrolled_on DATE NOT NULL,
  PRIMARY KEY (student_id)
);
```

Why it's designed this way:

- **`student_id`** is the primary key, so it uniquely identifies each student. `AUTO_INCREMENT` means MySQL assigns 1, 2, 3, and so on by itself ([Using AUTO_INCREMENT](https://dev.mysql.com/doc/refman/8.4/en/example-auto-increment.html)). `UNSIGNED` rules out negative numbers, which IDs never need, and doubles the usable positive range ([Integer Types](https://dev.mysql.com/doc/refman/8.4/en/integer-types.html)).
- **`NOT NULL`** marks required fields. `major` and `gpa` are allowed to be empty, because a new student may not have declared a major or earned a GPA yet.
- **`DECIMAL(3,2)`** stores exact numbers with 3 digits total, 2 after the decimal point, so values like `3.75` fit exactly ([Fixed-Point Types](https://dev.mysql.com/doc/refman/8.4/en/fixed-point-types.html)).
- **`VARCHAR(n)`** holds text up to `n` characters and only uses as much space as the text needs. There is also `CHAR(n)`, which always stores exactly `n` characters. That's the right choice for a password-hash column, `CHAR(40)`, because a SHA1 hash is always 40 characters long ([CHAR and VARCHAR](https://dev.mysql.com/doc/refman/8.4/en/char.html)).

### Every query is a sentence with optional clauses

Reading data always starts with `SELECT columns FROM table`. Each clause after that refines the result, and they must appear in this order:

```sql
SELECT columns
FROM table
WHERE conditions      -- which rows?
ORDER BY column       -- in what order?
LIMIT how_many;       -- how many of them?
```

A useful way to think about it: `WHERE` decides *which* rows make it into the result, `ORDER BY` arranges them, and `LIMIT` cuts the list down.

### How the database executes a statement

MySQL runs every statement in three steps:

1. **Parse.** Checks the syntax and that the tables and columns exist. A typo stops here with an error.
2. **Plan.** The optimizer picks the cheapest way to get the rows: scan the whole table, or jump straight to matching rows using an index (the primary key is one). Put `EXPLAIN` in front of a query to see the plan.
3. **Execute.** Runs the plan and returns the result set.

During execution the clauses are evaluated in a different order than you write them:

```
FROM  →  WHERE  →  SELECT (columns, functions, aliases)  →  ORDER BY  →  LIMIT
```

This is why an alias works in `ORDER BY` but not in `WHERE`: at filtering time the `SELECT` list hasn't been computed yet. It's also why a `WHERE` on a primary key is fast, and one on a column like `LOWER(email)` is slow (every row has to be read and the function run before the comparison).

References: [Optimization Overview](https://dev.mysql.com/doc/refman/8.4/en/optimize-overview.html), [Optimizing SELECT Statements](https://dev.mysql.com/doc/refman/8.4/en/select-optimization.html), [EXPLAIN](https://dev.mysql.com/doc/refman/8.4/en/explain.html), [How MySQL Uses Indexes](https://dev.mysql.com/doc/refman/8.4/en/mysql-indexes.html).

### Conditions are true-or-false tests on each row

`WHERE` checks every row against a condition and keeps the ones where it's true. Conditions use comparison operators (`=`, `<`, `>=`, `!=`) and can be combined with `AND`, `OR`, `NOT`, and `XOR`.

Two things trip students up here:

- **`AND` is evaluated before `OR`.** `a OR b AND c` means `a OR (b AND c)`. Wrap conditions in parentheses whenever you mix them ([Operator Precedence](https://dev.mysql.com/doc/refman/8.4/en/operator-precedence.html)).
- **`NULL` isn't a value you can compare.** It means "unknown." `gpa = NULL` is never true, even for rows where `gpa` is empty. You have to write `gpa IS NULL` ([Problems with NULL Values](https://dev.mysql.com/doc/refman/8.4/en/problems-with-null.html)).

### INSERT matches values to columns by position

When you list the columns, the values fill them in that order. When you don't list them, you must give a value for every column in the table's exact order. Naming the columns is safer, because the query keeps working even if someone adds or reorders columns later.

For an `AUTO_INCREMENT` column, either leave it out of the column list or pass `NULL`. Both tell MySQL to generate the next ID.

### Quotes tell MySQL what kind of value it's looking at

- Text and dates are quoted: `'Maria'`, `'2026-09-01'`.
- Numbers, functions, and `NULL` are not quoted: `3.5`, `NOW()`, `NULL`.

If you quote a function, it becomes plain text. `'NOW()'` stores the five characters N-O-W-(-), not the current time. The rules for quoting and escaping text are in [String Literals](https://dev.mysql.com/doc/refman/8.4/en/string-literals.html).

> [!IMPORTANT]
> In PHP, never paste user input straight into a query string. A user can type quotes that change what the query does. This is **SQL injection**, and defenses are covered later. For the short version, see OWASP's [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html) (I think you will cover this later on during the course).

### UPDATE and DELETE work on whatever WHERE selects

These commands change or remove every row that matches the `WHERE` clause. With no `WHERE`, that's every row in the table. The safest habits:

- Target rows by primary key when you mean one row.
- Run a `SELECT` with the same `WHERE` first, so you can see exactly which rows will be affected.

### Functions reshape the output, not the stored data

A function in a `SELECT` changes what you get back, while the table stays the same. An alias (`AS`) gives the result a readable column name, which makes it much easier to use from PHP.

Aliases can be used in `ORDER BY`, but **not in `WHERE`**. MySQL filters rows (`WHERE`) before it works out the selected columns and their aliases, so at filtering time the alias doesn't exist yet. The manual explains this in [Problems with Column Aliases](https://dev.mysql.com/doc/refman/8.4/en/problems-with-alias.html).

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Table** | a set of rows (records) and columns (fields) |
| **Primary key** | a column whose value uniquely identifies each row |
| **`AUTO_INCREMENT`** | MySQL assigns the next number automatically |
| **`NULL`** | no known value. Not zero, and not an empty string. |
| **Result set** | the rows a `SELECT` returns |
| **Alias** | a temporary name for a column or calculation in the result |
| **Hash** | a fixed-length value computed from input that can't be reversed (`SHA1()`, `MD5()`) |

### Commands at a glance

| Command | What it does |
|---|---|
| `CREATE DATABASE name` | creates a database |
| `USE name` | selects the database to work in |
| `CREATE TABLE name (...)` | creates a table |
| `SHOW TABLES` | lists tables in the current database |
| `SHOW COLUMNS FROM table` | lists a table's columns and types |
| `INSERT INTO table (...) VALUES (...)` | adds rows |
| `SELECT ... FROM table` | reads rows |
| `UPDATE table SET col = value WHERE ...` | changes rows |
| `DELETE FROM table WHERE ...` | removes rows |
| `TRUNCATE TABLE table` | empties the table and resets `AUTO_INCREMENT` |
| `DROP TABLE` / `DROP DATABASE` | removes the table or database entirely |

### Operators

| Operator | Meaning |
|---|---|
| `=`, `!=`, `<`, `>`, `<=`, `>=` | comparisons |
| `IS NULL` / `IS NOT NULL` | has no value / has a value |
| `BETWEEN a AND b` / `NOT BETWEEN` | within a range, **including** both ends / outside it |
| `IN (a, b, c)` / `NOT IN` | matches any value in the list / none of them |
| `IS TRUE` / `IS FALSE` | for `BOOLEAN` columns: has a true / false value |
| `AND` / `OR` / `NOT` | both true / at least one true / reverses |
| `XOR` | exactly one of two conditions is true |
| `LIKE` / `NOT LIKE` | matches / doesn't match a pattern |

### Wildcards for LIKE

See [`LIKE`](https://dev.mysql.com/doc/refman/8.4/en/string-comparison-functions.html#operator_like) in the manual.

| Wildcard | Matches | Example |
|:---:|---|---|
| `%` | any number of characters, including none | `'Mar%'` matches Maria, Marcus, Mar |
| `_` | exactly one character | `'J_n'` matches Jon and Jan |

### ORDER BY and LIMIT

- `ORDER BY` sorts ascending (`ASC`) by default. Add `DESC` for descending.
- With several sort columns, each one only breaks ties from the column before it.
- `LIMIT n` returns the first `n` rows.
- `LIMIT offset, count` skips `offset` rows, then returns `count`. The offset starts at 0.
- For pagination with `per_page` results, page `p` is `LIMIT (p - 1) * per_page, per_page`.
- Full syntax for both: [SELECT Statement](https://dev.mysql.com/doc/refman/8.4/en/select.html).

### DELETE vs TRUNCATE vs DROP

| | Removes rows | Keeps the table | Resets `AUTO_INCREMENT` |
|---|:---:|:---:|:---:|
| `DELETE FROM table` | Yes | Yes | No |
| `TRUNCATE TABLE table` | Yes | Yes | Yes |
| `DROP TABLE table` | Yes | No | (table is gone) |

More detail: [DELETE](https://dev.mysql.com/doc/refman/8.4/en/delete.html), [TRUNCATE TABLE](https://dev.mysql.com/doc/refman/8.4/en/truncate-table.html), [DROP TABLE](https://dev.mysql.com/doc/refman/8.4/en/drop-table.html).

### Common functions

| Group | Function | Does |
|---|---|---|
| Text | [`CONCAT(a, b, ...)`](https://dev.mysql.com/doc/refman/8.4/en/string-functions.html#function_concat) | joins strings |
| Text | [`LENGTH(s)`](https://dev.mysql.com/doc/refman/8.4/en/string-functions.html#function_length) / [`CHAR_LENGTH(s)`](https://dev.mysql.com/doc/refman/8.4/en/string-functions.html#function_char-length) | length in bytes / in characters |
| Text | `UPPER(s)` / `LOWER(s)` | changes case |
| Numeric | [`FORMAT(n, d)`](https://dev.mysql.com/doc/refman/8.4/en/string-functions.html#function_format) | formats a number with `d` decimals and commas (returns text) |
| Numeric | [`ROUND(n, d)`](https://dev.mysql.com/doc/refman/8.4/en/mathematical-functions.html#function_round) | rounds to `d` decimals |
| Numeric | [`RAND()`](https://dev.mysql.com/doc/refman/8.4/en/mathematical-functions.html#function_rand) | random number between 0 and 1 |
| Date/time | [`NOW()`](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_now) | current date and time |
| Date/time | `CURDATE()` / `CURTIME()` | current date / current time |
| Date/time | `DATE(dt)` | date part of a datetime |
| Date/time | [`DATE_FORMAT(dt, format)`](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_date-format) | formats a date for display |
| Date/time | [`TIME_FORMAT(t, format)`](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_time-format) | same, for the time part only (`'%T'` gives `14:30:00`) |
| Other | [`SHA1(s)`](https://dev.mysql.com/doc/refman/8.4/en/encryption-functions.html#function_sha1), [`MD5(s)`](https://dev.mysql.com/doc/refman/8.4/en/encryption-functions.html#function_md5) | return a hash of the input |

### Date format codes you'll use most

The full list is under [`DATE_FORMAT()`](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_date-format).

| Code | Gives | Example |
|:---:|---|---|
| `%Y` | 4-digit year | 2026 |
| `%M` / `%m` | month name / number | September / 09 |
| `%d` / `%e` | day with / without leading zero | 05 / 5 |
| `%W` | weekday name | Friday |
| `%H` / `%l` | hour, 24-hour / 12-hour | 14 / 2 |
| `%i` | minutes | 30 |
| `%p` | AM or PM | PM |

---

## Practical Examples

All of these use the `students` table from above.

### Adding students

```sql
-- Naming the columns (recommended). student_id is left out, so it's generated.
INSERT INTO students (first_name, last_name, email, major, enrolled_on)
VALUES ('Maria', 'Lopez', 'mlopez@college.edu', 'Biology', '2026-09-01');

-- Several students at once
INSERT INTO students (first_name, last_name, email, enrolled_on)
VALUES ('Jon', 'Park', 'jpark@college.edu', CURDATE()),
       ('Aisha', 'Khan', 'akhan@college.edu', CURDATE());
```

Jon and Aisha have no `major` or `gpa` yet, so those columns are left `NULL`.

### Finding students

```sql
-- Dean's list: GPA of 3.5 or higher, best first
SELECT first_name, last_name, gpa
FROM students
WHERE gpa >= 3.5
ORDER BY gpa DESC;

-- Students in either of two majors
SELECT first_name, last_name, major
FROM students
WHERE major IN ('Computer Science', 'Mathematics');

-- Students who haven't declared a major
SELECT first_name, last_name
FROM students
WHERE major IS NULL;

-- GPA in a middle range, and a major is declared
SELECT first_name, last_name
FROM students
WHERE (gpa BETWEEN 2.0 AND 3.0) AND (major IS NOT NULL);
```

### Searching by pattern

```sql
-- Last names starting with "Mc"
SELECT first_name, last_name FROM students WHERE last_name LIKE 'Mc%';

-- Anyone whose email is NOT a college address
SELECT first_name, email FROM students WHERE email NOT LIKE '%@college.edu';
```

### Sorting and paging

```sql
-- Class roster, like a phone book: last name, then first name
SELECT last_name, first_name FROM students
ORDER BY last_name, first_name;

-- The 3 most recently enrolled students
SELECT first_name, last_name, enrolled_on FROM students
ORDER BY enrolled_on DESC LIMIT 3;

-- Page 2 of the roster, 20 students per page (rows 21–40)
SELECT last_name, first_name FROM students
ORDER BY last_name LIMIT 20, 20;
```

### Updating and deleting

```sql
-- Jon declares a major
UPDATE students SET major = 'History' WHERE student_id = 2;

-- Change two fields at once
UPDATE students SET major = 'Economics', gpa = 3.10 WHERE student_id = 3;

-- Remove one student, with LIMIT 1 as a safety net
DELETE FROM students WHERE student_id = 7 LIMIT 1;
```

### Using functions and aliases

```sql
-- Full names, sorted by the alias
SELECT CONCAT(last_name, ', ', first_name) AS full_name
FROM students
ORDER BY full_name;

-- Enrollment date in a friendly format
SELECT first_name, DATE_FORMAT(enrolled_on, '%M %e, %Y') AS enrolled
FROM students;
-- e.g. "September 1, 2026"

-- A random student to call on in class
SELECT first_name FROM students ORDER BY RAND() LIMIT 1;

-- The student with the longest first name
SELECT first_name, CHAR_LENGTH(first_name) AS len
FROM students
ORDER BY len DESC LIMIT 1;
```

---

## Nice to know

> [!CAUTION]
> **Forgetting `WHERE` (filter) on `UPDATE` or `DELETE`.** `UPDATE students SET major = 'History';` changes every student's major. Always double-check the `WHERE` clause before running these.

> [!WARNING]
> **Aliases in `WHERE` don't work.** `SELECT first_name AS name FROM users WHERE name='Sam'` gives an error in MySQL. Filter on the real column instead: `WHERE first_name = 'Sam'`.

> [!IMPORTANT]
> **`BETWEEN` includes both ends.** With `DATETIME` columns, `BETWEEN '2026-09-01' AND '2026-09-30'` stops at midnight at the start of September 30. Use `>= '2026-09-01' AND < '2026-10-01'` to get the whole month.

> [!TIP]
> **SHA1 and MD5 are fine for the educational or Proof of concept kind of things, but not for production environment.** The are not currently good to use. PHP projects usually use [`password_hash()`](https://www.php.net/manual/en/function.password-hash.php) and [`password_verify()`](https://www.php.net/manual/en/function.password-verify.php) instead of fast hashing algorithms. OWASP's [Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html) explains why fast hashes are a problem and which algorithms to use instead.

## Practice exercises

The self-checking SQL exercises in [practice-material/sql](../practice-material/sql/README.md) start where these notes do. Modules 01 to 03 (`CREATE TABLE`, data types, primary keys) match this material; the rest follow the [database design](sql-database-design.md) and [advanced SQL](sql-advanced.md) notes.

Exercises to do in phpMyAdmin or the mysql client, using the `students` table from these notes:

1. **Build and fill.** Create the `students` table, insert six students with `INSERT` statements that name their columns, two of them in one statement, and leave `major` and `gpa` empty for at least one. Check the result with `SELECT *` and `SHOW COLUMNS FROM students`.
2. **Predict, then run.** Before running each query, write down which of your six rows it returns: `WHERE gpa > 3.0`; `WHERE gpa = NULL`; `WHERE gpa IS NULL`; `WHERE major = 'Biology' OR major = 'History' AND gpa > 3.5`. Then add the parentheses that make the last one mean what it looks like.
3. **Patterns.** Write queries for: last names ending in `son`; emails that are not at `college.edu`; first names that are exactly four letters long (use `_`, not `LENGTH`).
4. **Paging.** Sort the roster by last name then first name and return page 2 with a page size of 2. Then write the query a PHP page would send for page `$p` with `$per_page` rows.
5. **Safe changes.** Give one student a GPA with `UPDATE`, but run the `SELECT` with the same `WHERE` first. Then delete one student by primary key with `LIMIT 1`. Finally, say in one sentence what `UPDATE students SET gpa = 4.0;` would do.
6. **Reshape the output.** Produce one column `name` as `Last, First`, one column `enrolled` formatted like `Monday, September 1, 2026`, and sort by the `name` alias. Explain why `WHERE name LIKE 'L%'` fails and rewrite it so it works.
7. **Quotes.** Insert a student whose last name is `O'Brien`, and one whose enrollment date is today without typing today's date. Then insert `'NOW()'` in quotes on purpose, look at what was stored, and delete that row.

## Further Reading

| Topic | MySQL 8.4 Reference Manual |
|---|---|
| Data types | [data-types](https://dev.mysql.com/doc/refman/8.4/en/data-types.html) |
| `CREATE TABLE` | [create-table](https://dev.mysql.com/doc/refman/8.4/en/create-table.html) |
| `INSERT` | [insert](https://dev.mysql.com/doc/refman/8.4/en/insert.html) |
| `SELECT` (with `WHERE`, `ORDER BY`, `LIMIT`) | [select](https://dev.mysql.com/doc/refman/8.4/en/select.html) |
| `UPDATE` | [update](https://dev.mysql.com/doc/refman/8.4/en/update.html) |
| `DELETE` | [delete](https://dev.mysql.com/doc/refman/8.4/en/delete.html) |
| Operators | [comparison-operators](https://dev.mysql.com/doc/refman/8.4/en/comparison-operators.html) |
| String functions | [string-functions](https://dev.mysql.com/doc/refman/8.4/en/string-functions.html) |
| Math functions | [mathematical-functions](https://dev.mysql.com/doc/refman/8.4/en/mathematical-functions.html) |
| Date functions and format codes | [date-and-time-functions](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_date-format) |
| How statements are executed | [optimize-overview](https://dev.mysql.com/doc/refman/8.4/en/optimize-overview.html), [select-optimization](https://dev.mysql.com/doc/refman/8.4/en/select-optimization.html) |
| Seeing the query plan | [explain](https://dev.mysql.com/doc/refman/8.4/en/explain.html) |
| Indexes | [mysql-indexes](https://dev.mysql.com/doc/refman/8.4/en/mysql-indexes.html) |

| Topic | OWASP |
|---|---|
| Storing passwords safely | [Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html) |
| Preventing SQL injection | [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html) |

