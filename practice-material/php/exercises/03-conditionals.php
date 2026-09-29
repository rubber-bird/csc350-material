<?php
// ============================================================
//  03 - Conditionals
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 03)
//
//  Making decisions:
//
//    if ($age >= 18) {            comparison:  ==  ===  !=  !==  <  >  <=  >=
//        return "adult";          combine:     &&  ||  !
//    } elseif ($age >= 13) {
//        return "teen";           ===  checks value AND type (use this one)
//    } else {                     ==   converts types first: "1" == 1 is true
//        return "child";
//    }
//
//    $label = $n > 0 ? "positive" : "not positive";    // ternary, a short if/else
//
//    return match (true) {                            // match, a cleaner if/elseif chain
//        $n > 0 => "positive",
//        $n < 0 => "negative",
//        default => "zero",
//    };
//
//  Docs:
//    if / elseif / else   https://www.php.net/manual/en/control-structures.elseif.php
//    comparison operators https://www.php.net/manual/en/language.operators.comparison.php
//    logical operators    https://www.php.net/manual/en/language.operators.logical.php
//    match                https://www.php.net/manual/en/control-structures.match.php
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * is_even($n)
 *
 * Return true if the number is even.
 *
 * Input:   $n - a whole number
 * Output:  true or false
 *
 * Examples:
 *   is_even(4)  ->  true
 *   is_even(7)  ->  false
 *   is_even(0)  ->  true
 */
function is_even($n)
{
    // your code here
}

/**
 * is_positive($n)
 *
 * Return true if the number is greater than zero.
 *
 * Input:   $n - a number
 * Output:  true or false
 *
 * Examples:
 *   is_positive(5)   ->  true
 *   is_positive(-2)  ->  false
 *   is_positive(0)   ->  false
 */
function is_positive($n)
{
    // your code here
}

/**
 * max_of_two($a, $b)
 *
 * Return the bigger of two numbers. Use an if, not max().
 *
 * Input:   $a, $b - numbers
 * Output:  a number
 *
 * Examples:
 *   max_of_two(3, 9)   ->  9
 *   max_of_two(10, 2)  ->  10
 *   max_of_two(4, 4)   ->  4
 */
function max_of_two($a, $b)
{
    // your code here
}

/**
 * is_adult($age)
 *
 * Return true if the age is 18 or more.
 *
 * Input:   $age - a whole number
 * Output:  true or false
 *
 * Examples:
 *   is_adult(18)  ->  true
 *   is_adult(30)  ->  true
 *   is_adult(17)  ->  false
 */
function is_adult($age)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * sign_of($n)
 *
 * Describe a number in one word.
 *
 * Input:   $n - a number
 * Output:  "positive", "negative" or "zero"
 *
 * Examples:
 *   sign_of(12)  ->  "positive"
 *   sign_of(-3)  ->  "negative"
 *   sign_of(0)   ->  "zero"
 */
function sign_of($n)
{
    // your code here
}

/**
 * grade_letter($score)
 *
 * Turn a score out of 100 into a letter.
 *   90 and up: "A"    80-89: "B"    70-79: "C"    60-69: "D"    below 60: "F"
 *
 * Input:   $score - a whole number from 0 to 100
 * Output:  a one-letter string
 *
 * Examples:
 *   grade_letter(95)  ->  "A"
 *   grade_letter(80)  ->  "B"
 *   grade_letter(59)  ->  "F"
 */
function grade_letter($score)
{
    // your code here
}

/**
 * fizz_buzz_one($n)
 *
 * The classic. If $n divides by 3 and 5, return "FizzBuzz". If only
 * by 3, "Fizz". If only by 5, "Buzz". Otherwise the number itself
 * as a string.
 *
 * Input:   $n - a whole number, 1 or more
 * Output:  a string
 *
 * Examples:
 *   fizz_buzz_one(3)   ->  "Fizz"
 *   fizz_buzz_one(10)  ->  "Buzz"
 *   fizz_buzz_one(15)  ->  "FizzBuzz"
 *   fizz_buzz_one(7)   ->  "7"
 *
 * Hint: check the 3-and-5 case first. Turn a number into a string
 * with (string) $n or strval($n).
 */
function fizz_buzz_one($n)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * is_leap_year($year)
 *
 * A year is a leap year if it divides by 4, except years that divide
 * by 100, unless they also divide by 400.
 *
 * Input:   $year - a whole number
 * Output:  true or false
 *
 * Examples:
 *   is_leap_year(2024)  ->  true    (divides by 4)
 *   is_leap_year(1900)  ->  false   (divides by 100)
 *   is_leap_year(2000)  ->  true    (divides by 400)
 *   is_leap_year(2023)  ->  false
 */
function is_leap_year($year)
{
    // your code here
}

/**
 * ticket_price($age, $is_student)
 *
 * Cinema prices: children under 12 pay 5, seniors 65 and older pay 7,
 * everyone else pays 12. Students get 2 off, but only if they are
 * paying the full price of 12.
 *
 * Input:   $age        - a whole number
 *          $is_student - true or false
 * Output:  a number
 *
 * Examples:
 *   ticket_price(8, false)   ->  5
 *   ticket_price(70, true)   ->  7
 *   ticket_price(30, false)  ->  12
 *   ticket_price(20, true)   ->  10
 */
function ticket_price($age, $is_student)
{
    // your code here
}

/**
 * triangle_type($a, $b, $c)
 *
 * Given three side lengths, name the triangle:
 *   all three equal      -> "equilateral"
 *   exactly two equal    -> "isosceles"
 *   all different        -> "scalene"
 * If the sides cannot make a triangle (the two shorter sides added
 * together are not longer than the longest side), return "invalid".
 *
 * Input:   $a, $b, $c - positive numbers
 * Output:  a string
 *
 * Examples:
 *   triangle_type(3, 3, 3)  ->  "equilateral"
 *   triangle_type(3, 4, 4)  ->  "isosceles"
 *   triangle_type(3, 4, 5)  ->  "scalene"
 *   triangle_type(1, 2, 3)  ->  "invalid"
 *
 * Docs: https://www.php.net/max
 */
function triangle_type($a, $b, $c)
{
    // your code here
}
