# Advanced SQL - Lecture notes

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

Normalization splits data across tables, so almost every useful page needs to put it back together: a message with its author's name, a forum with its message count, a user with the date of their latest post. This is what **joins** and **grouping** are for, and together they are most of the SQL you'll write in the project.

The rest covers things that come up once a site is real: picking a value conditionally, making searches fast with indexes, searching text properly, running several statements as one all-or-nothing unit (transactions), and encrypting data you need to read back. Everything from the first SQL notes (selecting, filtering, sorting, functions, aliases, hashes with `SHA1`) is assumed and not repeated here.

All examples use the forum schema from the database design notes: `users`, `forums`, and `messages`, where each message has a `user_id` and a `forum_id`.

---

## Core Concepts

### A join reads from two tables at once

A join combines rows from two tables where a condition is true, almost always "this foreign key equals that primary key":

```sql
SELECT messages.subject, users.username
FROM messages
INNER JOIN users ON messages.user_id = users.user_id;
```

The result is a new, temporary table with columns from both. Two habits keep joins readable:

- **Prefix columns with the table name** (`users.user_id`) when both tables have a column of that name. Without it you get an "ambiguous column" error.
- **Give the tables short aliases** and use those: `FROM messages AS m INNER JOIN users AS u ON m.user_id = u.user_id`. The `AS` is optional.

When the joined columns have exactly the same name in both tables, `USING (user_id)` can replace the `ON` clause.

### INNER JOIN keeps matches, OUTER JOIN keeps everything on one side

An **INNER JOIN** returns only rows that have a match on both sides. A message whose author was deleted, or a forum with no messages, simply doesn't appear.

A **LEFT JOIN** returns **every** row of the left table, matched or not. Where there's no match, the right table's columns come back as `NULL`. That's how you list all forums including the empty ones, or find users who have never posted:

```sql
SELECT f.name, COUNT(m.message_id) AS messages
FROM forums AS f
LEFT JOIN messages AS m ON f.forum_id = m.forum_id
GROUP BY f.forum_id;
```

`RIGHT JOIN` is the same with the sides swapped. Rewrite it as a `LEFT JOIN` with the tables the other way round; nobody enjoys reading right joins. `FULL JOIN` (everything from both sides) doesn't exist in MySQL.

```
INNER JOIN            LEFT JOIN             RIGHT JOIN
  A  ∩  B              A  ∪  (A ∩ B)         (A ∩ B)  ∪  B
 only matches         all of A, B or NULL   all of B, A or NULL
```

### Joining more than two tables

Each extra table gets its own `JOIN ... ON`. The joins are applied left to right, and the result of each becomes the left side of the next:

```sql
SELECT u.username, f.name AS forum, m.subject
FROM messages AS m
INNER JOIN users  AS u USING (user_id)
INNER JOIN forums AS f USING (forum_id)
ORDER BY m.date_entered DESC
LIMIT 5;
```

A table can even be joined to itself (a **self join**), as long as each copy gets a different alias. The classic case is an `employees` table with a `manager_id` column: join `employees AS e` to `employees AS boss ON e.manager_id = boss.employee_id`.

### Aggregate functions collapse many rows into one value

`COUNT`, `SUM`, `AVG`, `MIN`, `MAX` and `GROUP_CONCAT` take a whole column of values and return one result:

```sql
SELECT COUNT(*) AS total, AVG(LENGTH(body)) AS avg_length, MAX(date_entered) AS latest
FROM messages;
```

Two details matter. `COUNT(*)` counts rows; `COUNT(column)` counts rows where that column is **not NULL**, which is how `COUNT(m.message_id)` above returns 0 for an empty forum rather than 1. And `DISTINCT` inside the function counts unique values: `COUNT(DISTINCT user_id)` is the number of different people who posted.

### GROUP BY makes one row per group, HAVING filters the groups

Without `GROUP BY`, an aggregate gives one row for the whole table. With it, you get one row **per distinct value** of the grouped column, and the aggregates are computed inside each group:

```sql
SELECT u.username, COUNT(*) AS posts
FROM messages AS m
INNER JOIN users AS u USING (user_id)
GROUP BY u.user_id
HAVING posts >= 10
ORDER BY posts DESC;
```

Every column in the `SELECT` must either be in the `GROUP BY` or be inside an aggregate. Asking for `subject` here makes no sense, because each group holds many subjects, and modern servers reject it.

