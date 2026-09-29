# PHP Fundamentals

Eight exercise files from arithmetic to rendering a web page, ordered from
easiest to hardest. They run inside XAMPP, so you also learn how PHP is
served by a real web server. Nothing else to install.

## Setup

1. Install XAMPP from https://www.apachefriends.org and start **Apache**
   in the XAMPP control panel.
2. Copy this whole folder into XAMPP's `htdocs` folder:
   - Windows: `C:\xampp\htdocs\php-fundamentals`
   - Mac: `/Applications/XAMPP/htdocs/php-fundamentals`
3. Open http://localhost/php-fundamentals/ in your browser.

You should see every module with red failing tests. Your job is to turn
them green.

## How each exercise works

Every file in `exercises/` is full of small functions with
`// your code here` inside. The comment above each function explains the
task: a description, **Input**, **Output**, and **Examples**. Each file has
EASY, MEDIUM and HARD sections, and starts with a list of the built-in PHP
functions you will need, each linked to its page in the PHP manual.

Edit a file in `exercises/`, save, and refresh the browser. Click a module
name at the top of the page to see only that module.

## The order

| # | File | Covers |
|---|------|--------|
| 01 | `01-numbers.php` | arithmetic, `%`, `round`, `intdiv` |
| 02 | `02-strings.php` | `strlen`, `strtoupper`, `substr`, `explode`, `implode` |
| 03 | `03-conditionals.php` | if / elseif / else, `match`, comparison |
| 04 | `04-loops.php` | for, while, foreach, building arrays |
| 05 | `05-arrays.php` | lists and named keys, `array_map`, `array_filter`, `usort` |
| 06 | `06-functions.php` | functions as values, closures, arrow functions |
| 07 | `07-objects.php` | classes, `$this`, private, inheritance, static |
| 08 | `08-html.php` | turning data into HTML, escaping user input |

All paths are inside `exercises/`.

Module 08 has a second page: http://localhost/php-fundamentals/playground.php
renders your HTML functions with real data. Add `?name=Ada` to the address,
click the nav links, and submit the form to see them react.

## Running in the terminal

XAMPP includes the `php` command. It is not on your PATH by default, so
use the full path, from inside this folder:

```
# Windows
C:\xampp\php\php.exe run.php        # every module
C:\xampp\php\php.exe run.php 03     # just module 03

# Mac
/Applications/XAMPP/bin/php run.php
/Applications/XAMPP/bin/php run.php 03
```

## Reading a failing test

```
✗ add(2, 3) -> 5
    Expected: 5
    Received: null
```

"Received: null" almost always means the function has no `return` yet.

## Stuck on a HARD one?

Every failing HARD test has a **Hint 1** you can click open. Inside it is
Hint 2, and inside that Hint 3, which is close to the full answer. Open
them one at a time and try again after each. In the terminal, add
`--hints` to print them:

```
/Applications/XAMPP/bin/php run.php 05 --hints
```

A red box that mentions "Parse error" or "syntax error" means PHP could not
read the file at all. Check the line number it names: a missing `;`, `$`,
or `}` is the usual cause.

## PHP cheat sheet

| Thing | PHP | Manual |
|-------|-----|--------|
| Variable | `$name = "Ada";` | [variables](https://www.php.net/manual/en/language.variables.basics.php) |
| Join strings | `"Hi, " . $name` or `"Hi, $name"` | [strings](https://www.php.net/manual/en/language.types.string.php) |
| List | `$fruit = ["apple", "pear"]; $fruit[0]` | [arrays](https://www.php.net/manual/en/language.types.array.php) |
| Named keys | `$p = ["name" => "Ada"]; $p["name"]` | [arrays](https://www.php.net/manual/en/language.types.array.php) |
| Add to a list | `$fruit[] = "plum";` | [arrays](https://www.php.net/manual/en/language.types.array.php) |
| Strict compare | `$a === $b` (use this, not `==`) | [comparison](https://www.php.net/manual/en/language.operators.comparison.php) |
| If | `if ($x > 1) { } elseif ($x < 0) { } else { }` | [if](https://www.php.net/manual/en/control-structures.elseif.php) |
| Loop over array | `foreach ($items as $item) { }` | [foreach](https://www.php.net/manual/en/control-structures.foreach.php) |
| Count loop | `for ($i = 0; $i < 10; $i++) { }` | [for](https://www.php.net/manual/en/control-structures.for.php) |
| Function | `function add($a, $b) { return $a + $b; }` | [functions](https://www.php.net/manual/en/language.functions.php) |
| Arrow function | `$double = fn($n) => $n * 2;` | [arrow functions](https://www.php.net/manual/en/functions.arrow.php) |
| Class | `class Dog { public $name; public function bark() { } }` | [classes](https://www.php.net/manual/en/language.oop5.basic.php) |
| New object | `$rex = new Dog(); $rex->bark();` | [classes](https://www.php.net/manual/en/language.oop5.basic.php) |
| Print | `echo "hello";` | [echo](https://www.php.net/echo) |

Any built-in function can be looked up at `https://www.php.net/` followed
by its name, for example https://www.php.net/strlen.

## Under the hood

- `index.php` finds every file in `tests/` and shows each one inside its
  own frame, so a syntax error in one exercise does not hide the others.
- `spec.php` is a tiny test runner that provides `describe`, `it` and
  `expect`, the same names Mocha and Chai use in the JavaScript exercises.
  It prints HTML in the browser and plain text in the terminal.
- `run.php` runs every test file in the terminal, each in its own PHP
  process.
- `solutions/` mirrors `exercises/` with reference answers. Remove it
  before sharing with students.
