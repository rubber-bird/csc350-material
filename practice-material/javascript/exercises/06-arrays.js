// ============================================================
//  06 - Arrays
// ============================================================
//
//  An array is an ordered list:  [10, 20, 30]
//
//    arr.length          how many items
//    arr[0]              the first item, arr[arr.length - 1] the last
//    arr.push(x)         add x to the end
//    arr.includes(x)     true if x is in the array
//    arr.indexOf(x)      position of x, or -1 if not found
//    for (const item of arr) { ... }   visit every item
//
//  "Return a NEW array" means: do not change the array you were
//  given. Build a fresh one and return that.
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * first(arr)
 *
 * Return the first item.
 *
 * Input:   arr - an array with at least one item
 * Output:  the first item
 *
 * Examples:
 *   first([5, 6, 7])  ->  5
 *   first(["a"])      ->  "a"
 */
function first(arr) {
  // your code here
}

/**
 * last(arr)
 *
 * Return the last item.
 *
 * Input:   arr - an array with at least one item
 * Output:  the last item
 *
 * Examples:
 *   last([5, 6, 7])  ->  7
 *   last(["a"])      ->  "a"
 */
function last(arr) {
  // your code here
}

/**
 * sum(arr)
 *
 * Add up all the numbers.
 *
 * Input:   arr - an array of numbers (may be empty)
 * Output:  a number; an empty array sums to 0
 *
 * Examples:
 *   sum([1, 2, 3, 4])  ->  10
 *   sum([])            ->  0
 */
function sum(arr) {
  // your code here
}

/**
 * contains(arr, value)
 *
 * Return true if the value is somewhere in the array.
 *
 * Input:   arr   - an array
 *          value - anything
 * Output:  true or false
 *
 * Examples:
 *   contains([1, 2, 3], 2)     ->  true
 *   contains(["a", "b"], "z")  ->  false
 */
function contains(arr, value) {
  // your code here
}

/**
 * doubleAll(arr)
 *
 * Return a NEW array where every number is doubled.
 *
 * Input:   arr - an array of numbers
 * Output:  a new array of numbers, same length
 *
 * Examples:
 *   doubleAll([1, 2, 3])  ->  [2, 4, 6]
 *   doubleAll([])         ->  []
 */
function doubleAll(arr) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * largest(arr)
 *
 * Return the biggest number in the array.
 *
 * Input:   arr - an array of numbers with at least one item
 * Output:  a number
 *
 * Examples:
 *   largest([3, 9, 2])      ->  9
 *   largest([-5, -1, -10])  ->  -1
 */
function largest(arr) {
  // your code here
}

/**
 * smallest(arr)
 *
 * Return the smallest number in the array.
 *
 * Input:   arr - an array of numbers with at least one item
 * Output:  a number
 *
 * Examples:
 *   smallest([3, 9, 2])    ->  2
 *   smallest([-5, -1, -10]) ->  -10
 */
function smallest(arr) {
  // your code here
}

/**
 * onlyEvens(arr)
 *
 * Return a NEW array containing only the even numbers, in the same order.
 *
 * Input:   arr - an array of whole numbers
 * Output:  a new array
 *
 * Examples:
 *   onlyEvens([1, 2, 3, 4, 5, 6])  ->  [2, 4, 6]
 *   onlyEvens([1, 3])              ->  []
 */
function onlyEvens(arr) {
  // your code here
}

/**
 * average(arr)
 *
 * Return the average (mean) of the numbers.
 *
 * Input:   arr - an array of numbers with at least one item
 * Output:  a number
 *
 * Examples:
 *   average([2, 4, 6])  ->  4
 *   average([1, 2])     ->  1.5
 */
function average(arr) {
  // your code here
}

/**
 * countValue(arr, value)
 *
 * Count how many times the value appears in the array.
 *
 * Input:   arr   - an array
 *          value - anything
 * Output:  a number
 *
 * Examples:
 *   countValue([1, 2, 1, 1], 1)        ->  3
 *   countValue(["a", "b"], "c")        ->  0
 */
function countValue(arr, value) {
  // your code here
}

/**
 * reverseArray(arr)
 *
 * Return a NEW array with the items in reverse order.
 * Do not use .reverse() (it changes the original array).
 *
 * Input:   arr - an array
 * Output:  a new array
 *
 * Examples:
 *   reverseArray([1, 2, 3])  ->  [3, 2, 1]
 *   reverseArray([])         ->  []
 */
function reverseArray(arr) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * removeDuplicates(arr)
 *
 * Return a NEW array with each value appearing only once, keeping the
 * order in which values first appeared.
 *
 * Input:   arr - an array
 * Output:  a new array
 *
 * Examples:
 *   removeDuplicates([1, 2, 2, 3, 1])       ->  [1, 2, 3]
 *   removeDuplicates(["a", "a", "a"])       ->  ["a"]
 */
function removeDuplicates(arr) {
  // your code here
}

/**
 * secondLargest(arr)
 *
 * Return the second biggest DISTINCT number.
 *
 * Input:   arr - an array of numbers with at least two different values
 * Output:  a number
 *
 * Examples:
 *   secondLargest([3, 9, 2])     ->  3
 *   secondLargest([5, 5, 4, 1])  ->  4     (the two 5s count as one value)
 */
function secondLargest(arr) {
  // your code here
}

/**
 * chunk(arr, size)
 *
 * Split the array into smaller arrays of the given size. The last
 * chunk may be smaller if there aren't enough items to fill it.
 *
 * Input:   arr  - an array
 *          size - a whole number, 1 or more
 * Output:  an array of arrays
 *
 * Examples:
 *   chunk([1, 2, 3, 4, 5], 2)  ->  [[1, 2], [3, 4], [5]]
 *   chunk([1, 2, 3], 3)        ->  [[1, 2, 3]]
 *   chunk([], 2)               ->  []
 */
function chunk(arr, size) {
  // your code here
}

/**
 * zip(a, b)
 *
 * Pair up items from two arrays of the same length.
 *
 * Input:   a - an array
 *          b - an array of the same length as a
 * Output:  an array of two-item arrays
 *
 * Examples:
 *   zip([1, 2, 3], ["a", "b", "c"])  ->  [[1, "a"], [2, "b"], [3, "c"]]
 *   zip([], [])                       ->  []
 */
function zip(a, b) {
  // your code here
}

/**
 * mostFrequent(arr)
 *
 * Return the value that appears most often. If there is a tie,
 * return the one that appeared FIRST in the array.
 *
 * Input:   arr - an array with at least one item
 * Output:  one of the values from the array
 *
 * Examples:
 *   mostFrequent([1, 3, 3, 2, 1, 3])  ->  3
 *   mostFrequent(["a", "b", "b", "a"]) ->  "a"     (tie, "a" came first)
 */
function mostFrequent(arr) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  first, last, sum, contains, doubleAll,
  largest, smallest, onlyEvens, average, countValue, reverseArray,
  removeDuplicates, secondLargest, chunk, zip, mostFrequent,
};