`WHERE` filters **rows before** grouping. `HAVING` filters **groups after** grouping, so it's the only place a condition on an aggregate can go. The evaluation order from the first SQL notes gains two steps in the middle:

```
FROM / JOIN  →  WHERE  →  GROUP BY  →  HAVING  →  SELECT  →  ORDER BY  →  LIMIT
```

### Picking a value conditionally

Sometimes the value you want to display depends on the data. Three functions handle this without any PHP:

- `COALESCE(a, b, ...)` returns the first argument that isn't `NULL`: `COALESCE(nickname, username, 'anonymous')`.
- `IF(condition, if_true, if_false)` is a one-line choice: `IF(posts >= 10, 'regular', 'newcomer')`.
- `CASE` handles several branches: `CASE WHEN grade >= 90 THEN 'A' WHEN grade >= 80 THEN 'B' ELSE 'F' END`.

### Indexes and search: when a WHERE is fast

The database design notes explain how to create an index. This is the other half: which searches can actually use one. An index is a sorted copy of a column, and the server uses it like a phone book: jump to where the matches start, read them, stop. That only works when the query compares the **indexed column itself** with a **known value or range**.

Uses the index:

```sql
WHERE email = 'ada@x.org'                      -- exact match
WHERE date_entered >= '2026-09-01'             -- range
WHERE user_id IN (1, 2, 3)                     -- list
WHERE subject LIKE 'Re:%'                      -- prefix: the start of the value is known
WHERE last_name = 'Lovelace' AND first_name = 'Ada'   -- composite index (last_name, first_name)
```

Can't use it, so every row is read:

```sql
WHERE LOWER(email) = 'ada@x.org'               -- a function hides the column
WHERE YEAR(date_entered) = 2026                -- same
WHERE subject LIKE '%join%'                    -- leading wildcard: no known start
WHERE first_name = 'Ada'                       -- second column of (last_name, first_name) alone
WHERE phone = 5550102                          -- number compared with a VARCHAR column
```

The fix is usually to rewrite the condition so the column stands alone. `YEAR(date_entered) = 2026` becomes `date_entered >= '2026-01-01' AND date_entered < '2027-01-01'`. `LOWER(email) = ...` isn't needed at all with a case-insensitive collation, which is the default. `LIKE '%word%'` has no rewrite; that's what FULLTEXT indexes are for, below.

Indexes help more than `WHERE`:

- **`ORDER BY ... LIMIT`.** With an index on `date_entered`, "the 20 newest messages" reads 20 index entries and stops. Without it the server sorts the whole table first, on every page view.
- **Joins.** The `ON` column of the inner table must be indexed, or each row of the outer table triggers a full scan of the inner one. InnoDB indexes foreign key columns automatically, which is one more reason to declare them.
- **`GROUP BY`.** Grouping on an indexed column lets the server walk the index instead of sorting.

`EXPLAIN` in front of a query shows whether an index was used. The `type` column is the one to read: `const`, `eq_ref`, `ref` and `range` mean an index did the work; `index` means the whole index was scanned; `ALL` means the whole table was.

### FULLTEXT search: finding words, not substrings

`LIKE '%mysql%'` works for short tables but reads every row and matches `mysql` inside `hmysqlx`. A **FULLTEXT index** on one or more text columns indexes the individual words, and `MATCH ... AGAINST` searches them:

```sql
ALTER TABLE messages ADD FULLTEXT (subject, body);

SELECT subject FROM messages
WHERE MATCH (subject, body) AGAINST ('html css');
```

In this default **natural language mode**, rows matching any of the words come back, best matches first. **Boolean mode** gives you operators: `'+html -javascript'` means "must contain html, must not contain javascript", and `'"web design"'` matches the exact phrase. The columns in `MATCH` must be exactly the columns the index was built on. InnoDB supports FULLTEXT on modern servers, so there's no need for MyISAM.

### Transactions: all or nothing

Moving money between two accounts takes two `UPDATE`s. If the server crashes between them, money vanishes. A **transaction** groups statements so that either all of them take effect or none do:

```sql
START TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE account_id = 1;
UPDATE accounts SET balance = balance + 100 WHERE account_id = 2;
COMMIT;        -- make it permanent, or ROLLBACK to undo both
```

