# PHP - Lecture notes

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

PHP is a language that runs on the **web server**, not in the browser. When someone requests `page.php`, the server runs the PHP code inside it, and whatever the code prints (usually HTML) is what gets sent back. The visitor never sees your PHP, only its output. That's the opposite of JavaScript, which is sent to the browser and runs there.

This is what makes PHP the glue of the term project: it receives form data, checks it, talks to MySQL, and builds the HTML the user sees. These notes cover three stages:

1. **The language**: the basic script, printing, variables, strings, numbers, constants, quotes, debugging.
2. **Programming with PHP**: receiving HTML form data, conditionals, operators, validation, arrays, loops.
3. **Building real pages**: splitting code across files, one page that both shows and handles a form, sticky forms, writing your own functions, variable scope.

---

## Core Concepts

### How a PHP script runs

Three things have to be true, and forgetting any one of them is the most common beginner problem:

1. The file ends in **`.php`**, so the server knows to run it.
2. The file sits in the web server's **document root** (for XAMPP, the `htdocs` folder), where the server can find it.
3. You open it **through a URL** that starts with `http://` (for example `http://localhost/signup.php`), not by double-clicking the file. A URL starting with `file://` or `C:\` bypasses the server, so the PHP never runs and you see the raw code or a blank page.

Inside the file, PHP code lives between `<?php` and `?>`. Anything outside those tags is plain HTML and is sent to the browser as-is ([PHP tags](https://www.php.net/manual/en/language.basic-syntax.phptags.php)).

```php
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Basic PHP Page</title>
</head>
<body>
  <p>This is standard HTML.</p>
<?php
echo '<p>This was generated using PHP!</p>';
?>
</body>
</html>
```

### Sending output: `echo` and `print`

`echo` and `print` send text to the browser. They do the same job; `echo` is more common. Each statement ends with a **semicolon**. The text can contain HTML tags, which the browser then renders like any other HTML.

```php
echo 'Hello, world!';
echo '<p>Hello, <b>world</b>!</p>';
print "What's new?";
```

Function names in PHP are case-insensitive (`ECHO` works), but don't rely on that. Write them lowercase ([`echo`](https://www.php.net/manual/en/function.echo.php)).

### Single quotes are literal, double quotes are interpreted

This is the rule to memorize. Both kinds of quotes make a string, but they treat the contents differently:

| | `'single quotes'` | `"double quotes"` |
|---|---|---|
| Variables inside (`$name`) | printed as the literal characters `$name` | replaced with the variable's value |
| Escape sequences (`\n`, `\t`) | not interpreted (only `\'` and `\\` work) | turned into newline, tab, and so on |
| Speed | very slightly faster | very slightly slower |

```php
$var = 'test';
echo "var is equal to $var";     // var is equal to test
echo 'var is equal to $var';     // var is equal to $var
echo "\$var is equal to $var";   // $var is equal to test
```

