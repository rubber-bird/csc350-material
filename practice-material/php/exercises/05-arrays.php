<?php
// ============================================================
//  05 - Arrays
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 05)
//
//  PHP has one array type that does two jobs:
//
//    $list = ["apple", "pear"];              numbered from 0, like a JS array
//    $list[0]                                 "apple"
//    $list[] = "plum";                        add to the end
//
//    $person = ["name" => "Ada", "age" => 36];   named keys, like a JS object
//    $person["name"]                             "Ada"
//    $person["city"] = "London";                 add a key
//
//  Functions you will need (click for the manual):
//    count($arr)                  how many items                     https://www.php.net/count
//    array_map($fn, $arr)         new array, $fn applied to each     https://www.php.net/array_map
//    array_filter($arr, $fn)      keep items where $fn is true       https://www.php.net/array_filter
//    array_values($arr)           renumber keys 0, 1, 2 ...          https://www.php.net/array_values
//    array_keys($arr)             the keys as a list                 https://www.php.net/array_keys
//    array_key_exists($k, $arr)   does the key exist?                https://www.php.net/array_key_exists
//    array_column($rows, $key)    one field from every row           https://www.php.net/array_column
//    array_sum($arr)              add everything up                  https://www.php.net/array_sum
//    usort($arr, $fn)             sort in place with a compare fn    https://www.php.net/usort
//    explode(" ", $s)             string to array                    https://www.php.net/explode
//    strtolower($s)               lowercase                          https://www.php.net/strtolower
//
//  Note: array_filter keeps the original keys. Wrap it in
//  array_values() if the tests expect [0, 1, 2 ...] numbering.
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * first_item($items)
 *
 * Return the first item of a list.
 *
 * Input:   $items - an array with at least one item
 * Output:  the first item
 *
 * Examples:
 *   first_item([7, 8, 9])         ->  7
 *   first_item(["pear", "fig"])   ->  "pear"
 */
function first_item($items)
{
    // your code here
}

/**
 * last_item($items)
 *
 * Return the last item of a list.
 *
 * Input:   $items - an array with at least one item
 * Output:  the last item
 *
 * Examples:
 *   last_item([7, 8, 9])         ->  9
 *   last_item(["pear", "fig"])   ->  "fig"
 *
 * Docs: https://www.php.net/count
 */
function last_item($items)
{
    // your code here
}

/**
 * double_all($numbers)
 *
 * Return a new array with every number doubled.
 *
 * Input:   $numbers - an array of numbers
 * Output:  a new array of numbers
 *
 * Examples:
 *   double_all([1, 2, 3])  ->  [2, 4, 6]
 *   double_all([])         ->  []
 *
 * Docs: https://www.php.net/array_map
 */
function double_all($numbers)
{
    // your code here
}

/**
 * has_key($assoc, $key)
 *
 * Does the array have this key?
 *
 * Input:   $assoc - an array with named keys
 *          $key   - a string
 * Output:  true or false
 *
 * Examples:
 *   has_key(["name" => "Ada"], "name")  ->  true
 *   has_key(["name" => "Ada"], "age")   ->  false
 *
 * Docs: https://www.php.net/array_key_exists
 */
function has_key($assoc, $key)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * only_evens($numbers)
 *
 * Return a new array with only the even numbers, numbered from 0.
 *
 * Input:   $numbers - an array of whole numbers
 * Output:  a new array
 *
 * Examples:
 *   only_evens([1, 2, 3, 4])  ->  [2, 4]
 *   only_evens([1, 3])        ->  []
 *
 * Docs: https://www.php.net/array_filter   https://www.php.net/array_values
 */
function only_evens($numbers)
{
    // your code here
}

/**
 * names_of($people)
 *
 * Pull out the "name" of every person.
 *
 * Input:   $people - an array of arrays, each with a "name" key
 * Output:  an array of strings
 *
 * Examples:
 *   names_of([["name" => "Ada", "age" => 36], ["name" => "Alan", "age" => 41]])
 *     ->  ["Ada", "Alan"]
 *   names_of([])  ->  []
 *
 * Docs: https://www.php.net/array_column
 */
function names_of($people)
{
    // your code here
}

/**
 * total_price($cart)
 *
 * Add up price times quantity for every line in a shopping cart.
 *
 * Input:   $cart - an array of arrays with "price" and "qty" keys
 * Output:  a number
 *
 * Examples:
 *   total_price([["price" => 2.5, "qty" => 2], ["price" => 10, "qty" => 1]])  ->  15
 *   total_price([])  ->  0
 */
function total_price($cart)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * word_frequency($sentence)
 *
 * Count how often each word appears. Ignore capital letters.
 * The keys must appear in the order the words were first seen.
 *
 * Input:   $sentence - lowercase or mixed-case words separated by single spaces
 * Output:  an array of word => count
 *
 * Examples:
 *   word_frequency("the cat and the hat")  ->  ["the" => 2, "cat" => 1, "and" => 1, "hat" => 1]
 *   word_frequency("Go go GO")             ->  ["go" => 3]
 *
 * Docs: https://www.php.net/explode   https://www.php.net/array_key_exists
 */
function word_frequency($sentence)
{
    // your code here
}

/**
 * oldest_person($people)
 *
 * Return the name of the oldest person.
 *
 * Input:   $people - an array of ["name" => ..., "age" => ...], at least one
 * Output:  a string
 *
 * Examples:
 *   oldest_person([["name" => "Ada", "age" => 36], ["name" => "Alan", "age" => 41]])  ->  "Alan"
 *   oldest_person([["name" => "Solo", "age" => 5]])  ->  "Solo"
 */
function oldest_person($people)
{
    // your code here
}

/**
 * sort_by_key($rows, $key)
 *
 * Return a copy of the rows sorted from smallest to largest by one key.
 * The original array must not change.
 *
 * Input:   $rows - an array of arrays that all have $key
 *          $key  - the key to sort by
 * Output:  a new sorted array, numbered from 0
 *
 * Examples:
 *   sort_by_key([["n" => "b", "age" => 30], ["n" => "a", "age" => 20]], "age")
 *     ->  [["n" => "a", "age" => 20], ["n" => "b", "age" => 30]]
 *   sort_by_key([["n" => "b"], ["n" => "a"]], "n")
 *     ->  [["n" => "a"], ["n" => "b"]]
 *
 * Docs: https://www.php.net/usort  (the spaceship operator <=> is handy)
 *       https://www.php.net/manual/en/language.operators.comparison.php
 */
function sort_by_key($rows, $key)
{
    // your code here
}
