# Database design - Lecture notes

## Contents

- [Overview](#overview)
- [Core Concepts](#core-concepts)
- [Important Facts & Definitions](#important-facts--definitions)
- [Practical Examples](#practical-examples)
- [Going further](#going-further)
- [Nice to know](#nice-to-know)
- [Practice exercises](#practice-exercises)
- [Further Reading](#further-reading)

---

## Overview

The first SQL notes showed how to create one table and work with its rows. A real application has many tables, and the hard part is deciding what they are: which columns go where, what type each one has, how the tables point at each other, and what the database should refuse to store. Getting this right early saves a lot of pain, because changing a table that already holds data is much harder than changing an empty one.

These notes cover the decisions in the order you make them: naming, choosing types, choosing keys, splitting data into related tables (normalization), speeding things up with indexes, protecting relationships with foreign key constraints, and the server-level settings (storage engine, character set, time zone) that quietly affect everything.

---

## Core Concepts

### Naming databases, tables and columns

- Use only letters, digits, and the underscore.
- Don't use SQL keywords (`order`, `group`, `user` are the ones people hit most).
- Keep names under 64 characters and unique inside their scope: two tables can both have an `id` column, but one table can't have two.
- Treat names as **case-sensitive**. Whether `Users` and `users` are the same table depends on the operating system, so pick one style and stick to it. The usual style is lowercase with underscores: `users`, `forum_posts`, `created_at`.

### Choosing a column type: the smallest one that fits

Every column has a type, and the type decides what can be stored, how much space it takes, and how fast it can be compared. The rule of thumb is to pick the **smallest type that will always be big enough**.

**Text.** The first SQL notes covered `CHAR(n)` versus `VARCHAR(n)`: fixed length for values that are always the same size (a 2-letter state code, a 64-character hash), variable length for everything else. For anything longer than a few hundred characters use one of the `TEXT` types, which can hold whole documents but can't have a `DEFAULT` and are slower to search.

**Numbers.** The integer types differ only in range: `TINYINT` holds up to 127 (255 unsigned), `INT` up to about 2 billion, `BIGINT` far more. Pick the smallest that fits and add `UNSIGNED` when the value can never be negative. For numbers with a decimal point, `DECIMAL` is exact; `FLOAT` and `DOUBLE` are approximations, fine for measurements and useless for prices (`0.1 + 0.2` doesn't equal `0.3`).

**Dates and times.** `DATE` is a day, `TIME` is a time of day, `DATETIME` is both. `TIMESTAMP` also stores both, but it's converted to and from the session's time zone on the way in and out, and its range ends in 2038 on most servers. For "when did this happen" columns, `DATETIME` is the simpler choice.

**Special types.** `ENUM('small', 'medium', 'large')` stores exactly one value from a fixed list and rejects anything else. `SET` stores any combination from a list. `BOOLEAN` is just a nickname for `TINYINT(1)`, with `TRUE` and `FALSE` stored as 1 and 0.

### Column properties

After the type, a column can carry several rules:

| Property | Means |
|---|---|
| `NOT NULL` | the column must have a value. Use it on as many columns as you honestly can. |
| `DEFAULT value` | what to store when an `INSERT` leaves the column out |
| `UNSIGNED` | numbers only, no negatives |
| `AUTO_INCREMENT` | the server assigns the next number. One per table, on the primary key. |
| `UNIQUE` | no two rows may share a value (an email address, a username) |
| `CHECK (condition)` | every row must satisfy the condition, e.g. `CHECK (price >= 0)` |
| `ZEROFILL` | pads numbers with leading zeros on display (`INT(5) ZEROFILL` shows `00042`). Legacy; format in PHP instead. |

### Primary keys

Every table should have a primary key: a column (or set of columns) whose value identifies exactly one row. A good primary key

1. always has a value,
2. never changes, and
3. is unique for every row.

In practice that means an `INT UNSIGNED NOT NULL AUTO_INCREMENT` column, usually named `id` or `tablename_id`. Don't use something with real-world meaning, like an email address, as a primary key, because people change their email. Occasionally a key is made of several columns together (a **composite key**), typically in a table that links two other tables.

### Foreign keys and relationships

A **foreign key** is a column that holds the primary key value of a row in another table. A `messages` table with a `user_id` column "points at" the user who wrote each message. Foreign keys are how tables relate to each other, and there are three kinds of relationship:

```
one-to-one     users ──── profiles          one user has one profile
                 1          1                (rare; usually just one table)

one-to-many    forums ──── messages         one forum has many messages,
                 1          many             each message is in one forum

many-to-many   students ─── enrollments ─── courses
                 1       many          many   1
```

A many-to-many relationship (a student takes many courses, a course has many students) is always stored as **two** one-to-many relationships through a third table, here `enrollments`, with a foreign key to each side. Its primary key is usually the pair of foreign keys together.

### Normalization: splitting data so nothing is stored twice

Normalization is a set of rules for deciding which columns belong in which table. The goal is that each fact is stored exactly once, so it can't become inconsistent. Three levels are enough for almost every application.

**First normal form (1NF).** Every column holds a single value, and no table has repeating groups. A `phone1, phone2, phone3` set of columns, or a `tags` column containing `'php, mysql, web'`, both break 1NF. Fix: move the repeating thing into its own table, one row per value.

**Second normal form (2NF).** Already in 1NF, and every non-key column depends on the **whole** primary key. This only matters for composite keys. If `enrollments` has the key `(student_id, course_id)` and also stores `student_name`, the name depends only on `student_id`, not on the pair. Fix: move it to `students`, where `student_id` alone is the key.

**Third normal form (3NF).** Already in 2NF, and non-key columns don't depend on **each other**. If a table has both `zip_code` and `city`, the city is determined by the zip code, not by the row's key. Fix: a `zip_codes` table with one row per code.

A practical way to spot problems: look for the **same non-key value repeated across many rows** (the same author name on fifty books). That value belongs in its own table with an id, and the fifty rows should hold the id.

Normalization is sometimes broken on purpose for speed or convenience. Storing a date and time in one `DATETIME` column, or a simple `status ENUM` instead of a `statuses` table, are both fine.

### Indexes: making lookups fast

Without an index, finding `WHERE email = 'x'` means reading every row. An index is a separate, sorted structure the server can search instead, which makes the lookup almost instant. The primary key is an index, and `UNIQUE` creates one too.

Index the columns you **search, sort, and join on**: anything that appears in `WHERE`, `ORDER BY`, or a join condition. Don't bother indexing columns with only a few distinct values (a yes/no flag) or ones that are mostly `NULL`. Every index also has a cost: it must be updated on every `INSERT`, `UPDATE`, and `DELETE`, so don't index everything.

```sql
CREATE TABLE users (
  user_id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(20) NOT NULL,
  last_name  VARCHAR(40) NOT NULL,
  email      VARCHAR(80) NOT NULL,
  pass       VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE (email),                        -- one account per address, and fast login lookup
  INDEX full_name (last_name, first_name) -- speeds up sorting and searching by name
);
```

An index on two columns works for searches on the first column alone, or both together, but **not** for the second column alone, so put the most-searched column first.

### Foreign key constraints: letting the database guard relationships

Declaring a column as a foreign key makes the server **enforce** the relationship. It refuses a message whose `user_id` doesn't exist, and it decides what happens to the messages when their user is deleted or renumbered:

```sql
FOREIGN KEY (user_id) REFERENCES users (user_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
```

| Action | On deleting / changing the parent row |
|---|---|
| `RESTRICT` (the default) | refuse, as long as child rows exist |
| `CASCADE` | delete / update the child rows too |
| `SET NULL` | set the child column to `NULL` (so it must allow `NULL`) |

Both tables must use the InnoDB storage engine, and the two columns must have exactly the same type.

### Storage engines

MySQL can store a table in different formats, called storage engines, chosen with `ENGINE = name` at the end of `CREATE TABLE`. **InnoDB** is the default and the one to use: it supports transactions and foreign key constraints. **MyISAM** is older and faster for read-only data but has neither, so you'll only meet it in legacy projects. XAMPP creates InnoDB tables unless told otherwise.

### Character sets and collations

A **character set** is the alphabet a column can store. A **collation** is the set of rules for comparing and sorting values in it: whether `a` equals `A`, whether `é` sorts with `e`, and so on. Use `utf8mb4`, which holds every character including emoji, with a case-insensitive collation such as `utf8mb4_unicode_ci`:

```sql
CREATE DATABASE forum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Tables and columns inherit from the database unless they say otherwise. The character set must match what your PHP and HTML use: `<meta charset="utf-8">` in the page and `charset=utf8mb4` in the database connection, or accented names will come back as garbage.

### Time zones

By default a server stores `NOW()` in its own local time, which goes wrong the moment users or servers are in different places, or when the clocks change. Store times in UTC (`UTC_TIMESTAMP()` instead of `NOW()`), and convert for display with `CONVERT_TZ(value, '+00:00', '-05:00')`. Named zones like `'America/New_York'` work only if the server has its time zone tables loaded, so offsets are safer in XAMPP.

### Changing a table after the fact

`ALTER TABLE` changes a table's definition without losing its rows. The forms you'll use most:

```sql
ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email;
ALTER TABLE users MODIFY COLUMN phone VARCHAR(30) NOT NULL;   -- same name, new definition
ALTER TABLE users CHANGE COLUMN phone mobile VARCHAR(30);      -- rename (and redefine)
ALTER TABLE users DROP COLUMN mobile;
ALTER TABLE users ADD INDEX (last_name);
ALTER TABLE messages ADD CONSTRAINT fk_messages_user
  FOREIGN KEY (user_id) REFERENCES users (user_id);
RENAME TABLE users TO members;
```

Adding `NOT NULL` or `UNIQUE` to a column that already holds data only works if every existing row already satisfies the rule, so clean the data first.

### Talking to the server

Two tools come with XAMPP. **phpMyAdmin** is a web page at http://localhost/phpmyadmin/ : pick the database on the left, click a table to browse it, and use the **SQL** tab to run any query. The **mysql client** is a command-line program in `C:\xampp\mysql\bin\mysql.exe` (Windows) or `/Applications/XAMPP/bin/mysql` (macOS; with MAMP it's `/Applications/MAMP/Library/bin/mysql`):

```
mysql -u root -p          # start it; XAMPP's root has an empty password by default
USE forum;                 # pick a database
SHOW TABLES;
exit                       # leave
```

Every command ends with a semicolon. The client is handy for pasting whole `.sql` files, and it's what the SQL exercises in this repository are built around.

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Schema** | the design of a database: its tables, columns, types and relationships |
| **Primary key** | the column(s) that uniquely identify a row |
| **Composite key** | a primary key made of two or more columns |
| **Foreign key** | a column holding another table's primary key value |
| **Constraint** | a rule the database enforces: `NOT NULL`, `UNIQUE`, `CHECK`, `FOREIGN KEY` |
| **Join table** | a table whose only job is to link two others (many-to-many) |
| **Normalization** | organizing columns into tables so each fact is stored once |
| **Index** | a sorted structure that makes searching a column fast |
| **Storage engine** | how a table is stored on disk; InnoDB is the default |
| **Character set / collation** | which characters a column can hold / how they compare and sort |

### Integer types

| Type | Signed range | Unsigned range | Bytes |
|---|---|---|:---:|
| `TINYINT` | -128 to 127 | 0 to 255 | 1 |
| `SMALLINT` | -32,768 to 32,767 | 0 to 65,535 | 2 |
| `MEDIUMINT` | -8,388,608 to 8,388,607 | 0 to 16,777,215 | 3 |
| `INT` | about ±2.1 billion | 0 to about 4.3 billion | 4 |
| `BIGINT` | about ±9.2 quintillion | 0 to about 18 quintillion | 8 |

`DECIMAL(10,2)` holds exact numbers up to 99,999,999.99. `FLOAT` and `DOUBLE` are approximate.

### Text types

| Type | Holds | Use for |
|---|---|---|
| `CHAR(n)` | exactly `n` characters, up to 255 | fixed-length codes and hashes |
| `VARCHAR(n)` | up to `n` characters | names, emails, titles |
| `TINYTEXT` / `TEXT` | up to 255 / 65,535 characters | short / ordinary free text |
| `MEDIUMTEXT` / `LONGTEXT` | up to 16 MB / 4 GB | articles, logs |
| `ENUM('a','b')` | one value from a fixed list | status, size, role |
| `SET('a','b')` | any combination from a list | rarely; a join table is usually clearer |

### Date and time types

| Type | Format | Notes |
|---|---|---|
| `DATE` | `2026-09-01` | |
| `TIME` | `14:30:00` | can also be a duration |
| `DATETIME` | `2026-09-01 14:30:00` | stored exactly as given |
| `TIMESTAMP` | `2026-09-01 14:30:00` | converted to/from the session time zone; ends in 2038 on most servers |

### Normal forms in one line each

| Form | Rule | Typical fix |
|---|---|---|
| 1NF | one value per column, no repeating groups | new table, one row per value |
| 2NF | non-key columns depend on the whole key | move the column to the table its part of the key identifies |
| 3NF | non-key columns don't depend on each other | lookup table for the dependent column |

### Index types

| Keyword | Creates |
|---|---|
| `PRIMARY KEY` | the unique, not-null index that identifies rows |
| `UNIQUE` | an index that also forbids duplicates |
| `INDEX` (or `KEY`) | an ordinary index for speed |
| `FULLTEXT` | an index for word searches in text columns (`MATCH ... AGAINST`) |

### Foreign key actions

| | Parent row deleted | Parent key changed |
|---|---|---|
| `ON DELETE RESTRICT` / `ON UPDATE RESTRICT` | refused while children exist | refused while children exist |
| `ON DELETE CASCADE` / `ON UPDATE CASCADE` | children deleted | children updated to the new value |
| `ON DELETE SET NULL` / `ON UPDATE SET NULL` | children's column set to `NULL` | same |

### Inspecting a table

| Command | Shows |
|---|---|
| `DESCRIBE table` | columns, types, nulls, keys, defaults |
| `SHOW CREATE TABLE table` | the full `CREATE TABLE` statement, including indexes and foreign keys |
| `SHOW INDEX FROM table` | every index and the columns in it |
| `SHOW ENGINES` / `SHOW CHARACTER SET` / `SHOW COLLATION` | what the server supports |

---

## Practical Examples

### A forum: three related tables

Users write messages, and every message belongs to a forum. One user has many messages, and one forum has many messages.

```sql
CREATE DATABASE forum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE forum;

CREATE TABLE users (
  user_id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username   VARCHAR(30)  NOT NULL,
  email      VARCHAR(80)  NOT NULL,
  pass       VARCHAR(255) NOT NULL,
  created_at DATETIME     NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE (username),
  UNIQUE (email)
) ENGINE = InnoDB;

CREATE TABLE forums (
  forum_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name     VARCHAR(60)  NOT NULL,
  PRIMARY KEY (forum_id),
  UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE messages (
  message_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  forum_id     INT UNSIGNED NOT NULL,
  user_id      INT UNSIGNED NOT NULL,
  subject      VARCHAR(100) NOT NULL,
  body         TEXT         NOT NULL,
  date_entered DATETIME     NOT NULL,
  PRIMARY KEY (message_id),
  INDEX (date_entered),
  FOREIGN KEY (forum_id) REFERENCES forums (forum_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id)  REFERENCES users  (user_id)  ON DELETE CASCADE
) ENGINE = InnoDB;
```

Deleting a forum removes its messages, which is what you'd expect. The `UNIQUE` on `email` doubles as the index that makes login lookups fast. `pass` is `VARCHAR(255)` rather than `CHAR(40)` because PHP's `password_hash()` produces strings of varying length and may produce longer ones in the future.

### Normalizing a spreadsheet

A club keeps this sheet:

| member | email | event | event_date | role |
|---|---|---|---|---|
| Ada | ada@x.org | Hackathon | 2026-10-10 | organizer |
| Ada | ada@x.org | Pizza night | 2026-10-17 | attendee |
| Alan | alan@x.org | Hackathon | 2026-10-10 | attendee |

Ada's email is stored twice, the hackathon's date twice. If Ada changes her email, one row might be missed. The same facts in 3NF:

```sql
CREATE TABLE members (
  member_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name      VARCHAR(60) NOT NULL,
  email     VARCHAR(80) NOT NULL,
  PRIMARY KEY (member_id),
  UNIQUE (email)
);

CREATE TABLE events (
  event_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  event_date DATE NOT NULL,
  PRIMARY KEY (event_id)
);

-- the many-to-many link, with one extra fact that belongs to the pair
CREATE TABLE attendance (
  member_id INT UNSIGNED NOT NULL,
  event_id  INT UNSIGNED NOT NULL,
  role      ENUM('organizer', 'attendee') NOT NULL DEFAULT 'attendee',
  PRIMARY KEY (member_id, event_id),
  FOREIGN KEY (member_id) REFERENCES members (member_id) ON DELETE CASCADE,
  FOREIGN KEY (event_id)  REFERENCES events  (event_id)  ON DELETE CASCADE
);
```

`role` stays in `attendance` because it depends on the pair: Ada is an organizer of one event and an attendee of another.

### Watching a constraint do its job

```sql
INSERT INTO messages (forum_id, user_id, subject, body, date_entered)
VALUES (99, 1, 'Hello', 'Is anyone here?', UTC_TIMESTAMP());
-- ERROR 1452: Cannot add or update a child row: a foreign key constraint fails

INSERT INTO users (username, email, pass, created_at)
VALUES ('ada', 'ada@x.org', '...', UTC_TIMESTAMP());
INSERT INTO users (username, email, pass, created_at)
VALUES ('ada2', 'ada@x.org', '...', UTC_TIMESTAMP());
-- ERROR 1062: Duplicate entry 'ada@x.org' for key 'email'
```

These errors are your friends. Without the constraints both inserts would succeed and the bad data would surface weeks later in a page that shows a message with no author.

### Adding a constraint to a table that already exists

```sql
-- messages was created without the foreign keys. Clean up orphans first ...
DELETE FROM messages WHERE user_id NOT IN (SELECT user_id FROM users);

-- ... then add the rule, with a name you can refer to later
ALTER TABLE messages
  ADD CONSTRAINT fk_messages_user
  FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE;

-- and an index for the page that lists a user's posts newest first
ALTER TABLE messages ADD INDEX user_date (user_id, date_entered);
```

### Seeing whether an index is used

```sql
EXPLAIN SELECT * FROM users WHERE email = 'ada@x.org';
-- type: const, key: email      -> the UNIQUE index was used; one row read

-- the users table from the Indexes section has INDEX full_name (last_name, first_name).
-- Searching on its first column uses it ...
EXPLAIN SELECT * FROM users WHERE last_name = 'Lovelace';
-- type: ref, key: full_name

-- ... searching only the second column does not
EXPLAIN SELECT * FROM users WHERE first_name = 'Ada';
-- type: ALL, key: NULL          -> every row read
```

### Showing times in the user's zone

```sql
-- stored in UTC
INSERT INTO messages (..., date_entered) VALUES (..., UTC_TIMESTAMP());

-- displayed in New York time (UTC-4 in summer, UTC-5 in winter)
SELECT subject, CONVERT_TZ(date_entered, '+00:00', '-04:00') AS posted
FROM messages;
```

---

## Going further

### Designing the term project's tables

The project needs registration, login, and a few pages that read and write data. Start from the questions each page asks:

- **Registration** inserts a user. So `users` needs `username`, `email`, `pass`, `created_at`, with `UNIQUE` on the fields that must not repeat.
- **Login** looks up one user by email or username. That column needs an index, which `UNIQUE` already gives you.
- **Every other page** stores things that belong to a user. Each such table gets a `user_id` foreign key with `ON DELETE CASCADE`, so deleting an account cleans up after itself.

Write the `CREATE TABLE` statements into a `schema.sql` file in your project and keep it up to date. Being able to rebuild the database from one file is worth more than any amount of clicking in phpMyAdmin.

### Soft deletes and timestamps

Two columns that almost every table benefits from:

```sql
created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

With the `DEFAULT`, an `INSERT` can leave them out and they fill themselves in. Many applications also add `deleted_at DATETIME NULL` and set it instead of running `DELETE`, so that "deleted" rows can be restored and nothing is cascaded away by mistake. Every query then needs `WHERE deleted_at IS NULL`.

### `CHECK` constraints

A `CHECK` lets the database enforce rules the types can't express:

```sql
CREATE TABLE products (
  product_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  price      DECIMAL(8,2) NOT NULL,
  sale_price DECIMAL(8,2),
  PRIMARY KEY (product_id),
  CHECK (price >= 0),
  CHECK (sale_price IS NULL OR sale_price < price)
);
```

MySQL 8.0.16 and later enforces these. Older servers silently ignore them, which is one reason to also validate in PHP.

### Strict mode

By default an old MySQL server would quietly truncate `'Alexander'` to fit a `VARCHAR(5)` or store `0` for a bad date. Modern servers run in **strict mode**, where such values are errors, and that's what you want. You can check with `SELECT @@sql_mode;` and see `STRICT_TRANS_TABLES` in the list. The SQL exercises in this repository turn it on for every run.

### Dumping and restoring

`mysqldump` writes a database back out as SQL, which is how you back it up, move it to another machine, or hand it to a teammate:

```
mysqldump -u root -p forum > forum.sql
mysql -u root -p forum < forum.sql
```

phpMyAdmin's **Export** and **Import** tabs do the same thing through the browser.

---

## Nice to know

> [!WARNING]
> **`utf8` is not UTF-8.** In MySQL the character set called `utf8` is an old 3-byte version that can't store emoji or some Asian characters; inserting one gives an "Incorrect string value" error. Always write `utf8mb4`.

> [!WARNING]
> **Never store money in `FLOAT` or `DOUBLE`.** Binary floating point can't represent 0.10 exactly, and the errors add up over a column of prices. `DECIMAL(10,2)` is exact.

> [!IMPORTANT]
> **A foreign key needs matching types on both sides.** `INT UNSIGNED` on one side and `INT` on the other fails with a vague "errno: 150" message. Copy the parent's exact type, including `UNSIGNED`.

> [!IMPORTANT]
> **Case-insensitive collations affect `UNIQUE`.** With `utf8mb4_unicode_ci`, `Ada@x.org` and `ada@x.org` count as duplicates. That's usually what you want for emails and usernames, and worth knowing when it surprises you.

> [!NOTE]
> **`ENUM` is convenient but rigid.** Adding a fourth size means an `ALTER TABLE` on a possibly huge table. If the list of values changes often, or has data of its own (a description, a price), make it a table instead.
The removal of enum value causes a full scan which might be problematic on huge array of data.

> [!TIP]
> **Draw it first.** Before writing any `CREATE TABLE`, sketch the tables as boxes with their columns and draw lines for the foreign keys. Free tools like dbdiagram.io do this from a few lines of text. A design problem found on paper costs minutes; the same problem found after launch costs a migration.

---

## Practice exercises

The self-checking SQL exercises in [practice-material/sql](../practice-material/sql/README.md) cover this topic. Modules 01 to 08 go from `CREATE TABLE` through types, keys, foreign keys and indexes to normalizing a flat table, and a runner checks what your SQL actually built.

Pen-and-paper exercises, which are what an exam would ask:

1. **Types.** For each of these, name the best column type and properties: a US zip code; a product price; the number of seats in a classroom; a yes/no "account confirmed" flag; a blog post body; a user's date of birth; a 64-character SHA-256 hash.
2. **Spot the violations.** A table `orders(order_id, customer_name, customer_email, product_names, order_date)` stores `'Keyboard, Mouse'` in `product_names`. Say which normal form it breaks and redesign it into 3NF, with primary and foreign keys.
3. **Relationships.** For a music site with artists, albums, songs, playlists and users, list every relationship and say whether it's one-to-one, one-to-many or many-to-many. Name the join tables you need.
4. **Cascade or restrict?** In the forum schema above, argue for `ON DELETE CASCADE` on `messages.user_id` and then argue against it. What would `SET NULL` require, and what would the page that shows a thread have to do?
5. **Indexes.** For a `messages` table that is listed by forum newest first, searched by subject, and counted per user, say which indexes you'd create, in which column order, and which column you'd refuse to index.
6. **Migration.** `users.email` was created `VARCHAR(40)` and without `UNIQUE`. Write the statements to find duplicates, decide what to do with them, widen the column to 80 characters and add the constraint.

---

## Further Reading

| Topic | MySQL 8.4 Reference Manual |
|---|---|
| Identifier names | [identifiers](https://dev.mysql.com/doc/refman/8.4/en/identifiers.html) |
| Data types | [data-types](https://dev.mysql.com/doc/refman/8.4/en/data-types.html), [integer-types](https://dev.mysql.com/doc/refman/8.4/en/integer-types.html), [fixed-point-types](https://dev.mysql.com/doc/refman/8.4/en/fixed-point-types.html), [char](https://dev.mysql.com/doc/refman/8.4/en/char.html), [datetime](https://dev.mysql.com/doc/refman/8.4/en/datetime.html), [enum](https://dev.mysql.com/doc/refman/8.4/en/enum.html) |
| `CREATE TABLE` and `ALTER TABLE` | [create-table](https://dev.mysql.com/doc/refman/8.4/en/create-table.html), [alter-table](https://dev.mysql.com/doc/refman/8.4/en/alter-table.html) |
| Primary keys and `AUTO_INCREMENT` | [example-auto-increment](https://dev.mysql.com/doc/refman/8.4/en/example-auto-increment.html) |
| Foreign key constraints | [create-table-foreign-keys](https://dev.mysql.com/doc/refman/8.4/en/create-table-foreign-keys.html) |
| `CHECK` constraints | [create-table-check-constraints](https://dev.mysql.com/doc/refman/8.4/en/create-table-check-constraints.html) |
| Indexes | [mysql-indexes](https://dev.mysql.com/doc/refman/8.4/en/mysql-indexes.html), [create-index](https://dev.mysql.com/doc/refman/8.4/en/create-index.html), [multiple-column-indexes](https://dev.mysql.com/doc/refman/8.4/en/multiple-column-indexes.html) |
| Storage engines | [storage-engines](https://dev.mysql.com/doc/refman/8.4/en/storage-engines.html) |
| Character sets and collations | [charset](https://dev.mysql.com/doc/refman/8.4/en/charset.html) |
| Time zones | [time-zone-support](https://dev.mysql.com/doc/refman/8.4/en/time-zone-support.html), [CONVERT_TZ](https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_convert-tz) |
| Strict mode | [sql-mode](https://dev.mysql.com/doc/refman/8.4/en/sql-mode.html) |
| `EXPLAIN` | [explain](https://dev.mysql.com/doc/refman/8.4/en/explain.html) |
| Backups with `mysqldump` | [mysqldump](https://dev.mysql.com/doc/refman/8.4/en/mysqldump.html) |