Until `COMMIT`, nothing is visible to other connections, and `ROLLBACK` throws the changes away. Transactions need InnoDB tables. `CREATE`, `ALTER`, `DROP` and `TRUNCATE` can't be rolled back; running one of them inside a transaction commits it immediately.

### Encryption is reversible, hashing is not

The first SQL notes introduced hashes (`SHA1`, `MD5`) for passwords: one-way, so a stolen table doesn't reveal them. Two additions. `SHA2('text', 256)` is the stronger hash to use when you hash in SQL at all (64 characters, so a `CHAR(64)` column). And for data the application must read back, such as an API token, hashing is useless; you need **encryption**, which is reversible with a key: `AES_ENCRYPT(value, key)` and `AES_DECRYPT(column, key)`. The result is binary, so the column must be `VARBINARY` or `BLOB`. Where the key lives is the hard part: never in the database that holds the encrypted data.

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Join** | combining rows from two tables where a condition holds |
| **Join condition** | the `ON` or `USING` part: which columns must be equal |
| **Inner / outer join** | matches only / all rows from one side, with `NULL` for missing matches |
| **Self join** | joining a table to another copy of itself under a different alias |
| **Aggregate function** | one that turns many rows into one value (`COUNT`, `SUM`, ...) |
| **Group** | the set of rows sharing the same `GROUP BY` value |
| **Transaction** | statements that succeed or fail as one unit |
| **Commit / rollback** | make a transaction permanent / undo it |

### Join types

| Join | Returns | Typical use |
|---|---|---|
| `INNER JOIN` | rows with a match on both sides | a message with its author |
| `LEFT JOIN` | every left row; right columns `NULL` when unmatched | all forums with their message count, even 0 |
| `RIGHT JOIN` | every right row; left columns `NULL` when unmatched | same as a left join with the tables swapped |
| `CROSS JOIN` | every combination of rows | rarely; generating combinations |
| self join | a table against itself | manager and employee, course and prerequisite |

### Aggregate functions

| Function | Returns | Note |
|---|---|---|
| `COUNT(*)` | number of rows | includes rows full of `NULL` |
| `COUNT(col)` | number of non-`NULL` values | `COUNT(DISTINCT col)` for unique values |
| `SUM(col)` | total | `NULL` if there are no rows |
| `AVG(col)` | mean | ignores `NULL`s; usually wrap in `ROUND()` |
| `MIN(col)` / `MAX(col)` | smallest / largest | works on dates and text too |
| `GROUP_CONCAT(col)` | the values joined into one string | `GROUP_CONCAT(name ORDER BY name SEPARATOR ', ')` |

### WHERE versus HAVING

| | `WHERE` | `HAVING` |
|---|---|---|
| Filters | individual rows | groups |
| Runs | before `GROUP BY` | after `GROUP BY` |
| Can use aggregates | no | yes |
| Can use aliases | no | yes |

### Which conditions can use an index

| Condition on an indexed column | Index used? |
|---|:---:|
| `col = value`, `col IN (...)`, `col BETWEEN a AND b`, `col > value` | yes |
| `col LIKE 'abc%'` | yes |
| `col LIKE '%abc'` or `'%abc%'` | no |
| `FUNCTION(col) = value` | no |
| `col = value` where the types differ (text column, number value) | no |
| second column of a composite index, without the first | no |
| `col IS NULL` | yes |
| `col != value`, `col NOT IN (...)` | rarely useful: most rows match anyway |

### Reading `EXPLAIN`

| `type` | Meaning | How many rows are read |
|---|---|---|
| `const` / `eq_ref` | lookup by primary key or unique index | one |
| `ref` | lookup by a non-unique index | the matching ones |
| `range` | an index scan over a range (`BETWEEN`, `>`, `LIKE 'x%'`) | the range |
| `index` | the whole index, in order | every index entry |
| `ALL` | the whole table | every row |

The `key` column names the index that was chosen and `rows` is the estimate of how many rows will be examined. A large `rows` with `type: ALL` on a big table is the query to fix.

### Boolean FULLTEXT operators

| Operator | Meaning | Example |
|:---:|---|---|
| `+` | word must be present | `+mysql` |
| `-` | word must be absent | `-oracle` |
| (none) | optional, raises the score | `mysql php` |
| `>` / `<` | raise / lower a word's weight | `>mysql <sqlite` |
| `~` | present is fine, but lowers the score | `~beginner` |
| `*` | prefix wildcard | `data*` matches data, database, datatype |
| `"..."` | exact phrase | `"web development"` |
| `( )` | grouping | `+(html css) -javascript` |

