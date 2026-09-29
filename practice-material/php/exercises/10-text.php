<?php
// ============================================================
//  10 - Text and parsing
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 10)
//
//  Web applications live on text: URLs, form input, uploaded files,
//  templates, log lines. These functions turn messy text into data and
//  data into tidy text. Two of them need regular expressions; the rest
//  are careful loops and string functions.
//
//  Functions you will need (click for the manual):
//    preg_replace()        https://www.php.net/preg_replace
//    preg_match_all()      https://www.php.net/preg_match_all
//    preg_replace_callback() https://www.php.net/preg_replace_callback
//    preg_quote()          https://www.php.net/preg_quote
//    mb_strlen(), mb_substr() https://www.php.net/mb_strlen
//    str_word_count()      https://www.php.net/str_word_count
//    htmlspecialchars()    https://www.php.net/htmlspecialchars
//    trim()                https://www.php.net/trim
//    number_format()       https://www.php.net/number_format
//    Regex syntax          https://www.php.net/manual/en/reference.pcre.pattern.syntax.php
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * slugify($title)
 *
 * Turn a title into a URL-safe slug: lower case, letters and digits
 * only, runs of anything else become one dash, no dash at either end.
 *
 * Input:   a string
 * Output:  a string
 *
 * Examples:
 *   slugify("Hello, World!")          ->  "hello-world"
 *   slugify("  PHP 8.3 -- what's new ")  ->  "php-8-3-what-s-new"
 *   slugify("---")                    ->  ""
 */
function slugify($title)
{
    // your code here
}

/**
 * truncate($text, $max)
 *
 * Shorten text to at most $max characters. If it had to be cut, the
 * last character of the result is "…" (one character), so the whole
 * result is still $max characters long. Count characters, not bytes.
 *
 * Input:   $text - a string, $max - a positive integer
 * Output:  a string
 *
 * Examples:
 *   truncate("Hello world", 5)   ->  "Hell…"
 *   truncate("Hello", 5)         ->  "Hello"
 *   truncate("Ünïcödé text", 4)  ->  "Ünï…"
 */
function truncate($text, $max)
{
    // your code here
}

/**
 * format_bytes($bytes)
 *
 * A human size: B, KB, MB, GB, TB, using 1024 per step, one decimal
 * place except for plain bytes. Trailing ".0" is kept: "1.0 KB".
 *
 * Input:   a non-negative integer
 * Output:  a string
 *
 * Examples:
 *   format_bytes(512)         ->  "512 B"
 *   format_bytes(1024)        ->  "1.0 KB"
 *   format_bytes(1536)        ->  "1.5 KB"
 *   format_bytes(5242880)     ->  "5.0 MB"
 *   format_bytes(1099511627776) ->  "1.0 TB"
 */
function format_bytes($bytes)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * parse_csv_line($line)
 *
 * Split one line of CSV into fields. Fields are separated by commas.
 * A field may be wrapped in double quotes; inside quotes a comma is
 * part of the field and a doubled quote "" is one literal quote.
 * Do not use str_getcsv: write the loop yourself.
 *
 * Input:   a string
 * Output:  a list of strings
 *
 * Examples:
 *   parse_csv_line('a,b,c')                  ->  ['a', 'b', 'c']
 *   parse_csv_line('a,"b,c",d')              ->  ['a', 'b,c', 'd']
 *   parse_csv_line('"say ""hi""",x')         ->  ['say "hi"', 'x']
 *   parse_csv_line('a,,c')                   ->  ['a', '', 'c']
 *   parse_csv_line('')                       ->  ['']
 */
function parse_csv_line($line)
{
    // your code here
}

/**
 * render_template($template, $vars)
 *
 * Replace every {{name}} with the value of $vars['name'], escaped for
 * HTML. Spaces inside the braces are allowed: {{ name }}. A name that
 * is not in $vars becomes an empty string.
 *
 * Input:   $template - a string, $vars - an associative array
 * Output:  a string
 *
 * Examples:
 *   render_template("Hi {{name}}!", ['name' => 'Ada'])        ->  "Hi Ada!"
 *   render_template("{{ a }}-{{b}}", ['a' => '<b>', 'b' => 2])  ->  "&lt;b&gt;-2"
 *   render_template("{{missing}}", [])                        ->  ""
 *
 * Docs: https://www.php.net/preg_replace_callback
 */