Rule of thumb: use single quotes unless you need a variable or an escape sequence inside the string ([Strings](https://www.php.net/manual/en/language.types.string.php)).

### Escaping quotes

A quote character inside a string of the same kind ends the string early and causes a parse error. Two fixes: switch to the other kind of quote, or put a backslash before it.

```php
echo "She said, "How are you?"";    // Error!
echo 'I'm just ducky.';             // Error!

echo 'She said, "How are you?"';    // fine: different quote type inside
echo "I'm just ducky.";             // fine

echo "She said, \"How are you?\"";  // fine: escaped
echo 'I\'m just ducky.';            // fine: escaped
```

### Comments

PHP has three comment styles. PHP comments never reach the browser. HTML comments do, and anyone can read them in the page source.

```php
<!-- HTML comment: visible in "view source" -->
<?php
# single line
// another single line
/* multiple
   lines */
?>
```

### Variables

A variable starts with `$`, then letters, numbers and underscores, with no digit right after the `$`. Names are **case-sensitive**: `$Name` and `$name` are two different variables. You don't declare a type; PHP figures it out from the value. Assignment uses `=`.

```php
$first_name = 'Tobias';
$n = 3.14;
$is_admin = false;
```

The types you'll meet: boolean, integer, floating point, string, array, object, resource, and NULL. The first four are **scalar** (one value). Arrays and objects hold many values ([Variable basics](https://www.php.net/manual/en/language.variables.basics.php)).

### Strings: joining with the dot

The concatenation operator is a **period**, not `+`. `.=` appends to an existing string.

```php
$city = 'Seattle';
$state = 'Washington';
$address = $city . ', ' . $state;    // Seattle, Washington

// Same thing, built up step by step:
$address = $city;
$address .= ', ';
$address .= $state;
```

Printing a variable inside double quotes is often shorter than concatenating: `echo "Hello, $first_name";` ([String operators](https://www.php.net/manual/en/language.operators.string.php)).

### Numbers

Numbers are written without quotes and without commas: `8`, `3.14`, `-4.29048`, `4.4e2`. The arithmetic operators are the usual `+ - * /`, plus `%` (remainder), `++` (add one) and `--` (subtract one).

Two functions for presenting numbers:

- **`round($n, $decimals)`** rounds. `round(3.142857, 3)` is `3.143`. With no second argument it rounds to a whole number.
- **`number_format($n, $decimals)`** adds thousands separators and fixes the decimals. `number_format(20943, 2)` is `20,943.00`. It returns a **string**, so do your math first and format last.

### Constants

A constant is a named value that never changes. Define it with `define()`, and use it **without** a dollar sign. By convention the name is all uppercase.

```php
define('USERNAME', 'troutocity');
define('PI', 3.14);

echo 'Hello, ' . USERNAME;    // works
echo "Hello, USERNAME";       // Won't work! Prints the word USERNAME
```

Constants can only hold scalar values and can't be used inside double-quoted strings. Concatenate them instead ([Constants](https://www.php.net/manual/en/language.constants.php)).

### Receiving form data

Three things in the HTML form decide how PHP handles it:

1. **`action`**: which PHP file receives the data.
2. **`method`**: `get` or `post`.
3. The **`name`** of each field: the key PHP uses to look the value up.

```html
<form action="handle_form.php" method="post">
  <p><label>Name: <input type="text" name="name" size="20" maxlength="40"></label></p>
  <p><label>Email: <input type="text" name="email" size="40" maxlength="60"></label></p>
  <p><input type="submit" name="submit" value="Submit My Information"></p>
</form>
```

In `handle_form.php` the values arrive in a **superglobal array**: `$_POST` for a POST form, `$_GET` for a GET form. `$_REQUEST` contains both (plus cookies), which is convenient at first but ambiguous, so prefer the specific one.

```php
$name  = $_POST['name'];
$email = $_POST['email'];
echo "<p>Thank you, <b>$name</b>. We will reply to you at <i>$email</i>.</p>";
```

The keys are case-sensitive and must match the `name` attributes exactly.

### GET versus POST

| | GET | POST |
|---|---|---|
| Where the data goes | appended to the URL (`?name=Jon&email=...`) | in the body of the request, not visible in the URL |
| Can be bookmarked or shared | yes | no |
| Size limit | small (URL length) | large |
| Can upload files | no | yes |
| Clicking Back | just works | browser warns about resubmitting |
| Use it for | **requesting information**: searches, page 2 of results | **requesting an action**: registering, saving a record, sending email |

Simply loading a page is a GET. Search engines use GET. Anything that changes data on the server should be POST.

### Conditionals and what counts as true

```php
if ($condition) {
    // Do this!
} elseif ($other_condition) {
    // Do that!
} else {
    // Do whatever!
}
```

`else` and `elseif` are optional. PHP decides truth loosely: a variable is **false** if it holds `0`, `0.0`, an empty string `''`, the string `'0'`, an empty array, `NULL`, or `false`. Everything else is true. Two functions you'll use in every form handler:

- **`isset($var)`** is true if the variable exists and is not `NULL`. It's true even for `0`, `false`, or `''`.
- **`empty($var)`** is true if the variable doesn't exist *or* holds a false-ish value. `!empty($var)` therefore means "has a real, non-empty value."

The comparison operators are `==`, `!=`, `<`, `>`, `<=`, `>=`. The logical ones are `!` (not), `&&` or `AND`, `||` or `OR`, and `XOR`. Use parentheses to group. And watch the difference between `=` (assign) and `==` (compare): `if ($x = 5)` assigns 5 and is always true ([Comparison operators](https://www.php.net/manual/en/language.operators.comparison.php)).

### Never trust external data

Anything from a form, a URL, or a cookie was typed by a stranger. The checklist:

1. **`isset()`** to confirm the field was submitted at all.
2. **`!empty()`** to confirm it isn't blank.
3. Check the **type** where it matters, for example `is_numeric($age)`.
4. Check the **value** where it matters, for example an age between 1 and 120.

The [Practical Examples](#practical-examples) show a full handler built on these.

### Arrays

An array holds many values in one variable, as **key/value pairs**. **Indexed** arrays use numbers as keys, starting at 0. **Associative** arrays use strings.

```php
// Indexed: keys assigned automatically (0, 1, 2)
$artists = array('Clem Snide', 'Shins', 'Eels');
$artists = ['Clem Snide', 'Shins', 'Eels'];   // same thing, shorter syntax

// Associative
$states = array('IA' => 'Iowa', 'MD' => 'Maryland');

// Adding one element at a time
$band[] = 'Jemaine';        // key 0
$band[] = 'Bret';           // key 1
$band['fan'] = 'Mel';
$band['fan'] = 'Dave';      // overwrites 'Mel'

$ten = range(1, 10);        // 1, 2, ..., 10
```

Access one element with square brackets: `$artists[0]`, `$states['MD']`. An array's value can itself be an array; that's a **multidimensional** array (`$fruit[3] = $band;`).

Printing is where arrays trip people up:

```php
echo "My list of states: $states";      // prints "Array", not the contents
echo "IL is $states['IL'].";            // BAD! parse error
echo "IL is {$states['IL']}.";          // good: braces around array access in a string
echo 'IL is ' . $states['IL'] . '.';    // good: concatenation
```

The **superglobals** `$_GET`, `$_POST`, `$_REQUEST`, `$_SERVER`, `$_ENV`, `$_SESSION`, and `$_COOKIE` are all associative arrays that PHP fills in for you ([Arrays](https://www.php.net/manual/en/language.types.array.php), [Superglobals](https://www.php.net/manual/en/language.variables.superglobals.php)).

### Loops

**`foreach`** visits every element of an array. It's the loop you'll use most.

```php
foreach ($states as $value) {
    echo "$value<br>";
}
foreach ($states as $key => $value) {
    echo "The value at $key is $value.<br>";
}
```

**`while`** runs as long as a condition holds. Use it when you don't know in advance how many times, such as reading rows from a database query.

```php
while ($condition) {
    // Do something, and eventually make $condition false.
}
```

**`for`** runs a known number of times. The three parts are: run once at the start; check before each pass; run after each pass.

```php
for ($i = 1; $i <= 10; $i++) {
    echo $i;
}
```

### Sorting, and converting between arrays and strings

| Function | Sorts by | Keeps keys? |
|---|---|---|
| `sort()` | value | no (renumbers from 0) |
| `rsort()` | value, descending | no |
| `asort()` | value | yes |
| `arsort()` | value, descending | yes |
| `ksort()` | key | yes |
| `krsort()` | key, descending | yes |

Use `asort`/`ksort` on associative arrays, where the keys mean something. `sort` throws them away ([Sorting arrays](https://www.php.net/manual/en/array.sorting.php)).

**`explode(separator, $string)`** splits a string into an array. **`implode(glue, $array)`** joins an array into a string.

```php
$s1 = 'Mon-Tue-Wed-Thu-Fri';
$days = explode('-', $s1);       // ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']
$s2 = implode(', ', $days);      // 'Mon, Tue, Wed, Thu, Fri'
```

### Splitting code across files

`include` and `require` pull another file into the current script at that point. Use them for a shared header, footer, or database connection so you write it once.

| Function | If the file is missing |
|---|---|
| `include('file.php')` | warning, script keeps going |
| `require('file.php')` | fatal error, script stops |
| `include_once()`, `require_once()` | same as above, but a file already included is skipped |

Use `require` for things the page can't work without (the database connection). Use `include` for optional pieces. The included file is treated as HTML unless its code is inside `<?php ?>` tags, and the extension doesn't matter ([`include`](https://www.php.net/manual/en/function.include.php), [`require`](https://www.php.net/manual/en/function.require.php)).

**Paths.** An **absolute** path starts from a fixed place (`/` or `C:\`) and is correct no matter which file uses it. A **relative** path starts from the current file's folder (`header.html`, `includes/header.html`, `../header.html`) and only works if the files keep the same relative positions.

### One page that shows and handles the form

Instead of `form.html` posting to `handle.php`, a single `form.php` can do both. It checks whether it was reached by a form submission:

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form.
} else {
    // Display the form.
}
```

`$_SERVER['REQUEST_METHOD']` is `'GET'` when the user first opens the page and `'POST'` after they submit (assuming the form uses `method="post"` and its `action` is the page itself). In practice you display the form in both cases, and only *also* handle it on POST, which is how you get sticky forms.

### Sticky forms

A **sticky form** remembers what the user typed, so when validation fails they don't have to start over. Print the submitted values back into the fields:

```php
<input type="text" name="city" value="<?php echo $city; ?>">

<input type="radio" name="gender" value="F" <?php if ($gender == 'F') { echo 'checked'; } ?>>

<textarea name="comments" rows="10" cols="50"><?php echo $comments; ?></textarea>
```

Text inputs use `value`; radio buttons and checkboxes need `checked`; textareas take the text between the tags.

### Writing your own functions

```php
function greet($name, $msg = 'Hello') {
    echo "$msg, $name!";
}

greet('Zoe');                  // Hello, Zoe!
greet('Sam', 'Good evening');  // Good evening, Sam!
```

- The word `function`, a name, parentheses, and the body in `{ }`.
- Names follow the same rules as variables (no leading digit) and are case-insensitive.
- **Arguments** in the parentheses receive the values passed at the call.
- A **default value** (`$msg = 'Hello'`) makes that argument optional. Required arguments without a value cause an error.
- **`return`** hands a value back to the caller and stops the function immediately. A function can have several `return` statements (in different branches) but only one ever runs per call.

```php
function find_sign($month, $day) {
    // ... work out the zodiac sign ...
    return $sign;
}

$my_sign = find_sign('October', 23);
echo find_sign('October', 23);
```

Good reasons to write a function: the same code appears more than once; a piece of logic is complicated or sensitive and deserves its own box; you want to reuse it on other pages ([Functions](https://www.php.net/manual/en/language.functions.php)).

### Variable scope

**Scope** is where a variable can be seen. A variable created in the main page is invisible inside a function, and a variable created inside a function disappears when the function returns. That's on purpose: it stops functions from accidentally clobbering each other's variables.

```php
$var = 20;

function show() {
    echo $var;          // Warning: undefined variable. $var is out of scope here.
}

function show_global() {
    global $var;        // pull the page-level $var into the function
    echo $var;          // 20
}
```

`global` works, but passing the value in as an argument and getting a result out via `return` is cleaner and easier to debug ([Variable scope](https://www.php.net/manual/en/language.variables.scope.php)).

### Reading the PHP manual

Every function page at [php.net](https://www.php.net/manual/en/) has the same layout: the name, the PHP versions it exists in, a description, then a signature showing the return type and the arguments. Optional arguments are in square brackets. For example:

```
round(int|float $num, int $precision = 0): float
```

That says: `round` takes a number and an optional precision (default 0) and returns a float. Learning to read these means you never have to memorize argument orders.

### Debugging

A checklist:

1. **Always run scripts through a URL.** Not `file://`.
2. **Know your PHP version.** `<?php phpinfo(); ?>` tells you.
3. **Enable `display_errors`** in development so PHP shows the error instead of a blank page.
4. **Check the HTML source** in the browser. PHP output that's wrapped in a broken tag can be invisible on the page but obvious in the source.
5. **Trust the error message.** A parse error's line number is usually right, or one line past the real problem (a missing semicolon on line 8 is reported on line 9).
6. **Take a break.**

**Parse errors** come from a missing semicolon, mismatched quotes, brackets or parentheses, or a misspelled function name. None of the script runs until they're fixed. `var_dump($x)` prints a variable's type and value, which is the quickest way to see what you're really holding.

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Server-side** | code that runs on the web server before the page is sent. The browser only sees the output. |
| **Document root** | the folder the web server serves files from (`htdocs` in XAMPP) |
| **Parse error** | a syntax mistake. Nothing runs until it's fixed. |
| **Scalar** | a single value: boolean, integer, float, or string |
| **Superglobal** | a built-in associative array available everywhere: `$_GET`, `$_POST`, `$_SERVER`, ... |
| **Indexed array** | keys are numbers starting at 0 |
| **Associative array** | keys are strings |
| **Multidimensional array** | an array whose values are arrays |
| **Sticky form** | a form that refills itself with what the user already typed |
| **Argument** | a value passed into a function |
| **Return value** | the value a function hands back |
| **Scope** | where a variable is visible: the page, or inside one function |
| **Constant** | a named value that can't change, used without `$` |

### Syntax at a glance

| Piece | Syntax |
|---|---|
| PHP block | `<?php ... ?>` |
| Print | `echo 'text';` |
| Variable | `$name = 'value';` |
| Constant | `define('NAME', 'value');` then `NAME` |
| Concatenate | `$a . $b` and `$a .= $b` |
| Comment | `// line`, `# line`, `/* block */` |
| Array (indexed) | `['a', 'b']` or `array('a', 'b')` |
| Array (associative) | `['k' => 'v']` |
| Array element | `$arr[0]`, `$arr['k']` |
| Array in a string | `"{$arr['k']}"` |
| If | `if (cond) { } elseif (cond) { } else { }` |
| Foreach | `foreach ($arr as $k => $v) { }` |
| For | `for ($i = 0; $i < 10; $i++) { }` |
| While | `while (cond) { }` |
| Function | `function name($arg, $opt = 'default') { return $x; }` |
| Include | `require('file.php');` |

### Operators

| Operator | Meaning | Type |
|---|---|---|
| `+`, `-`, `*`, `/`, `%` | add, subtract, multiply, divide, remainder | arithmetic |
| `++`, `--` | add one, subtract one | arithmetic |
| `.` | join strings | string |
| `=` | assign | assignment |
| `.=`, `+=`, `-=` | combine an operation with assignment | assignment |
| `==`, `!=` | equal, not equal (loose) | comparison |
| `===`, `!==` | equal, not equal (same type too) | comparison |
| `<`, `>`, `<=`, `>=` | less, greater, less or equal, greater or equal | comparison |
| `!` | not | logical |
| `&&`, `AND` | and | logical |
| `\|\|`, `OR` | or | logical |
| `XOR` | one or the other, not both | logical |

### Escape sequences (double-quoted strings)

| Code | Meaning |
|---|---|
| `\"` | double quote |
| `\'` | single quote |
| `\\` | backslash |
| `\n` | newline |
| `\r` | carriage return |
| `\t` | tab |
| `\$` | literal dollar sign |

### `isset` vs `empty`

| `$var` holds | `isset($var)` | `empty($var)` |
|---|:---:|:---:|
| `'Jon'` | true | false |
| `0` or `'0'` | true | **true** |
| `''` (empty string) | true | true |
| `false` | true | true |
| `NULL` | false | true |
| not defined at all | false | true |

The `0` row matters: a user who enters 0 as a quantity passes `isset` but fails `!empty`. Use `isset` plus `is_numeric` for numeric fields.

### Common functions

| Function | Does |
|---|---|
| [`round($n, $d)`](https://www.php.net/manual/en/function.round.php) | rounds to `d` decimals |
| [`number_format($n, $d)`](https://www.php.net/manual/en/function.number-format.php) | formats with commas and `d` decimals; returns a string |
| [`define('NAME', $v)`](https://www.php.net/manual/en/function.define.php) | creates a constant |
| [`isset($v)`](https://www.php.net/manual/en/function.isset.php) | variable exists and is not NULL |
| [`empty($v)`](https://www.php.net/manual/en/function.empty.php) | variable is missing or false-ish |
| [`is_numeric($v)`](https://www.php.net/manual/en/function.is-numeric.php) | value is a number or numeric string |
| [`range($a, $b)`](https://www.php.net/manual/en/function.range.php) | array of numbers from `a` to `b` |
| [`sort()` family](https://www.php.net/manual/en/array.sorting.php) | sorts an array in place |
| [`explode($sep, $s)`](https://www.php.net/manual/en/function.explode.php) | string to array |
| [`implode($glue, $arr)`](https://www.php.net/manual/en/function.implode.php) | array to string |
| [`include`, `require`](https://www.php.net/manual/en/function.require.php) | pull in another file |
| [`var_dump($v)`](https://www.php.net/manual/en/function.var-dump.php) | prints type and value, for debugging |
| [`htmlspecialchars($s)`](https://www.php.net/manual/en/function.htmlspecialchars.php) | makes user text safe to echo into HTML (see [Nice to know](#nice-to-know)) |

---

## Practical Examples

These build toward the club sign-up form from the HTML notes.

### Hello, and some arithmetic

```php
<?php // numbers.php

$quantity = 30;
$price = 119.95;
$taxrate = .05;    // 5% sales tax

$total = $quantity * $price;
$total = $total + ($total * $taxrate);
$total = number_format($total, 2);    // format last: this returns a string

echo '<p>You are purchasing <b>' . $quantity . '</b> widget(s) at <b>$' . $price .
     '</b> each. With tax, the total comes to <b>$' . $total . '</b>.</p>';
?>
```

### A form and a separate handler

`signup.html`:

```html
<form action="handle_signup.php" method="post">
  <p><label>First name: <input type="text" name="first_name" size="20" maxlength="30"></label></p>
  <p><label>Email: <input type="text" name="email" size="40" maxlength="80"></label></p>
  <p>Year:
    <label><input type="radio" name="year" value="1"> First</label>
    <label><input type="radio" name="year" value="2"> Second</label>
  </p>
  <p><label>Comments: <textarea name="comments" rows="3" cols="40"></textarea></label></p>
  <p><input type="submit" name="submit" value="Join"></p>
</form>
```

`handle_signup.php`:

```php
<?php
// Shorthand for the form data. Keys match the name="" attributes exactly.
$first_name = $_POST['first_name'];
$email      = $_POST['email'];
$comments   = $_POST['comments'];

echo "<p>Thank you, <b>$first_name</b>. We'll reply to you at <i>$email</i>.</p>";
echo "<p>You wrote: $comments</p>";
?>
```

### Validating the same form

```php
<?php
$errors = [];    // collect problems, then decide

if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
    $first_name = $_POST['first_name'];
} else {
    $errors[] = 'You forgot to enter your first name.';
}

if (!empty($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $errors[] = 'You forgot to enter your email.';
}

if (isset($_POST['year']) && is_numeric($_POST['year']) && $_POST['year'] >= 1 && $_POST['year'] <= 4) {
    $year = $_POST['year'];
} else {
    $errors[] = 'Please pick a year.';
}

if (empty($errors)) {
    echo "<p>Welcome, $first_name! You're in year $year.</p>";
} else {
    echo '<p>Please fix the following:</p><ul>';
    foreach ($errors as $msg) {
        echo "<li>$msg</li>";
    }
    echo '</ul>';
}
?>
```

Note the radio button check: an unchecked radio group sends *nothing*, so `$_POST['year']` doesn't exist and `isset` is what catches it.

### Working with arrays

```php
<?php
$majors = ['cs' => 'Computer Science', 'math' => 'Mathematics', 'bio' => 'Biology'];

// Build a <select> from an array, so adding a major is one line
echo '<select name="major">';
foreach ($majors as $code => $label) {
    echo "<option value=\"$code\">$label</option>";
}
echo '</select>';

// Sort by name but keep the codes
asort($majors);

// Members as a list
$members = [];
$members[] = 'Maria Lopez';
$members[] = 'Jon Park';
$members[] = 'Aisha Khan';
sort($members);
echo '<p>Members: ' . implode(', ', $members) . '</p>';   // Aisha Khan, Jon Park, Maria Lopez

// A multidimensional array: each member is itself an array
$roster = [
    ['name' => 'Maria Lopez', 'major' => 'bio', 'year' => 2],
    ['name' => 'Jon Park',    'major' => 'cs',  'year' => 1],
];
foreach ($roster as $student) {
    echo "<p>{$student['name']} studies {$majors[$student['major']]}.</p>";
}

// Loop a known number of times
for ($i = 1; $i <= 4; $i++) {
    echo "<option value=\"$i\">Year $i</option>";
}
?>
```

### One page: display, validate, and stay sticky

`signup.php` does the whole job. The form posts back to itself.

```php
<?php
// signup.php: shows the form, handles it on POST, and remembers what was typed.

require('includes/header.php');   // shared <html>, <head>, nav

// Start every field empty so the form works on the first (GET) visit.
$first_name = '';
$email = '';
$year = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $first_name = trim($_POST['first_name']);
    $email      = trim($_POST['email']);
    $year       = $_POST['year'] ?? '';        // '' if no radio was picked

    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    }
    if (!is_numeric($year)) {
        $errors[] = 'Pick a year.';
    }

    if (empty($errors)) {
        echo '<p>Thanks, ' . htmlspecialchars($first_name) . '. You are signed up.</p>';
        require('includes/footer.php');
        exit();                                // done: don't show the form again
    }
}

// Reached on the first visit, or on POST with errors.
foreach ($errors as $msg) {
    echo "<p class=\"error\">$msg</p>";
}
?>

<form action="signup.php" method="post">
  <p><label>First name:
    <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>"></label></p>

  <p><label>Email:
    <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"></label></p>

  <p>Year:
    <label><input type="radio" name="year" value="1" <?php if ($year == '1') echo 'checked'; ?>> First</label>
    <label><input type="radio" name="year" value="2" <?php if ($year == '2') echo 'checked'; ?>> Second</label>
  </p>

  <p><input type="submit" name="submit" value="Join"></p>
</form>

<?php require('includes/footer.php'); ?>
```

`includes/header.php` and `includes/footer.php` hold the opening and closing HTML shared by every page. Change the site's navigation once and every page picks it up.

### A function with arguments, a default, and a return value

```php
<?php
// Returns the club's dues for a given year, with an optional discount.
function calculate_dues($year, $discount = 0) {
    $base = 40;
    if ($year == 1) {
        $base = 25;              // first-years pay less
    }
    return $base - $discount;    // return stops the function here
}

echo calculate_dues(1);          // 25
echo calculate_dues(3);          // 40
echo calculate_dues(3, 10);      // 30

$owed = calculate_dues($year);
echo "<p>You owe $" . number_format($owed, 2) . ".</p>";
?>
```

`$base` exists only inside the function. The page can't see it, and the function can't see `$year` from the page unless it's passed in as an argument, which is exactly what the call does.

---

## Going further

### Getting set up

PHP needs a web server to run. The usual way to get one on your own machine is an all-in-one package: **XAMPP** (Windows, Mac, Linux), **MAMP** (Mac, Windows), or **AMPPS**. All of them install Apache (the web server), PHP, and MySQL together.

1. Install the package and start Apache (and MySQL, for later) from its control panel.
2. Find the **document root**: `htdocs` inside the XAMPP folder, or `htdocs` inside MAMP. Files you put here are served by the web server.
3. Create `htdocs/test.php` containing `<?php phpinfo(); ?>` and open `http://localhost/test.php` in your browser. (MAMP may use port 8888: `http://localhost:8888/test.php`.) If you see a long page of PHP settings, everything works. Delete the file afterwards; it reveals more than you want a public server to.
4. Make a folder for your project, `htdocs/club/`, and open pages as `http://localhost/club/index.php`.

If the browser shows your PHP source code, the file isn't being served through Apache. Check the URL starts with `http://localhost/` and the file ends in `.php`.

### String functions you'll need

| Function | Does | Example |
|---|---|---|
| [`strlen($s)`](https://www.php.net/manual/en/function.strlen.php) | length in bytes | `strlen('hello')` is `5` |
| [`trim($s)`](https://www.php.net/manual/en/function.trim.php) | removes spaces from both ends | `trim('  Jon  ')` is `'Jon'` |
| [`strtolower($s)`](https://www.php.net/manual/en/function.strtolower.php), `strtoupper($s)` | changes case | `strtolower('Jon')` is `'jon'` |
| [`ucfirst($s)`](https://www.php.net/manual/en/function.ucfirst.php), `ucwords($s)` | capitalises the first letter / every word | `ucwords('jon park')` is `'Jon Park'` |
| [`str_replace($find, $replace, $s)`](https://www.php.net/manual/en/function.str-replace.php) | replaces every occurrence | `str_replace('-', '/', '2026-09-01')` |
| [`str_contains($s, $needle)`](https://www.php.net/manual/en/function.str-contains.php) | is `$needle` in `$s`? (PHP 8) | `str_contains($email, '@')` |
| [`substr($s, $start, $len)`](https://www.php.net/manual/en/function.substr.php) | part of a string, from 0 | `substr('Maria', 0, 3)` is `'Mar'` |
| [`sprintf($format, ...)`](https://www.php.net/manual/en/function.sprintf.php) | builds a formatted string | `sprintf('%.2f', 3.14159)` is `'3.14'` |
| [`nl2br($s)`](https://www.php.net/manual/en/function.nl2br.php) | turns newlines into `<br>` | for showing a textarea's contents |

Always `trim()` form input before checking it. `'   '` passes `!empty()` but is not a name.

### `filter_var` for validation

Instead of writing your own email check, let PHP do it:

```php
$email = trim($_POST['email'] ?? '');

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // looks like an email address
} else {
    $errors[] = 'Please enter a valid email address.';
}

$year = filter_var($_POST['year'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 4]]);
if ($year === false) {
    $errors[] = 'Year must be 1 to 4.';
}
```

`filter_var` returns the cleaned value, or `false` if it doesn't pass. Note the `===`: a valid year of `0` would also be false-ish, so compare the type too ([`filter_var`](https://www.php.net/manual/en/function.filter-var.php), [validate filters](https://www.php.net/manual/en/filter.constants.php)).

### Two more ways to branch: `switch` and the ternary

```php
// switch: compare one value against several options
switch ($_POST['major']) {
    case 'cs':
        $dept = 'Computer Science';
        break;                       // without break, execution falls into the next case
    case 'math':
        $dept = 'Mathematics';
        break;
    default:
        $dept = 'Undeclared';
}

// match (PHP 8): the same, as an expression, no break needed
$dept = match ($_POST['major']) {
    'cs'    => 'Computer Science',
    'math'  => 'Mathematics',
    default => 'Undeclared',
};

// Ternary: a one-line if/else for picking a value
$greeting = ($hour < 12) ? 'Good morning' : 'Good afternoon';
```

([`switch`](https://www.php.net/manual/en/control-structures.switch.php), [`match`](https://www.php.net/manual/en/control-structures.match.php), [ternary operator](https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary))

### Dates and times

```php
echo date('Y-m-d');            // 2026-09-29
echo date('l, F j, Y');        // Monday, September 29, 2026
echo date('g:i a');            // 2:30 pm
$timestamp = time();           // seconds since 1970, useful for storing "when"
```

The format letters are listed under [`date()`](https://www.php.net/manual/en/function.date.php). Note they are different from MySQL's `DATE_FORMAT` codes. Set the time zone once at the top of your script or in `php.ini`: `date_default_timezone_set('America/New_York');`.

### Redirecting after a successful POST

After a form is handled successfully, don't just print "Thanks". If the user refreshes, the browser re-sends the POST and you get a duplicate record. Redirect to a normal GET page instead:

```php
if (empty($errors)) {
    // ... save to the database ...
    header('Location: thanks.php');
    exit();
}
```

`header()` must be called **before any output**, including a stray blank line above `<?php`. This is the "Post/Redirect/Get" pattern and you'll use it for every form that saves something ([`header`](https://www.php.net/manual/en/function.header.php)).

### Sessions: remembering who is logged in

HTTP forgets everything between requests. A **session** is PHP's way of remembering a user from one page to the next, and it's how a login works. The term project needs this.

```php
<?php
session_start();                          // first line of every page that uses sessions, before any output

// After checking the username and password against the database:
$_SESSION['user_id'] = 42;
$_SESSION['first_name'] = 'Maria';

// On any later page:
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');        // not logged in: send them to the login page
    exit();
}
echo 'Welcome back, ' . htmlspecialchars($_SESSION['first_name']);

// Logging out:
session_start();
$_SESSION = [];
session_destroy();
```

`$_SESSION` is a superglobal array like `$_POST`, but it persists across pages for that visitor. PHP stores the data on the server and gives the browser a cookie with an ID to find it again ([Sessions](https://www.php.net/manual/en/book.session.php)).

### Passwords

Never store a password as typed, and don't use `md5()` or `sha1()` either: they're fast, which is exactly what an attacker wants. PHP has the right tools built in:

```php
// When registering:
$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
// store $hash in the database (VARCHAR(255))

// When logging in:
if (password_verify($_POST['password'], $hash_from_database)) {
    // correct password
}
```

([`password_hash`](https://www.php.net/manual/en/function.password-hash.php), [`password_verify`](https://www.php.net/manual/en/function.password-verify.php), OWASP [Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html))

### A small function library

Put helpers you use on every page in one file and `require` it. Two that pay for themselves immediately:

```php
<?php
// includes/functions.php

// Escape a value for safe output in HTML.
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Read a POST field, trimmed, or a default if it's missing.
function post($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}
```

Then `value="<?= e(post('city')) ?>"` in a sticky form does the reading, trimming, and escaping in one readable line.

---

## Nice to know

> [!CAUTION]
> **Echoing user input straight into HTML is a security hole.** The simple handler above prints `$name` exactly as typed. If a user types `<script>...</script>` into the name field, it runs in every browser that views the output. This is **cross-site scripting (XSS)**. Wrap anything that came from a user in `htmlspecialchars()` before echoing it, as the sticky-form example above does. OWASP's [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html) has the details. (I think you will cover this later in the course, but it's a one-function habit worth starting now.)

> [!WARNING]
> **`==` compares loosely.** PHP converts types before comparing, so `'abc' == 0` was true in older versions and `'1' == '01'` is still true. Use `===` when the type matters (it usually does), and read [PHP type comparison tables](https://www.php.net/manual/en/types.comparisons.php) once so the surprises aren't surprises.

> [!WARNING]
> **Missing arguments are a fatal error in PHP 8.** Older books say calling a function without all its required arguments gives a warning. Since PHP 8.0 it throws an `ArgumentCountError` and stops the script. Give optional arguments a default value.

> [!IMPORTANT]
> **Prefer `$_POST` and `$_GET` over `$_REQUEST`.** `$_REQUEST` merges GET, POST, and cookie data, so you can't tell where a value came from, and a cookie with the same name can silently override your form field. Match the array to the form's `method`.

> [!IMPORTANT]
> **`display_errors` is for development only.** Error messages reveal file paths and code. Turn it on locally (XAMPP defaults to on) and off on any public server, logging errors to a file instead ([display_errors](https://www.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)).

> [!TIP]
> **`<?= $x ?>` is shorthand for `<?php echo $x; ?>`.** It's always available and makes sticky forms much easier to read: `value="<?= htmlspecialchars($city) ?>"`.

> [!TIP]
> **`??` gives a default for a missing key.** `$year = $_POST['year'] ?? '';` reads "use the posted year, or an empty string if it wasn't sent." It replaces the `isset()` dance for the common case ([null coalescing operator](https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.coalesce)).

> [!NOTE]
> **You'll see an XHTML 1.0 doctype and `<br />` in older PHP examples.** That was the style in the early 2010s. For the project use `<!DOCTYPE html>` and plain `<br>`. PHP doesn't care either way.

> [!NOTE]
> **`global` works but is a code smell.** Every function that reaches for a global is tied to that page's variable names and is harder to test and reuse. Pass values in as arguments and hand results back with `return`. The common exception is the database connection, which is often passed around as a global.

## Practice exercises

1. **Hello, server.** Get XAMPP or MAMP running, create `htdocs/practice/hello.php`, and print today's date with `date()` inside an HTML page. Then deliberately remove a semicolon and read the error message.
2. **Temperature converter.** A form with a number box and two radio buttons (to Fahrenheit, to Celsius). Handle it on the same page with `$_SERVER['REQUEST_METHOD']`, make the form sticky, and refuse non-numeric input.
3. **Roster from an array.** Put five students in a multidimensional array (name, major, year). Print them as an HTML table with a `foreach`, sorted by name, and add a row count in the footer.
4. **Sign-up with validation.** Take the sign-up form from the HTML notes and write the handler: `trim` every field, `filter_var` the email, check the year is 1 to 4, collect errors in an array, show them above a sticky form, and on success redirect to `thanks.php`.
5. **Header and footer includes.** Move the shared `<head>`, navigation, and footer of exercise 4 into `includes/header.php` and `includes/footer.php`. Add a second page that uses them. Change the nav in one place and confirm both pages update.
6. **Function library.** Write `e()` and `post()` from the section above in `includes/functions.php`, plus a `calculate_dues($year, $discount = 0)` function. Use all three in exercise 4.
7. **Login skeleton.** A `login.php` that checks a hard-coded username and `password_hash`'d password, starts a session on success, and a `members.php` that redirects to `login.php` unless `$_SESSION['user_id']` is set. Add `logout.php`. (Swap the hard-coded user for a database lookup once you reach MySQL.)

## Further Reading

| Topic | PHP Manual |
|---|---|
| PHP tags and basic syntax | [Basic syntax](https://www.php.net/manual/en/language.basic-syntax.php) |
| Strings and quoting | [Strings](https://www.php.net/manual/en/language.types.string.php) |
| Variables | [Variables](https://www.php.net/manual/en/language.variables.php) |
| Constants | [Constants](https://www.php.net/manual/en/language.constants.php) |
| Operators | [Operators](https://www.php.net/manual/en/language.operators.php) |
| Truthiness | [Booleans](https://www.php.net/manual/en/language.types.boolean.php), [Type comparison tables](https://www.php.net/manual/en/types.comparisons.php) |
| Control structures | [`if`](https://www.php.net/manual/en/control-structures.if.php), [`foreach`](https://www.php.net/manual/en/control-structures.foreach.php), [`for`](https://www.php.net/manual/en/control-structures.for.php), [`while`](https://www.php.net/manual/en/control-structures.while.php) |
| Arrays | [Arrays](https://www.php.net/manual/en/language.types.array.php), [Sorting arrays](https://www.php.net/manual/en/array.sorting.php) |
| Superglobals | [Superglobals](https://www.php.net/manual/en/language.variables.superglobals.php), [`$_POST`](https://www.php.net/manual/en/reserved.variables.post.php), [`$_SERVER`](https://www.php.net/manual/en/reserved.variables.server.php) |
| Form handling | [Dealing with forms](https://www.php.net/manual/en/tutorial.forms.php) |
| Including files | [`include`](https://www.php.net/manual/en/function.include.php), [`require`](https://www.php.net/manual/en/function.require.php) |
| Functions | [Functions](https://www.php.net/manual/en/language.functions.php), [Arguments](https://www.php.net/manual/en/functions.arguments.php), [Returning values](https://www.php.net/manual/en/functions.returning-values.php) |
| Scope | [Variable scope](https://www.php.net/manual/en/language.variables.scope.php) |
| Safe output | [`htmlspecialchars`](https://www.php.net/manual/en/function.htmlspecialchars.php) |
| Input filtering | [`filter_var`](https://www.php.net/manual/en/function.filter-var.php) |

| Topic | OWASP |
|---|---|
| Escaping output | [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html) |
| Validating input | [Input Validation Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html) |