### Transaction statements

| Statement | Does |
|---|---|
| `START TRANSACTION` (or `BEGIN`) | begins the unit |
| `COMMIT` | saves everything since the start |
| `ROLLBACK` | discards everything since the start |
| `SAVEPOINT name` / `ROLLBACK TO name` | a partial undo point inside a transaction |

### Hashing and encryption functions

| Function | Returns | Length |
|---|---|---|
| `SHA2(s, 256)` | hex hash | 64 characters |
| `AES_ENCRYPT(s, key)` / `AES_DECRYPT(b, key)` | binary / the original text | store in `VARBINARY` or `BLOB` |

---

## Practical Examples

Sample data for the forum:

```sql
INSERT INTO users (user_id, username, email, pass, created_at) VALUES
  (1, 'ada',   'ada@x.org',   '...', '2026-09-01 09:00:00'),
  (2, 'alan',  'alan@x.org',  '...', '2026-09-02 10:00:00'),
  (3, 'grace', 'grace@x.org', '...', '2026-09-03 11:00:00');

INSERT INTO forums (forum_id, name) VALUES
  (1, 'MySQL'), (2, 'PHP'), (3, 'Modern Dance');

INSERT INTO messages (message_id, forum_id, user_id, subject, body, date_entered) VALUES
  (1, 1, 1, 'Joins',        'How do I join two tables?',        '2026-09-10 08:00:00'),
  (2, 1, 2, 'Re: Joins',    'Use INNER JOIN with ON.',          '2026-09-10 08:30:00'),
  (3, 2, 1, 'Sticky forms', 'How do I keep the values?',        '2026-09-11 09:00:00'),
  (4, 1, 1, 'Indexes',      'When should I add one?',           '2026-09-12 10:00:00');
```

Grace has never posted. Modern Dance has no messages.

### Every message with its author and forum

```sql
SELECT m.subject, u.username, f.name AS forum
FROM messages AS m
INNER JOIN users  AS u ON m.user_id  = u.user_id
INNER JOIN forums AS f ON m.forum_id = f.forum_id
ORDER BY m.date_entered;
```

| subject | username | forum |
|---|---|---|
| Joins | ada | MySQL |
| Re: Joins | alan | MySQL |
| Sticky forms | ada | PHP |
| Indexes | ada | MySQL |

### All forums, with a message count, including empty ones

```sql
SELECT f.name, COUNT(m.message_id) AS messages
FROM forums AS f
LEFT JOIN messages AS m ON f.forum_id = m.forum_id
GROUP BY f.forum_id
ORDER BY messages DESC, f.name;
```

| name | messages |
|---|---|
| MySQL | 3 |
| PHP | 1 |
| Modern Dance | 0 |

With `INNER JOIN` instead of `LEFT JOIN`, Modern Dance would vanish from the list. With `COUNT(*)` instead of `COUNT(m.message_id)`, it would show 1.

### Users who have never posted

```sql
SELECT u.username
FROM users AS u
LEFT JOIN messages AS m ON u.user_id = m.user_id
WHERE m.message_id IS NULL;
-- grace
```

The left join gives Grace a row full of `NULL`s on the messages side, and the `WHERE` keeps only those rows.

### Who posts the most, and only the busy ones

```sql
SELECT u.username, COUNT(*) AS posts, MAX(m.date_entered) AS last_post
FROM users AS u
INNER JOIN messages AS m USING (user_id)
GROUP BY u.user_id
HAVING posts > 1
ORDER BY posts DESC;
-- ada | 3 | 2026-09-12 10:00:00
```

### A per-forum summary in one row each

```sql
SELECT f.name,
       COUNT(DISTINCT m.user_id)                 AS posters,
       GROUP_CONCAT(DISTINCT u.username ORDER BY u.username) AS who,
       COALESCE(MAX(m.date_entered), 'never')    AS last_activity
FROM forums AS f
LEFT JOIN messages AS m USING (forum_id)
LEFT JOIN users    AS u USING (user_id)
GROUP BY f.forum_id;
```

| name | posters | who | last_activity |
|---|---|---|---|
| MySQL | 2 | ada,alan | 2026-09-12 10:00:00 |
| PHP | 1 | ada | 2026-09-11 09:00:00 |
| Modern Dance | 0 | NULL | never |