function render_template($template, $vars)
{
    // your code here
}

/**
 * extract_emails($text)
 *
 * Every email address in the text, in order, without duplicates.
 * An address is: one or more of letters, digits, dot, underscore, plus
 * or dash; then @; then a domain of letters, digits, dots and dashes
 * that contains at least one dot.
 *
 * Input:   a string
 * Output:  a list of strings
 *
 * Examples:
 *   extract_emails("Write to ada@example.org or alan.t@cs.man.ac.uk today")
 *     ->  ['ada@example.org', 'alan.t@cs.man.ac.uk']
 *   extract_emails("ada@example.org, ada@example.org")  ->  ['ada@example.org']
 *   extract_emails("no addresses here")                 ->  []
 */
function extract_emails($text)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * wrap_text($text, $width)
 *
 * Re-flow text so no line is longer than $width characters, breaking
 * only at spaces. Existing line breaks and repeated spaces are treated
 * as single spaces. A single word longer than $width stays on its own
 * line, unbroken. Do not use wordwrap().
 *
 * Input:   $text - a string, $width - a positive integer
 * Output:  a string with "\n" between lines
 *
 * Examples:
 *   wrap_text("the quick brown fox jumps", 10)  ->  "the quick\nbrown fox\njumps"
 *   wrap_text("a  b\nc", 5)                     ->  "a b c"
 *   wrap_text("extraordinary cat", 5)           ->  "extraordinary\ncat"
 */
function wrap_text($text, $width)
{
    // your code here
}

/**
 * parse_duration($text)
 *
 * Turn "1h 30m 15s" style text into a number of seconds. Units are h,
 * m and s, each optional, in any order, with or without spaces. Return
 * null for anything that is not a duration.
 *
 * Input:   a string
 * Output:  an integer, or null
 *
 * Examples:
 *   parse_duration("1h 30m 15s")  ->  5415
 *   parse_duration("45m")         ->  2700
 *   parse_duration("2h5s")        ->  7205
 *   parse_duration("90s")         ->  90
 *   parse_duration("soon")        ->  null
 *   parse_duration("")            ->  null
 */
function parse_duration($text)
{
    // your code here
}

/**
 * parse_config($text)
 *
 * Read an INI-like config into a nested array. Lines are key = value.
 * A line [section] starts a section; keys after it go under that
 * section. Blank lines and lines starting with # or ; are ignored.
 * Keys and values are trimmed. Values "true" and "false" become
 * booleans, and values made only of digits become integers.
 * Do not use parse_ini_string.
 *
 * Input:   a string
 * Output:  an associative array
 *
 * Examples:
 *   parse_config("name = demo\ndebug = true\n\n[db]\nhost = localhost\nport = 3306\n; comment\n")
 *     ->  ['name' => 'demo', 'debug' => true, 'db' => ['host' => 'localhost', 'port' => 3306]]
 *   parse_config("")  ->  []
 */
function parse_config($text)
{
    // your code here
}

/**
 * highlight($text, $term)
 *
 * Wrap every case-insensitive match of $term in <mark>...</mark>,
 * keeping the original case of the match, and escape everything for
 * HTML. An empty $term just escapes the text.
 *
 * Input:   $text - a string, $term - a string
 * Output:  an HTML string
 *
 * Examples:
 *   highlight("PHP is fun, php!", "php")   ->  "<mark>PHP</mark> is fun, <mark>php</mark>!"
 *   highlight("a < b", "<")                ->  "a <mark>&lt;</mark> b"
 *   highlight("a < b", "")                 ->  "a &lt; b"
 *
 * Hint: preg_quote() makes the term safe inside a pattern; escape the
 * matched text with htmlspecialchars() before wrapping it.
 */
function highlight($text, $term)
{
    // your code here
}
