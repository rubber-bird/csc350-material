# SQL Fundamentals

Twelve `.sql` files with tasks in them. Modules 01 to 08 build and change tables; 09 to 12 query them (joins, grouping, subqueries, transactions). A small PHP runner executes your file against a fresh database and checks the result.

## Setup

1. Install [XAMPP](https://www.apachefriends.org) and start **Apache** and **MySQL** in its control panel.
2. Copy this folder into `htdocs` and name it `sql-fundamentals`:
   - Windows: `C:\xampp\htdocs\sql-fundamentals`
   - Mac: `/Applications/XAMPP/htdocs/sql-fundamentals`
3. Open http://localhost/sql-fundamentals/.

Every module shows red failing tests. Edit the files in `exercises/`, save, refresh, make them green.

If the page says **No database connection**, MySQL is not running. If you changed the root password, put it in `config.php`.

> **The runner owns the database `sql_fundamentals`** and wipes it on every run. Never keep your own data in it.

## How it works

- Each file is a list of tasks. Write your SQL under each `-- your code here`. Files have EASY, MEDIUM and HARD sections and start with links to the manual pages you need.
- On every refresh the file runs top to bottom against an empty database. Then the tests inspect what it built: tables, columns, types, keys, indexes, and whether rows that should be rejected really are.
- Lines marked "leave this alone" set up a table for you. Don't edit them.
- From module 09 on, a task asks for a query. Write it as a view, `CREATE VIEW v_name AS SELECT ...;`, with the column names the task gives. The tests compare the view's rows with the expected result.

## The modules

| # | File | Covers |
|---|------|--------|
| 01 | `01-create-tables.sql` | `CREATE`, `DROP`, `ALTER`, `RENAME TABLE` |
| 02 | `02-data-types.sql` | integers, `DECIMAL`, `VARCHAR` / `CHAR` / `TEXT`, dates, `ENUM`, `NOT NULL`, `DEFAULT`, `CHECK` |
| 03 | `03-primary-keys.sql` | single and composite primary keys, `AUTO_INCREMENT`, `UNIQUE` |
| 04 | `04-foreign-keys.sql` | `FOREIGN KEY`, `ON DELETE` / `ON UPDATE` rules, self-references, join tables |
| 05 | `05-indexes.sql` | `CREATE INDEX`, unique and composite indexes, prefix and `FULLTEXT` indexes |
| 06 | `06-library-schema.sql` | a four-table design using all of the above |
| 07 | `07-migrations.sql` | changing tables that already hold data |
| 08 | `08-normalize.sql` | one flat table into four normalized ones, plus a `VIEW` |
| 09 | `09-joins.sql` | `INNER JOIN`, `LEFT JOIN`, `USING`, multi-table and self joins |
| 10 | `10-aggregates.sql` | `COUNT`, `SUM`, `AVG`, `MIN`, `MAX`, `GROUP BY`, `HAVING`, `GROUP_CONCAT` |
| 11 | `11-conditions-subqueries-search.sql` | `COALESCE`, `IF`, `CASE`, subqueries, `UNION`, `MATCH ... AGAINST` |
| 12 | `12-transactions.sql` | `START TRANSACTION`, `COMMIT`, `ROLLBACK`, `SAVEPOINT`, `LAST_INSERT_ID()`, `SHA2`, `AES_ENCRYPT` |

## Running in the terminal

XAMPP ships the `php` command, but it's not on your PATH. From inside this folder:

```
C:\xampp\php\php.exe run.php            # Windows, every module
C:\xampp\php\php.exe run.php 03         # just module 03
/Applications/XAMPP/bin/php run.php     # Mac
/Applications/XAMPP/bin/php run.php 03 --hints
```

## Reading a failing test

```
✗ Task 1: `students` has id INT and name VARCHAR(100)
    There is no table called `students`.
    Tables that exist: employees, rooms, scratch, tweets
```

The message tells you what exists so you can compare it with what was asked. A wrong type shows both values (`Expected: "varchar(100)"`, `Received: "text"`). If a statement has an SQL error, a red item at the top of the module shows the server's message and the statement; the rest of the file still runs.

"Expected the database to reject ... but it was accepted" means a constraint is missing.

## Hints

Failing HARD tests (and most tests from module 07 on) have a **Hint 1** you can click open. Hint 2, and sometimes Hint 3, is inside it and is close to the full answer. In the terminal add `--hints`.

## Cheat sheet

| Thing | SQL | Manual |
|-------|-----|--------|
| Create a table | `CREATE TABLE t (id INT, name VARCHAR(100));` | [CREATE TABLE](https://dev.mysql.com/doc/refman/8.4/en/create-table.html) |
| Required, default | `name VARCHAR(100) NOT NULL`, `price DECIMAL(10,2) NOT NULL DEFAULT 0` | same |
| Primary key | `id INT AUTO_INCREMENT PRIMARY KEY` or `PRIMARY KEY (a, b)` | [AUTO_INCREMENT](https://dev.mysql.com/doc/refman/8.4/en/example-auto-increment.html) |
| Unique | `email VARCHAR(255) NOT NULL UNIQUE` | [CREATE INDEX](https://dev.mysql.com/doc/refman/8.4/en/create-index.html) |
| Foreign key | `CONSTRAINT fk_x FOREIGN KEY (a_id) REFERENCES a (id) ON DELETE CASCADE` | [FOREIGN KEY](https://dev.mysql.com/doc/refman/8.4/en/create-table-foreign-keys.html) |
| Value rule | `CHECK (age <= 150)` | [CHECK](https://dev.mysql.com/doc/refman/8.4/en/create-table-check-constraints.html) |
| Index | `CREATE INDEX idx_t_name ON t (name);` | [CREATE INDEX](https://dev.mysql.com/doc/refman/8.4/en/create-index.html) |
| Change a table | `ALTER TABLE t ADD COLUMN x INT;` / `DROP COLUMN x` / `MODIFY x BIGINT` | [ALTER TABLE](https://dev.mysql.com/doc/refman/8.4/en/alter-table.html) |
| See a table | `SHOW CREATE TABLE t;` / `DESCRIBE t;` | [SHOW CREATE TABLE](https://dev.mysql.com/doc/refman/8.4/en/show-create-table.html) |
| Join | `FROM a INNER JOIN b ON a.b_id = b.id` | [JOIN](https://dev.mysql.com/doc/refman/8.4/en/join.html) |
| Unmatched rows | `FROM a LEFT JOIN b ON ... WHERE b.id IS NULL` | same |
| Count per group | `SELECT x, COUNT(*) AS n FROM t GROUP BY x HAVING n > 1` | [GROUP BY](https://dev.mysql.com/doc/refman/8.4/en/group-by-handling.html) |
| Save a query | `CREATE VIEW v_name AS SELECT ...;` | [CREATE VIEW](https://dev.mysql.com/doc/refman/8.4/en/create-view.html) |
| All or nothing | `START TRANSACTION; ... COMMIT;` or `ROLLBACK;` | [Transactions](https://dev.mysql.com/doc/refman/8.4/en/commit.html) |

phpMyAdmin (http://localhost/phpmyadmin/) shows the `sql_fundamentals` database after a run, which is a good way to see what your file built.

## Under the hood

- `index.php` runs every test file on one page; `run.php` does the same in the terminal.
- `spec.php` is the tiny `describe` / `it` / `expect` runner shared with the PHP exercises.
- `db.php` connects, runs an exercise file statement by statement against a fresh database in strict SQL mode, and reads the result back through `information_schema`.
- `config.php` holds the connection settings.
- `solutions/` mirrors `exercises/` with reference answers. Remove it before handing the folder to students, or leave it for self-checking.
