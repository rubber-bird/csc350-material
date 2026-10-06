# PHP Fundamentals

Sixteen `.php` files of small functions to fill in, from arithmetic to a complete small application with registration, login and a JSON API. They run inside XAMPP, so you also learn how PHP is served by a real web server.

## Setup

1. Install [XAMPP](https://www.apachefriends.org) and start **Apache** in its control panel.
2. Copy this folder into `htdocs` and name it `php-fundamentals`:
   - Windows: `C:\xampp\htdocs\php-fundamentals`
   - Mac: `/Applications/XAMPP/htdocs/php-fundamentals`
3. Open http://localhost/php-fundamentals/.

Every module shows red failing tests. Edit the files in `exercises/`, save, refresh, make them green.

## How it works

- Each file is a list of functions with `// your code here` inside. The comment above each one gives the task, its **Input**, **Output** and **Examples**.
- Files have EASY, MEDIUM and HARD sections and start with links to the PHP manual pages for the built-in functions you need.
- Module 08 has a second page, http://localhost/php-fundamentals/playground.php, that renders your HTML functions with real data.
- Module 13 uses an in-memory SQLite database the tests create themselves, so nothing extra to start. Module 16 builds on module 15, so do 15 first.

## The modules

| # | File | Covers |
|---|------|--------|
| 01 | `01-numbers.php` | arithmetic, `%`, `round`, `intdiv` |
| 02 | `02-strings.php` | `strlen`, `strtoupper`, `substr`, `explode`, `implode` |
| 03 | `03-conditionals.php` | `if` / `elseif` / `else`, `match`, comparison |
| 04 | `04-loops.php` | `for`, `while`, `foreach`, building arrays |
| 05 | `05-arrays.php` | lists and named keys, `array_map`, `array_filter`, `usort` |
| 06 | `06-functions.php` | functions as values, closures, arrow functions |
| 07 | `07-objects.php` | classes, `$this`, `private`, inheritance, `static` |
| 08 | `08-html.php` | turning data into HTML, escaping user input |
| 09 | `09-records.php` | lists of records: `array_column`, multi-key sorting, grouping, pagination |
| 10 | `10-text.php` | slugs, truncation, a CSV parser, templates, regular expressions |
| 11 | `11-oop.php` | interfaces, abstract classes, exceptions, enums, `readonly`, iterators |
| 12 | `12-validation.php` | cleaning input, a registration validator, `password_hash`, CSRF, login throttling |
| 13 | `13-database.php` | PDO with prepared statements: insert, find, paging, update, delete, a transaction |
| 14 | `14-generators-trees.php` | generators and `yield`, lazy sequences, trees, an LRU cache, memoization |
| 15 | `15-routing.php` | `Request` and `Response`, a `Router`, flash messages, middleware |
| 16 | `16-app.php` | everything together: register, login, dashboard, logout and a JSON API, tested as browser-like flows |

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
✗ add(2, 3) -> 5
    Expected: 5
    Received: null
```

"Received: null" almost always means the function has no `return` yet. A red box saying "Parse error" means PHP could not read the file at all: check the line it names for a missing `;`, `$` or `}`.

## Hints

Every failing HARD test has a **Hint 1** you can click open. Hint 2, and then Hint 3, are inside it, each closer to the answer. In the terminal add `--hints`.

## Cheat sheet

| Thing | PHP | Manual |
|-------|-----|--------|
| Variable | `$name = "Ada";` | [variables](https://www.php.net/manual/en/language.variables.basics.php) |
| Join strings | `"Hi, " . $name` or `"Hi, $name"` | [strings](https://www.php.net/manual/en/language.types.string.php) |
| List, named keys | `$fruit = ["apple", "pear"]; $p = ["name" => "Ada"];` | [arrays](https://www.php.net/manual/en/language.types.array.php) |
| Add to a list | `$fruit[] = "plum";` | same |
| Strict compare | `$a === $b` (not `==`) | [comparison](https://www.php.net/manual/en/language.operators.comparison.php) |
| If | `if ($x > 1) { } elseif ($x < 0) { } else { }` | [if](https://www.php.net/manual/en/control-structures.elseif.php) |
| Loops | `foreach ($items as $item) { }`, `for ($i = 0; $i < 10; $i++) { }` | [foreach](https://www.php.net/manual/en/control-structures.foreach.php), [for](https://www.php.net/manual/en/control-structures.for.php) |
| Function | `function add($a, $b) { return $a + $b; }` | [functions](https://www.php.net/manual/en/language.functions.php) |
| Arrow function | `$double = fn($n) => $n * 2;` | [arrow functions](https://www.php.net/manual/en/functions.arrow.php) |
| Class and object | `class Dog { public function bark() { } }  $rex = new Dog(); $rex->bark();` | [classes](https://www.php.net/manual/en/language.oop5.basic.php) |
| Print | `echo "hello";` | [echo](https://www.php.net/echo) |

Any built-in function is at `https://www.php.net/` followed by its name, e.g. https://www.php.net/strlen.

## Under the hood

- `index.php` runs every test file on one page; `run.php` does the same in the terminal.
- `spec.php` is the tiny `describe` / `it` / `expect` runner, with the same names Mocha and Chai use in the JavaScript exercises.
- `solutions/` mirrors `exercises/` with reference answers. Remove it before sharing with students.
