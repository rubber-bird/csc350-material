<?php
// ============================================================
//  09 - Working with records
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 09)
//
//  Data from a database arrives as a list of records: an array of
//  associative arrays, one per row.
//
//    $users = [
//        ['id' => 1, 'name' => 'Ada',   'city' => 'London', 'age' => 36],
//        ['id' => 2, 'name' => 'Alan',  'city' => 'Leeds',  'age' => 41],
//    ];
//
//  Most of the work of a web application is reshaping such lists:
//  indexing, filtering, sorting, grouping, summing, paginating. These
//  functions are the toolbox. Never modify the input array; always
//  return a new one.
//
//  Functions you will need (click for the manual):
//    array_column()       https://www.php.net/array_column
//    array_filter()       https://www.php.net/array_filter
//    array_values()       https://www.php.net/array_values
//    usort()              https://www.php.net/usort
//    <=>  (spaceship)     https://www.php.net/manual/en/language.operators.comparison.php
//    array_slice()        https://www.php.net/array_slice
//    array_is_list()      https://www.php.net/array_is_list
//    is_array()           https://www.php.net/is_array
//    ceil()               https://www.php.net/ceil
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * index_by($records, $key)
 *
 * Turn a list of records into an associative array keyed by one field,
 * so a record can be looked up directly instead of by searching.
 *
 * Input:   $records - a list of associative arrays
 *          $key     - the field to use as the key
 * Output:  an associative array: value of $key => the record
 *
 * Examples:
 *   index_by([['id' => 7, 'n' => 'a'], ['id' => 9, 'n' => 'b']], 'id')
 *     ->  [7 => ['id' => 7, 'n' => 'a'], 9 => ['id' => 9, 'n' => 'b']]
 *   index_by([], 'id')  ->  []
 */
function index_by($records, $key)
{
    // your code here
}

/**
 * pluck($records, $key)
 *
 * The values of one field, in order, as a plain list.
 *
 * Input:   $records - a list of associative arrays
 *          $key     - a field name
 * Output:  a list
 *
 * Examples:
 *   pluck([['name' => 'Ada'], ['name' => 'Alan']], 'name')  ->  ['Ada', 'Alan']
 *   pluck([], 'name')                                       ->  []
 */
function pluck($records, $key)
{
    // your code here
}

/**
 * where($records, $key, $value)
 *
 * Only the records whose field equals the value (strict comparison).
 * The result is a list: keys start again at 0.
 *
 * Input:   $records, $key, $value
 * Output:  a list of records
 *
 * Examples:
 *   where([['c' => 'x', 'n' => 1], ['c' => 'y', 'n' => 2], ['c' => 'x', 'n' => 3]], 'c', 'x')
 *     ->  [['c' => 'x', 'n' => 1], ['c' => 'x', 'n' => 3]]
 *   where([['n' => 1]], 'n', '1')  ->  []       (1 is not '1')
 */
function where($records, $key, $value)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * sort_by($records, ...$keys)
 *
 * Sort by one or more fields. A key that starts with '-' sorts that
 * field descending. Later keys break ties in earlier ones. Comparison
 * uses <=>. The result is a list.
 *
 * Input:   $records - a list of records
 *          $keys    - one or more field names, e.g. 'city', '-age'
 * Output:  a new sorted list
 *
 * Examples:
 *   $p = [['n' => 'Ada', 'age' => 36], ['n' => 'Bob', 'age' => 41], ['n' => 'Cy', 'age' => 36]];
 *   pluck(sort_by($p, 'age', 'n'), 'n')   ->  ['Ada', 'Cy', 'Bob']
 *   pluck(sort_by($p, '-age', 'n'), 'n')  ->  ['Bob', 'Ada', 'Cy']
 *   pluck(sort_by($p, '-age', '-n'), 'n') ->  ['Bob', 'Cy', 'Ada']
 *
 * Hint: in the usort callback, loop over the keys and return the first
 * non-zero comparison.
 */
function sort_by($records, ...$keys)
{
    // your code here
}

/**
 * sum_by($records, $group_key, $value_key)
 *
 * Total one field for each distinct value of another field.
 * Groups appear in the order they are first seen.
 *
 * Input:   $records, $group_key, $value_key
 * Output:  an associative array: group => total
 *
 * Examples:
 *   $orders = [['city' => 'Leeds', 'total' => 10], ['city' => 'York', 'total' => 5], ['city' => 'Leeds', 'total' => 2.5]];
 *   sum_by($orders, 'city', 'total')  ->  ['Leeds' => 12.5, 'York' => 5]
 *   sum_by([], 'city', 'total')       ->  []
 */