### Labels computed in the query

```sql
SELECT username,
       IF(user_id IN (SELECT user_id FROM messages), 'poster', 'lurker') AS kind,
       CASE
         WHEN created_at < '2026-09-02' THEN 'founder'
         WHEN created_at < '2026-09-03' THEN 'early'
         ELSE 'regular'
       END AS cohort
FROM users;
```

### Searching with and without an index

```sql
-- messages has INDEX (date_entered) and, via the foreign key, an index on user_id

EXPLAIN SELECT subject FROM messages WHERE user_id = 1;
-- type: ref, key: user_id, rows: 3        -> index on the foreign key

EXPLAIN SELECT subject FROM messages ORDER BY date_entered DESC LIMIT 20;
-- type: index, key: date_entered, rows: 20 -> reads 20 entries from the end of the index

EXPLAIN SELECT subject FROM messages WHERE YEAR(date_entered) = 2026;
-- type: ALL, rows: 4                       -> the function hides the column

EXPLAIN SELECT subject FROM messages
WHERE date_entered >= '2026-01-01' AND date_entered < '2027-01-01';
-- type: range, key: date_entered           -> same question, index used

EXPLAIN SELECT subject FROM messages WHERE subject LIKE 'Re:%';
-- type: ALL                                -> there is no index on subject yet

ALTER TABLE messages ADD INDEX (subject);
-- now the same query is type: range. But LIKE '%join%' stays ALL; see below.
```

### Searching message text

```sql
ALTER TABLE messages ADD FULLTEXT (subject, body);

-- any of the words, best match first
SELECT subject FROM messages WHERE MATCH (subject, body) AGAINST ('join tables');

-- must mention joins, must not mention indexes
SELECT subject FROM messages
WHERE MATCH (subject, body) AGAINST ('+join* -index*' IN BOOLEAN MODE);

-- show the relevance score and sort by it
SELECT subject, MATCH (subject, body) AGAINST ('join') AS score
FROM messages
WHERE MATCH (subject, body) AGAINST ('join')
ORDER BY score DESC;
```

### A transfer that can't half-happen

```sql
START TRANSACTION;
UPDATE accounts SET balance = balance - 250 WHERE account_id = 1;
UPDATE accounts SET balance = balance + 250 WHERE account_id = 2;
INSERT INTO transfers (from_account, to_account, amount) VALUES (1, 2, 250);
COMMIT;
```

If the second `UPDATE` fails (say a `CHECK (balance >= 0)` rejects it), run `ROLLBACK` and the first one is undone too. In PHP the same thing is `$pdo->beginTransaction()`, the statements, then `$pdo->commit()` in a `try` and `$pdo->rollBack()` in the `catch`.

### Storing a token you need back

```sql
-- stored encrypted; the key comes from the application's config, not the database
INSERT INTO api_tokens (user_id, token)
VALUES (3, AES_ENCRYPT('ghp_s3cr3t', 'the-app-key'));

-- read back as text
SELECT CAST(AES_DECRYPT(token, 'the-app-key') AS CHAR) AS token
FROM api_tokens WHERE user_id = 3;
```

---

## Going further

### Subqueries

A query can be used inside another. Two forms cover most needs:

```sql
-- a list to test against
SELECT username FROM users
WHERE user_id NOT IN (SELECT user_id FROM messages);

-- a single value
SELECT subject FROM messages
WHERE date_entered = (SELECT MAX(date_entered) FROM messages);
```

The first does the same as the `LEFT JOIN ... IS NULL` example and is often easier to read. The second finds the newest message without a `LIMIT`, which matters when several rows tie.

### Copying and updating across tables

Two combinations that show up when you reorganize data:

```sql
-- fill a new table from an existing one
INSERT INTO archived_messages (message_id, subject, body)
SELECT message_id, subject, body FROM messages WHERE date_entered < '2025-01-01';

-- update one table using values from another
UPDATE users AS u
INNER JOIN (SELECT user_id, COUNT(*) AS n FROM messages GROUP BY user_id) AS c USING (user_id)
SET u.post_count = c.n;
```

### UNION: stacking results

`UNION` glues the results of two `SELECT`s with the same columns into one list, removing duplicates (`UNION ALL` keeps them). It's also how you fake a `FULL JOIN`: a left join `UNION` a right join.

```sql
SELECT username AS name, 'user' AS kind FROM users
UNION
SELECT name, 'forum' FROM forums
ORDER BY name;
```

