<?php
// ============================================================
//  04 - Loops
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 04)
//
//  Three ways to repeat:
//
//    for ($i = 1; $i <= 10; $i++) { ... }        count from 1 to 10
//    while ($n > 0) { ... }                      repeat while something is true
//    foreach ($items as $item) { ... }           visit every item in an array
//    foreach ($items as $key => $value) { ... }  visit keys too
//
//  Building an array in a loop:
//
//    $result = [];
//    foreach ($items as $item) {
//        $result[] = $item * 2;     // [] on the left adds to the end
//    }
//    return $result;
//
//  Docs:
//    for        https://www.php.net/manual/en/control-structures.for.php
//    while      https://www.php.net/manual/en/control-structures.while.php
//    foreach    https://www.php.net/manual/en/control-structures.foreach.php
//    count()    https://www.php.net/count
//    range()    https://www.php.net/range      range(1, 5) -> [1, 2, 3, 4, 5]
//
//  Rule for this file: use loops, not array_sum, array_map, max, etc.
//  Those come in module 05. The point here is to practice loops.
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * sum_to($n)
 *
 * Add up every whole number from 1 to $n.
 *
 * Input:   $n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   sum_to(4)  ->  10     (1 + 2 + 3 + 4)
 *   sum_to(1)  ->  1
 *   sum_to(0)  ->  0
 */
function sum_to($n)
{
    // your code here
}

/**
 * count_down($n)
 *
 * Return an array counting down from $n to 1.
 *
 * Input:   $n - a whole number, 1 or more
 * Output:  an array of numbers
 *
 * Examples:
 *   count_down(5)  ->  [5, 4, 3, 2, 1]
 *   count_down(1)  ->  [1]
 */
function count_down($n)
{
    // your code here
}

/**
 * factorial($n)
 *
 * Multiply every whole number from 1 to $n together.
 * The factorial of 0 is 1.
 *
 * Input:   $n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   factorial(5)  ->  120    (1 * 2 * 3 * 4 * 5)
 *   factorial(0)  ->  1
 */
function factorial($n)
{
    // your code here
}

/**
 * multiples_of($n, $count)
 *
 * Return the first $count multiples of $n.
 *
 * Input:   $n     - a number
 *          $count - how many multiples to return
 * Output:  an array of numbers
 *
 * Examples:
 *   multiples_of(3, 4)  ->  [3, 6, 9, 12]
 *   multiples_of(5, 0)  ->  []
 */
function multiples_of($n, $count)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * count_evens($numbers)
 *
 * How many even numbers are in the array?
 *
 * Input:   $numbers - an array of whole numbers
 * Output:  a whole number
 *
 * Examples:
 *   count_evens([1, 2, 3, 4, 6])  ->  3
 *   count_evens([1, 3, 5])        ->  0
 *   count_evens([])               ->  0
 */
function count_evens($numbers)
{
    // your code here
}

/**
 * largest_in($numbers)
 *
 * Find the largest number. Do not use max(); keep track of the
 * biggest one seen so far as you loop.
 *
 * Input:   $numbers - an array with at least one number
 * Output:  a number
 *
 * Examples:
 *   largest_in([3, 9, 2])     ->  9
 *   largest_in([-5, -1, -8])  ->  -1
 */
function largest_in($numbers)
{
    // your code here
}

/**
 * sum_of_squares($numbers)
 *
 * Square every number and add the squares up.
 *
 * Input:   $numbers - an array of numbers
 * Output:  a number
 *
 * Examples:
 *   sum_of_squares([1, 2, 3])  ->  14     (1 + 4 + 9)
 *   sum_of_squares([])         ->  0
 */
function sum_of_squares($numbers)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * fizz_buzz_list($n)
 *
 * Return an array with FizzBuzz for every number from 1 to $n.
 * Multiples of 3 become "Fizz", of 5 "Buzz", of both "FizzBuzz",
 * everything else the number itself as a string.
 *
 * Input:   $n - a whole number, 1 or more
 * Output:  an array of strings
 *
 * Examples:
 *   fizz_buzz_list(5)   ->  ["1", "2", "Fizz", "4", "Buzz"]
 *   fizz_buzz_list(15)  ->  [..., "13", "14", "FizzBuzz"]
 */
function fizz_buzz_list($n)
{
    // your code here
}

/**
 * multiplication_table($n)
 *
 * Return an $n by $n multiplication table as an array of rows.
 * Row $i (starting at 1) holds $i*1, $i*2, ... $i*$n.
 *
 * Input:   $n - a whole number, 1 or more
 * Output:  an array of arrays of numbers
 *
 * Examples:
 *   multiplication_table(3)  ->  [[1, 2, 3], [2, 4, 6], [3, 6, 9]]
 *   multiplication_table(1)  ->  [[1]]
 *
 * Hint: a loop inside a loop. The inner loop builds one row.
 */
function multiplication_table($n)
{
    // your code here
}

/**
 * collatz_steps($n)
 *
 * Start with $n. If it is even, halve it. If it is odd, triple it and
 * add 1. Repeat until you reach 1. How many steps did it take?
 *
 * Input:   $n - a whole number, 1 or more
 * Output:  a whole number, the count of steps
 *
 * Examples:
 *   collatz_steps(1)  ->  0
 *   collatz_steps(6)  ->  8     (6 3 10 5 16 8 4 2 1)
 *   collatz_steps(27) ->  111
 *
 * Hint: a while loop. You do not know in advance how many times it runs.
 */
function collatz_steps($n)
{
    // your code here
}
