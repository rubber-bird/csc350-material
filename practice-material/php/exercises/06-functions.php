<?php
// ============================================================
//  06 - Functions as Values
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 06)
//
//  In PHP a function can be stored in a variable, passed to another
//  function, and returned from one.
//
//    $double = function ($n) { return $n * 2; };     anonymous function
//    $double = fn($n) => $n * 2;                     arrow function, same thing
//    $double(5)                                      10
//
//    $factor = 3;
//    $triple = function ($n) use ($factor) { ... }   `use` brings in an outside variable
//    $triple = fn($n) => $n * $factor;               arrow functions grab them automatically
//
//    function greet($name, $greeting = "Hello")      default value for a parameter
//    function sum(...$numbers)                       any number of arguments, as an array
//
//  Docs:
//    anonymous functions    https://www.php.net/manual/en/functions.anonymous.php
//    arrow functions        https://www.php.net/manual/en/functions.arrow.php
//    default parameters     https://www.php.net/manual/en/functions.arguments.php#functions.arguments.default
//    variable-length args   https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
//    array_reduce           https://www.php.net/array_reduce
//    array_filter           https://www.php.net/array_filter
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * greet($name, $greeting = "Hello")
 *
 * Build a greeting. The greeting word is optional and defaults to
 * "Hello".
 *
 * Input:   $name     - a string
 *          $greeting - a string (optional)
 * Output:  a string
 *
 * Examples:
 *   greet("Ada")          ->  "Hello, Ada!"
 *   greet("Ada", "Hi")    ->  "Hi, Ada!"
 */
function greet($name, $greeting = 'Hello')
{
    // your code here
}

/**
 * apply_twice($fn, $value)
 *
 * Call the function on the value, then call it again on the result.
 *
 * Input:   $fn    - a function that takes one argument
 *          $value - anything
 * Output:  whatever the second call returns
 *
 * Examples:
 *   apply_twice(fn($n) => $n * 2, 5)         ->  20
 *   apply_twice(fn($s) => $s . "!", "hey")   ->  "hey!!"
 */
function apply_twice($fn, $value)
{
    // your code here
}

/**
 * sum_all(...$numbers)
 *
 * Add up any number of arguments.
 *
 * Input:   any number of numbers
 * Output:  a number
 *
 * Examples:
 *   sum_all(1, 2, 3)  ->  6
 *   sum_all(10)       ->  10
 *   sum_all()         ->  0
 *
 * Docs: https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
 */
function sum_all(...$numbers)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * make_multiplier($factor)
 *
 * Return a new function that multiplies its argument by $factor.
 *
 * Input:   $factor - a number
 * Output:  a function
 *
 * Examples:
 *   $triple = make_multiplier(3);
 *   $triple(5)   ->  15
 *   $triple(0)   ->  0
 *
 * Docs: https://www.php.net/manual/en/functions.arrow.php
 */
function make_multiplier($factor)
{
    // your code here
}

/**
 * count_where($items, $test)
 *
 * How many items make the test function return true?
 *
 * Input:   $items - an array
 *          $test  - a function that returns true or false
 * Output:  a whole number
 *
 * Examples:
 *   count_where([1, 2, 3, 4], fn($n) => $n > 2)         ->  2
 *   count_where(["a", "bb", "cc"], fn($s) => strlen($s) === 2)  ->  2
 *   count_where([], fn($n) => true)                     ->  0
 */
function count_where($items, $test)
{
    // your code here
}

/**
 * compose($f, $g)
 *
 * Return a new function that applies $g first, then $f to the result.
 *
 * Input:   $f, $g - functions of one argument
 * Output:  a function
 *
 * Examples:
 *   $add_one = fn($n) => $n + 1;
 *   $double  = fn($n) => $n * 2;
 *   $h = compose($add_one, $double);
 *   $h(5)   ->  11      (double first: 10, then add one)
 */
function compose($f, $g)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * make_counter()
 *
 * Return a function that returns 1 the first time it is called, 2 the
 * second time, and so on. Each counter keeps its own count.
 *
 * Input:   nothing
 * Output:  a function
 *
 * Examples:
 *   $next = make_counter();
 *   $next()   ->  1
 *   $next()   ->  2
 *   $other = make_counter();
 *   $other()  ->  1
 *
 * Hint: `use (&$count)` shares the variable by reference so the
 * function can change it.
 * Docs: https://www.php.net/manual/en/functions.anonymous.php
 */
function make_counter()
{
    // your code here
}

/**
 * pipeline(...$fns)
 *
 * Return a function that runs a value through every function in
 * order, left to right.
 *
 * Input:   any number of functions of one argument
 * Output:  a function
 *
 * Examples:
 *   $run = pipeline(fn($n) => $n + 1, fn($n) => $n * 10, 'strval');
 *   $run(4)   ->  "50"
 *   $none = pipeline();
 *   $none(7)  ->  7
 *
 * Docs: https://www.php.net/array_reduce
 */
function pipeline(...$fns)
{
    // your code here
}

/**
 * group_by($items, $key_fn)
 *
 * Put items into groups. The function decides which group each item
 * belongs to by returning a key.
 *
 * Input:   $items  - an array
 *          $key_fn - a function that returns a string or number
 * Output:  an array of key => array of items
 *
 * Examples:
 *   group_by(["ant", "bee", "cow", "deer"], fn($w) => strlen($w))
 *     ->  [3 => ["ant", "bee", "cow"], 4 => ["deer"]]
 *   group_by([1, 2, 3, 4], fn($n) => $n % 2 === 0 ? "even" : "odd")
 *     ->  ["odd" => [1, 3], "even" => [2, 4]]
 */
function group_by($items, $key_fn)
{
    // your code here
}