### Views: a saved query that acts like a table

```sql
CREATE VIEW message_list AS
SELECT m.message_id, m.subject, u.username, f.name AS forum, m.date_entered
FROM messages AS m
INNER JOIN users  AS u USING (user_id)
INNER JOIN forums AS f USING (forum_id);

SELECT * FROM message_list WHERE forum = 'MySQL' ORDER BY date_entered DESC;
```

A view stores no data; it re-runs its query each time. It's a clean way to keep a complicated join in one place and give PHP a simple table to read. The SQL exercises use views to check your queries.

### Joins from PHP

The join changes nothing on the PHP side. Each result row is an associative array keyed by the column names or aliases, which is why giving good aliases (`AS forum`) matters:

```php
$sql = 'SELECT m.subject, u.username, f.name AS forum
        FROM messages AS m
        INNER JOIN users  AS u USING (user_id)
        INNER JOIN forums AS f USING (forum_id)
        WHERE f.forum_id = ?
        ORDER BY m.date_entered DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute([$forumId]);
foreach ($stmt as $row) {
    echo '<li>' . htmlspecialchars($row['subject']) . ' by ' . htmlspecialchars($row['username']) . '</li>';
}
```

### Keeping joins and groups fast

The table-side rules (small types, `NOT NULL`, integer keys, which columns to index) are in the database design notes. On the query side:

- Join on columns of exactly the same type, character set and collation, or the index on them can't be used.
- Make sure the columns in `ON`, `WHERE`, `GROUP BY` and `ORDER BY` are indexed; `EXPLAIN` shows `type: ALL` for a table that is being scanned in full.
- Select the columns you need, not `*`. In a join, `*` returns every column of every table.

---

## Nice to know

> [!IMPORTANT]
> **`LIKE '%word%'` never uses an index.** It's fine on a few thousand rows and a problem on a few million. If users search free text, add a FULLTEXT index and use `MATCH ... AGAINST`.

> [!NOTE]
> **The optimizer may skip an index on purpose.** On a tiny table, or when most rows match, reading the whole table is cheaper than hopping through an index, so `EXPLAIN` shows `ALL` even though the index exists. Judge indexes on realistic amounts of data.

> [!CAUTION]
> **A `WHERE` on the right table turns a `LEFT JOIN` back into an inner join.** `LEFT JOIN messages m ... WHERE m.forum_id = 1` drops every user with no messages, because for them `m.forum_id` is `NULL`. Put the condition in the `ON` clause instead: `LEFT JOIN messages m ON u.user_id = m.user_id AND m.forum_id = 1`.

> [!WARNING]
> **Columns in `SELECT` must be grouped or aggregated.** `SELECT username, subject, COUNT(*) ... GROUP BY username` is an error on a modern server (`ONLY_FULL_GROUP_BY`). Older servers returned a random subject from each group, which was worse.

> [!WARNING]
> **`COUNT(*)` over a `LEFT JOIN` never returns 0.** An unmatched left row still produces one result row, so count the right table's key, `COUNT(m.message_id)`, to get 0 for empty groups.

> [!IMPORTANT]
> **`AVG` and `SUM` ignore `NULL`.** The average of `80, NULL, 90` is 85, not 56.7. If a missing value should count as zero, write `AVG(COALESCE(grade, 0))`.

> [!NOTE]
> **FULLTEXT ignores short and common words.** InnoDB skips words under 3 characters and a built-in stopword list (`the`, `and`, ...), so a search for `'a'` finds nothing. Natural-language mode on MyISAM also drops words that appear in more than half the rows; boolean mode doesn't.

> [!NOTE]
> **`START TRANSACTION` is not saved by phpMyAdmin.** Each statement you run in its SQL tab is committed on its own. To see transactions behave, use the mysql client or PHP.

> [!TIP]
> **Build joins one table at a time.** Get `SELECT * FROM messages m INNER JOIN users u ON ...` returning sensible rows, check the row count, then add the next join. A wrong `ON` condition usually shows up as the row count exploding or collapsing.

---

## Practice exercises

Modules 09 to 12 of the self-checking SQL exercises in [practice-material/sql](../practice-material/sql/README.md) cover this topic: joins, aggregates and grouping, conditional values with subqueries and FULLTEXT, and transactions with hashing. You write each query as a `CREATE VIEW` and the runner compares the view's rows with the expected result.

