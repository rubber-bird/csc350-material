<?php
// ============================================================
//  01 - Numbers and Arithmetic
// ============================================================
//
//  Fill in each function below. The comment above each function
//  tells you what it must do, what it receives (Input), what it must
//  give back (Output), and a few examples.
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 01)
//
//  Tip: a function gives back a value with the `return` keyword.
//  If a test says "Received: null", you forgot to return.
//
//  Functions you will need (click for the manual):
//    %              remainder of a division         https://www.php.net/manual/en/language.operators.arithmetic.php
//    abs($n)        distance from zero              https://www.php.net/abs
//    round($n, 1)   round to 1 decimal place        https://www.php.net/round
//    sqrt($n)       square root                     https://www.php.net/sqrt
//    intdiv($a, $b) whole-number division           https://www.php.net/intdiv
//    str_pad()      add characters to a string      https://www.php.net/str_pad
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * add($a, $b)
 *
 * Add two numbers together.
 *
 * Input:   $a - a number
 *          $b - a number
 * Output:  a number, the sum of $a and $b
 *
 * Examples:
 *   add(2, 3)    ->  5
 *   add(-1, 1)   ->  0
 */
function add($a, $b)
{
    // your code here
}

/**
 * subtract($a, $b)
 *
 * Subtract $b from $a.
 *
 * Input:   $a - a number
 *          $b - a number
 * Output:  a number, $a minus $b
 *
 * Examples:
 *   subtract(10, 4)  ->  6
 *   subtract(0, 5)   ->  -5
 */
function subtract($a, $b)
{
    // your code here
}

/**
 * multiply($a, $b)
 *
 * Multiply two numbers.
 *
 * Input:   $a - a number
 *          $b - a number
 * Output:  a number, $a times $b
 *
 * Examples:
 *   multiply(3, 4)  ->  12
 *   multiply(7, 0)  ->  0
 */
function multiply($a, $b)
{
    // your code here
}

/**
 * remainder($a, $b)
 *
 * Return what is left over after dividing $a by $b.
 * PHP has an operator for this: %
 *
 * Input:   $a - a whole number
 *          $b - a whole number
 * Output:  a number, the remainder
 *
 * Examples:
 *   remainder(10, 3)  ->  1     (10 = 3 * 3 + 1)
 *   remainder(8, 4)   ->  0
 *
 * Docs: https://www.php.net/manual/en/language.operators.arithmetic.php
 */
function remainder($a, $b)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * square($n)
 *
 * Multiply a number by itself.
 *
 * Input:   $n - a number
 * Output:  a number, $n times $n
 *
 * Examples:
 *   square(5)   ->  25
 *   square(-3)  ->  9
 */
function square($n)
{
    // your code here
}

/**
 * average_of_three($a, $b, $c)
 *
 * Return the average (mean) of three numbers: add them up and
 * divide by 3.
 *
 * Input:   $a, $b, $c - numbers
 * Output:  a number, the average
 *
 * Examples:
 *   average_of_three(1, 2, 3)     ->  2
 *   average_of_three(10, 20, 60)  ->  30
 */
function average_of_three($a, $b, $c)
{
    // your code here
}

/**
 * is_divisible($a, $b)
 *
 * Return true if $a can be divided by $b with nothing left over.
 *
 * Input:   $a - a whole number
 *          $b - a whole number
 * Output:  true or false
 *
 * Examples:
 *   is_divisible(10, 5)  ->  true
 *   is_divisible(10, 3)  ->  false
 */
function is_divisible($a, $b)
{
    // your code here
}

/**
 * celsius_to_fahrenheit($c)
 *
 * Convert a temperature from Celsius to Fahrenheit.
 * Formula:  F = C * 9 / 5 + 32
 *
 * Input:   $c - temperature in Celsius (number)
 * Output:  temperature in Fahrenheit (number)
 *
 * Examples:
 *   celsius_to_fahrenheit(0)    ->  32
 *   celsius_to_fahrenheit(100)  ->  212
 *   celsius_to_fahrenheit(-40)  ->  -40
 */
function celsius_to_fahrenheit($c)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * clamp($n, $min, $max)
 *
 * Keep a number inside a range. If $n is smaller than $min, return
 * $min. If $n is bigger than $max, return $max. Otherwise return $n.
 *
 * Input:   $n   - a number
 *          $min - the lowest allowed value
 *          $max - the highest allowed value
 * Output:  a number between $min and $max (inclusive)
 *
 * Examples:
 *   clamp(5, 1, 10)   ->  5     (already inside the range)
 *   clamp(-3, 1, 10)  ->  1     (too small, so we get min)
 *   clamp(50, 1, 10)  ->  10    (too big, so we get max)
 */
function clamp($n, $min, $max)
{
    // your code here
}

/**
 * percent_of($part, $whole)
 *
 * What percentage of $whole is $part? Round to one decimal place.
 *
 * Input:   $part  - a number
 *          $whole - a number (never 0 in the tests)
 * Output:  a number, the percentage rounded to 1 decimal
 *
 * Examples:
 *   percent_of(50, 200)  ->  25
 *   percent_of(1, 3)     ->  33.3
 *   percent_of(2, 3)     ->  66.7
 *
 * Docs: https://www.php.net/round
 */
function percent_of($part, $whole)
{
    // your code here
}

/**
 * seconds_to_clock($total_seconds)
 *
 * Turn a number of seconds into a "m:ss" clock string.
 * Seconds must always have two digits.
 *
 * Input:   $total_seconds - a whole number, 0 or more
 * Output:  a string in the form "minutes:seconds"
 *
 * Examples:
 *   seconds_to_clock(90)   ->  "1:30"
 *   seconds_to_clock(5)    ->  "0:05"
 *   seconds_to_clock(600)  ->  "10:00"
 *
 * Docs: https://www.php.net/intdiv   https://www.php.net/str_pad
 */
function seconds_to_clock($total_seconds)
{
    // your code here
}
