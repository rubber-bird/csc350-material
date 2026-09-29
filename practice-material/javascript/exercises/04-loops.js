// ============================================================
//  04 - Loops
// ============================================================
//
//  Repeat something many times:
//
//    for (let i = 0; i < 5; i++) { ... }     // i = 0, 1, 2, 3, 4
//    while (condition) { ... }
//
//  Build up an answer in a variable declared BEFORE the loop:
//
//    let total = 0;
//    for (let i = 1; i <= n; i++) { total = total + i; }
//    return total;
//
//  Please solve these with loops, not with array helpers like .map().
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * sumTo(n)
 *
 * Add up every whole number from 1 to n.
 *
 * Input:   n - a whole number, 1 or more
 * Output:  a number
 *
 * Examples:
 *   sumTo(4)    ->  10      (1 + 2 + 3 + 4)
 *   sumTo(1)    ->  1
 *   sumTo(100)  ->  5050
 */
function sumTo(n) {
  // your code here
}

/**
 * repeatChar(char, n)
 *
 * Build a string by repeating a character n times.
 *
 * Input:   char - a string
 *          n    - a whole number, 0 or more
 * Output:  a string
 *
 * Examples:
 *   repeatChar("x", 3)   ->  "xxx"
 *   repeatChar("ab", 2)  ->  "abab"
 *   repeatChar("z", 0)   ->  ""
 */
function repeatChar(char, n) {
  // your code here
}

/**
 * countdown(n)
 *
 * Return an array counting down from n to 1.
 * Hint: start with an empty array [] and .push() onto it.
 *
 * Input:   n - a whole number, 1 or more
 * Output:  an array of numbers
 *
 * Examples:
 *   countdown(5)  ->  [5, 4, 3, 2, 1]
 *   countdown(1)  ->  [1]
 */
function countdown(n) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * factorial(n)
 *
 * n! means n * (n-1) * (n-2) * ... * 1.  By definition 0! is 1.
 *
 * Input:   n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   factorial(5)  ->  120     (5 * 4 * 3 * 2 * 1)
 *   factorial(1)  ->  1
 *   factorial(0)  ->  1
 */
function factorial(n) {
  // your code here
}

/**
 * countOccurrences(str, target)
 *
 * Count how many times a single character appears in a string.
 *
 * Input:   str    - a string
 *          target - a string of length 1
 * Output:  a number
 *
 * Examples:
 *   countOccurrences("banana", "a")  ->  3
 *   countOccurrences("hello", "z")   ->  0
 */
function countOccurrences(str, target) {
  // your code here
}

/**
 * range(start, end)
 *
 * Return an array of every whole number from start to end, inclusive.
 *
 * Input:   start - a whole number
 *          end   - a whole number, equal to or larger than start
 * Output:  an array of numbers
 *
 * Examples:
 *   range(2, 5)  ->  [2, 3, 4, 5]
 *   range(1, 1)  ->  [1]
 */
function range(start, end) {
  // your code here
}

/**
 * multiplicationTable(n)
 *
 * Return the n times table from 1 to 10 as an array.
 *
 * Input:   n - a whole number
 * Output:  an array of 10 numbers
 *
 * Examples:
 *   multiplicationTable(2)  ->  [2, 4, 6, 8, 10, 12, 14, 16, 18, 20]
 *   multiplicationTable(0)  ->  [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
 */
function multiplicationTable(n) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * countDigits(n)
 *
 * Count the digits of a positive whole number WITHOUT turning it
 * into a string. Hint: Math.floor(n / 10) chops off the last digit.
 * Keep chopping until nothing is left, counting each chop.
 *
 * Input:   n - a whole number, 1 or more
 * Output:  a number
 *
 * Examples:
 *   countDigits(4321)     ->  4
 *   countDigits(7)        ->  1
 *   countDigits(1000000)  ->  7
 */
function countDigits(n) {
  // your code here
}

/**
 * reverseNumber(n)
 *
 * Reverse the digits of a positive whole number, again WITHOUT strings.
 * Hint: n % 10 gives the last digit.
 *
 * Input:   n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   reverseNumber(1234)  ->  4321
 *   reverseNumber(1200)  ->  21
 *   reverseNumber(5)     ->  5
 */
function reverseNumber(n) {
  // your code here
}

/**
 * isPrime(n)
 *
 * A prime number is greater than 1 and can only be divided evenly by
 * 1 and itself. Check every number from 2 up to n - 1: if any of them
 * divides n evenly, it is not prime.
 *
 * Input:   n - a whole number
 * Output:  true or false
 *
 * Examples:
 *   isPrime(2)   ->  true
 *   isPrime(9)   ->  false   (3 * 3)
 *   isPrime(13)  ->  true
 *   isPrime(1)   ->  false
 */
function isPrime(n) {
  // your code here
}

/**
 * fibonacci(n)
 *
 * The Fibonacci sequence starts 0, 1 and every next number is the sum
 * of the two before it: 0, 1, 1, 2, 3, 5, 8, 13, ...
 * Return the n-th number, counting from 0.
 *
 * Input:   n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   fibonacci(0)   ->  0
 *   fibonacci(1)   ->  1
 *   fibonacci(6)   ->  8
 *   fibonacci(10)  ->  55
 */
function fibonacci(n) {
  // your code here
}

/**
 * starTriangle(n)
 *
 * Build a triangle of stars with n rows. Row 1 has one star, row 2
 * has two, and so on. Rows are separated by "\n" (a newline).
 * There is no "\n" after the last row.
 *
 * Input:   n - a whole number, 1 or more
 * Output:  a string
 *
 * Examples:
 *   starTriangle(1)  ->  "*"
 *   starTriangle(3)  ->  "*\n**\n***"
 *                        which prints as:   *
 *                                           **
 *                                           ***
 */
function starTriangle(n) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  sumTo, repeatChar, countdown,
  factorial, countOccurrences, range, multiplicationTable,
  countDigits, reverseNumber, isPrime, fibonacci, starTriangle,
};