function sum_by($records, $group_key, $value_key)
{
    // your code here
}

/**
 * top_n($records, $key, $n)
 *
 * The $n records with the highest value of $key, highest first.
 * Fewer than $n records: return them all. Do not change the input.
 *
 * Input:   $records, $key, $n
 * Output:  a list of at most $n records
 *
 * Examples:
 *   $s = [['n' => 'a', 'score' => 5], ['n' => 'b', 'score' => 9], ['n' => 'c', 'score' => 7]];
 *   pluck(top_n($s, 'score', 2), 'n')   ->  ['b', 'c']
 *   pluck(top_n($s, 'score', 10), 'n')  ->  ['b', 'c', 'a']
 */
function top_n($records, $key, $n)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * pivot($records, $row_key, $col_key, $value_key)
 *
 * A two-way table: one row per distinct $row_key, one column per
 * distinct $col_key, cells hold the SUM of $value_key. Every row has
 * every column; missing combinations are 0. Rows and columns are in
 * the order first seen.
 *
 * Input:   $records, $row_key, $col_key, $value_key
 * Output:  an associative array of associative arrays
 *
 * Examples:
 *   $sales = [
 *     ['city' => 'Leeds', 'q' => 'Q1', 'amt' => 10],
 *     ['city' => 'York',  'q' => 'Q2', 'amt' => 5],
 *     ['city' => 'Leeds', 'q' => 'Q1', 'amt' => 3],
 *     ['city' => 'Leeds', 'q' => 'Q2', 'amt' => 1],
 *   ];
 *   pivot($sales, 'city', 'q', 'amt')
 *     ->  ['Leeds' => ['Q1' => 13, 'Q2' => 1], 'York' => ['Q1' => 0, 'Q2' => 5]]
 */
function pivot($records, $row_key, $col_key, $value_key)
{
    // your code here
}

/**
 * paginate($records, $page, $per_page)
 *
 * One page of a list, plus the numbers a page footer needs.
 * Pages start at 1. A page past the end has an empty items list.
 * pages is 0 for an empty list.
 *
 * Input:   $records, $page, $per_page
 * Output:  ['items' => [...], 'page' => 2, 'pages' => 3, 'total' => 7]
 *
 * Examples:
 *   paginate(['a', 'b', 'c', 'd', 'e'], 2, 2)
 *     ->  ['items' => ['c', 'd'], 'page' => 2, 'pages' => 3, 'total' => 5]
 *   paginate(['a', 'b', 'c', 'd', 'e'], 3, 2)['items']  ->  ['e']
 *   paginate(['a', 'b', 'c', 'd', 'e'], 9, 2)['items']  ->  []
 *   paginate([], 1, 10)  ->  ['items' => [], 'page' => 1, 'pages' => 0, 'total' => 0]
 */
function paginate($records, $page, $per_page)
{
    // your code here
}

/**
 * deep_merge($base, $override)
 *
 * Merge two nested associative arrays, like default settings and user
 * settings. Where both sides have an associative array under the same
 * key, merge those recursively. Anything else in $override replaces
 * what is in $base, including plain lists (they are not appended).
 *
 * Input:   two arrays
 * Output:  a new array
 *
 * Examples:
 *   deep_merge(['a' => 1, 'b' => ['x' => 1, 'y' => 2]], ['b' => ['y' => 20, 'z' => 30], 'c' => 3])
 *     ->  ['a' => 1, 'b' => ['x' => 1, 'y' => 20, 'z' => 30], 'c' => 3]
 *   deep_merge(['tags' => ['a', 'b']], ['tags' => ['c']])  ->  ['tags' => ['c']]
 *
 * Hint: array_is_list() tells a plain list from an associative array.
 */
function deep_merge($base, $override)
{
    // your code here
}

/**
 * flatten_keys($array, $prefix = '')
 *
 * Turn a nested associative array into a flat one whose keys are the
 * paths joined with dots. Values that are not arrays are copied as
 * they are. An empty array becomes an empty array.
 *
 * Input:   a nested array
 * Output:  a flat associative array
 *
 * Examples:
 *   flatten_keys(['db' => ['host' => 'x', 'port' => 5], 'debug' => true])
 *     ->  ['db.host' => 'x', 'db.port' => 5, 'debug' => true]
 *   flatten_keys(['a' => ['b' => ['c' => 1]]])  ->  ['a.b.c' => 1]
 *
 * Hint: recursion. The $prefix parameter carries the path so far.
 */
function flatten_keys($array, $prefix = '')
{
    // your code here
}
