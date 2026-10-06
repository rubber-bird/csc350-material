# CSC 350 - Practice material

Three sets of self-checking exercises: files with `your code here` markers and tests that turn green as you fill them in. Do them in this order, alongside the [lecture notes](../lecture-notes/readme.md).

| # | Folder | Exercises | Needs | Start here |
|:---:|---|:---:|---|---|
| 1 | [javascript/](javascript/README.md) | 16 | a browser | open `javascript/index.html` |
| 2 | [php/](php/README.md) | 16 | XAMPP with Apache | http://localhost/php-fundamentals/ |
| 3 | [sql/](sql/README.md) | 12 | XAMPP with Apache and MySQL | http://localhost/sql-fundamentals/ |

All three use the same test vocabulary (`describe`, `it`, `expect`). Each folder's README explains its exercises and how to read a failing test.

## PHP and SQL need XAMPP

PHP runs on a web server, so those two sets can't be opened as plain files. Apache serves whatever is in XAMPP's `htdocs` folder:

| System | `htdocs` |
|---|---|
| Windows | `C:\xampp\htdocs` |
| macOS | `/Applications/XAMPP/htdocs` |
| Linux | `/opt/lampp/htdocs` |

1. Copy the `php` folder into `htdocs` and rename the copy `php-fundamentals`. Do the same with `sql` as `sql-fundamentals`.
2. Start **Apache** in the XAMPP control panel (and **MySQL** for the SQL set).
3. Open http://localhost/php-fundamentals/ or http://localhost/sql-fundamentals/.

Edit the files in `exercises/`, save, refresh. The SQL runner owns a database called `sql_fundamentals` and wipes it on every run.

Anything else in `htdocs` works the same way: `htdocs/club/index.php` is http://localhost/club/index.php, which is how the term project will run.