Pen-and-paper exercises:

1. **Predict the rows.** Using the sample data above, write down the exact result of `SELECT u.username, COUNT(*) FROM users u LEFT JOIN messages m USING (user_id) GROUP BY u.user_id`. Then fix the query so Grace shows 0.
2. **Three tables.** Write a query that lists each forum's name with the username of the person who posted its most recent message. Say which rows you'd expect for Modern Dance and why.
3. **WHERE or HAVING.** For each condition, say which clause it belongs in: messages posted in September; users with more than five posts; forums whose name starts with M; forums whose latest post is older than a year.
4. **Self join.** Given `courses(course_id, title, prerequisite_id)`, write a query that lists every course title next to its prerequisite's title, including courses with no prerequisite.
5. **Transaction design.** A user buys a ticket: a seat row must be marked taken, a ticket row inserted, and the user's balance reduced. Write the transaction, and say which single statement failing should cause a rollback, and what the PHP `catch` block should do.
6. **Search.** Explain the difference between `WHERE body LIKE '%cat%'`, `MATCH (body) AGAINST ('cat')`, and `MATCH (body) AGAINST ('+cat -dog' IN BOOLEAN MODE)`. Which one matches `concatenate`?
7. **Index or not.** `messages` has indexes on `(forum_id, date_entered)` and `(subject)`. For each query say whether an index can be used and which one: messages in forum 2; messages in forum 2 from September, newest first; all messages from September; subjects starting with "Help"; subjects containing "Help"; messages whose subject, lowercased, equals "help". Rewrite the ones that can't.

---

## Further Reading

| Topic | MySQL 8.4 Reference Manual |
|---|---|
| Joins | [join](https://dev.mysql.com/doc/refman/8.4/en/join.html), [Outer join simplification](https://dev.mysql.com/doc/refman/8.4/en/outer-join-simplification.html) |
| Aggregate functions | [aggregate-functions](https://dev.mysql.com/doc/refman/8.4/en/aggregate-functions.html) |
| `GROUP BY` and `HAVING` | [group-by-handling](https://dev.mysql.com/doc/refman/8.4/en/group-by-handling.html), [select](https://dev.mysql.com/doc/refman/8.4/en/select.html) |
| `IF`, `CASE`, `COALESCE` | [flow-control-functions](https://dev.mysql.com/doc/refman/8.4/en/flow-control-functions.html), [COALESCE](https://dev.mysql.com/doc/refman/8.4/en/comparison-operators.html#function_coalesce) |
| Subqueries | [subqueries](https://dev.mysql.com/doc/refman/8.4/en/subqueries.html) |
| `UNION` | [union](https://dev.mysql.com/doc/refman/8.4/en/union.html) |
| `INSERT ... SELECT` and multi-table `UPDATE` | [insert-select](https://dev.mysql.com/doc/refman/8.4/en/insert-select.html), [update](https://dev.mysql.com/doc/refman/8.4/en/update.html) |
| Views | [create-view](https://dev.mysql.com/doc/refman/8.4/en/create-view.html) |
| Indexes and `WHERE` | [range-optimization](https://dev.mysql.com/doc/refman/8.4/en/range-optimization.html), [order-by-optimization](https://dev.mysql.com/doc/refman/8.4/en/order-by-optimization.html), [explain-output](https://dev.mysql.com/doc/refman/8.4/en/explain-output.html) |
| FULLTEXT search | [fulltext-search](https://dev.mysql.com/doc/refman/8.4/en/fulltext-search.html), [fulltext-boolean](https://dev.mysql.com/doc/refman/8.4/en/fulltext-boolean.html) |
| Transactions | [commit](https://dev.mysql.com/doc/refman/8.4/en/commit.html), [implicit-commit](https://dev.mysql.com/doc/refman/8.4/en/implicit-commit.html) |
| `SHA2`, `AES_ENCRYPT` | [encryption-functions](https://dev.mysql.com/doc/refman/8.4/en/encryption-functions.html) |
| Optimizing queries | [select-optimization](https://dev.mysql.com/doc/refman/8.4/en/select-optimization.html), [explain](https://dev.mysql.com/doc/refman/8.4/en/explain.html) |

| Topic | PHP Manual |
|---|---|
| Transactions from PHP | [PDO::beginTransaction](https://www.php.net/manual/en/pdo.begintransaction.php) |
