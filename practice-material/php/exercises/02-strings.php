<?php
// ============================================================
//  02 - Strings
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 02)
//
//  In PHP, strings are joined with a dot:  "Hello, " . $name
//  Double quotes fill in variables:  "Hello, $name"
//
//  Functions you will need (click for the manual):
//    strtoupper($s)         "abc" -> "ABC"                 https://www.php.net/strtoupper
//    strtolower($s)         "ABC" -> "abc"                 https://www.php.net/strtolower
//    strlen($s)             number of characters           https://www.php.net/strlen
//    str_repeat($s, $n)     "ab" 3 times -> "ababab"       https://www.php.net/str_repeat
//    substr($s, $start, $length)  part of a string         https://www.php.net/substr
//    $s[0]  and  $s[-1]     first / last character         https://www.php.net/manual/en/language.types.string.php#language.types.string.substr
//    str_contains($s, $x)   is $x inside $s?               https://www.php.net/str_contains
//    explode(" ", $s)       split into an array of words   https://www.php.net/explode
//    implode(" ", $arr)     join an array into a string    https://www.php.net/implode
//    strrev($s)             reverse                        https://www.php.net/strrev
//    ucfirst($s)            capitalize first letter        https://www.php.net/ucfirst
//    str_ireplace()         replace, ignoring case         https://www.php.net/str_ireplace
//    preg_replace()         replace using a pattern        https://www.php.net/preg_replace
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * shout($text)
 *
 * Return the text in capital letters with an exclamation mark added.
 *
 * Input:   $text - a string
 * Output:  a string
 *
 * Examples:
 *   shout("hello")  ->  "HELLO!"
 *   shout("Wow")    ->  "WOW!"
 *
 * Docs: https://www.php.net/strtoupper
 */
function shout($text)
{
    // your code here
}

/**
 * whisper($text)
 *
 * Return the text in lowercase with "..." added.
 *
 * Input:   $text - a string
 * Output:  a string
 *
 * Examples:
 *   whisper("HELLO")  ->  "hello..."
 *   whisper("Psst")   ->  "psst..."
 *
 * Docs: https://www.php.net/strtolower
 */
function whisper($text)
{
    // your code here
}

/**
 * string_length($text)
 *
 * How many characters are in the string?
 *
 * Input:   $text - a string
 * Output:  a whole number
 *
 * Examples:
 *   string_length("hello")  ->  5
 *   string_length("")       ->  0
 *
 * Docs: https://www.php.net/strlen
 */
function string_length($text)
{
    // your code here
}

/**
 * repeat_string($text, $times)
 *
 * Repeat a string a number of times.
 *
 * Input:   $text  - a string
 *          $times - a whole number, 0 or more
 * Output:  a string
 *
 * Examples:
 *   repeat_string("ab", 3)  ->  "ababab"
 *   repeat_string("x", 0)   ->  ""
 *
 * Docs: https://www.php.net/str_repeat
 */
function repeat_string($text, $times)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * first_and_last($text)
 *
 * Return the first and last character joined together.
 *
 * Input:   $text - a string with at least 2 characters
 * Output:  a string of 2 characters
 *
 * Examples:
 *   first_and_last("hello")  ->  "ho"
 *   first_and_last("PHP")    ->  "PP"
 *
 * Docs: https://www.php.net/manual/en/language.types.string.php#language.types.string.substr
 */
function first_and_last($text)
{
    // your code here
}

/**
 * count_vowels($text)
 *
 * Count how many vowels (a, e, i, o, u) the string has.
 * Capital vowels count too.
 *
 * Input:   $text - a string
 * Output:  a whole number
 *
 * Examples:
 *   count_vowels("hello")  ->  2
 *   count_vowels("AEIOU")  ->  5
 *   count_vowels("xyz")    ->  0
 *
 * Docs: https://www.php.net/str_contains   https://www.php.net/strlen
 */
function count_vowels($text)
{
    // your code here
}

/**
 * initials($full_name)
 *
 * Return the first letter of each word, in capitals.
 *
 * Input:   $full_name - words separated by single spaces
 * Output:  a string
 *
 * Examples:
 *   initials("Ada Lovelace")        ->  "AL"
 *   initials("grace brewster hopper")  ->  "GBH"
 *
 * Docs: https://www.php.net/explode
 */
function initials($full_name)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * is_palindrome($text)
 *
 * A palindrome reads the same forwards and backwards. Ignore capital
 * letters and spaces.
 *
 * Input:   $text - a string
 * Output:  true or false
 *
 * Examples:
 *   is_palindrome("racecar")          ->  true
 *   is_palindrome("Never odd or even") ->  true
 *   is_palindrome("hello")            ->  false
 *
 * Docs: https://www.php.net/strrev   https://www.php.net/str_replace
 */
function is_palindrome($text)
{
    // your code here
}

/**
 * title_case($sentence)
 *
 * Capitalize the first letter of every word and lowercase the rest.
 *
 * Input:   $sentence - words separated by single spaces
 * Output:  a string
 *
 * Examples:
 *   title_case("hello world")     ->  "Hello World"
 *   title_case("tHE gREAT gATSBY") ->  "The Great Gatsby"
 *
 * Docs: https://www.php.net/ucfirst   https://www.php.net/implode
 */
function title_case($sentence)
{
    // your code here
}

/**
 * censor($sentence, $word)
 *
 * Replace every appearance of $word with the same number of asterisks.
 * Ignore capital letters when matching.
 *
 * Input:   $sentence - a string
 *          $word     - the word to hide
 * Output:  a string
 *
 * Examples:
 *   censor("I hate homework", "hate")        ->  "I **** homework"
 *   censor("Darn it, DARN it", "darn")       ->  "**** it, **** it"
 *   censor("all good here", "bad")           ->  "all good here"
 *
 * Docs: https://www.php.net/str_ireplace   https://www.php.net/str_repeat
 */
function censor($sentence, $word)
{
    // your code here
}
