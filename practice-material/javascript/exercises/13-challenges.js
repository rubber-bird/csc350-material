// ============================================================
//  13 - Challenges
// ============================================================
//
//  These mix everything from the earlier files: strings, loops,
//  arrays, objects. Read the description carefully, look at the
//  examples, and think about the steps before you type.
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * sumOfDigits(n)
 *
 * Add up the digits of a positive whole number.
 *
 * Input:   n - a whole number, 0 or more
 * Output:  a number
 *
 * Examples:
 *   sumOfDigits(1234)  ->  10     (1 + 2 + 3 + 4)
 *   sumOfDigits(0)     ->  0
 */
function sumOfDigits(n) {
  // your code here
}

/**
 * longestWord(sentence)
 *
 * Return the longest word. If two words tie, return the first one.
 *
 * Input:   sentence - words separated by single spaces
 * Output:  a string
 *
 * Examples:
 *   longestWord("the quick brown fox")  ->  "quick"
 *   longestWord("a bb cc")              ->  "bb"
 */
function longestWord(sentence) {
  // your code here
}

/**
 * isAnagram(a, b)
 *
 * Two words are anagrams if they use exactly the same letters in a
 * different order. Ignore capital letters.
 * Hint: sort the letters of each word and compare.
 *
 * Input:   a, b - strings
 * Output:  true or false
 *
 * Examples:
 *   isAnagram("listen", "silent")  ->  true
 *   isAnagram("Hello", "olleh")    ->  true
 *   isAnagram("abc", "abd")        ->  false
 */
function isAnagram(a, b) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * runLengthEncode(str)
 *
 * Compress runs of the same character into count + character.
 *
 * Input:   str - a string (may be empty)
 * Output:  a string
 *
 * Examples:
 *   runLengthEncode("aaabbc")  ->  "3a2b1c"
 *   runLengthEncode("abc")     ->  "1a1b1c"
 *   runLengthEncode("")        ->  ""
 */
function runLengthEncode(str) {
  // your code here
}

/**
 * caesarCipher(str, shift)
 *
 * Shift every lowercase letter forward in the alphabet by `shift`
 * places, wrapping around from z back to a. Leave anything that is
 * not a lowercase letter unchanged.
 * Hint: "a".charCodeAt(0) is 97, String.fromCharCode(97) is "a".
 *
 * Input:   str   - a string
 *          shift - a whole number from 0 to 25
 * Output:  a string
 *
 * Examples:
 *   caesarCipher("abc", 1)     ->  "bcd"
 *   caesarCipher("xyz", 3)     ->  "abc"
 *   caesarCipher("hi there!", 2) ->  "jk vjgtg!"
 */
function caesarCipher(str, shift) {
  // your code here
}

/**
 * twoSum(numbers, target)
 *
 * Find two DIFFERENT positions in the array whose numbers add up to
 * target. Return them as [i, j] with i < j. There is always exactly
 * one answer in the tests.
 *
 * Input:   numbers - an array of numbers
 *          target  - a number
 * Output:  an array of two positions
 *
 * Examples:
 *   twoSum([2, 7, 11, 15], 9)  ->  [0, 1]     (2 + 7)
 *   twoSum([3, 2, 4], 6)       ->  [1, 2]     (2 + 4)
 */
function twoSum(numbers, target) {
  // your code here
}

/**
 * flatten(nested)
 *
 * Turn an array that contains arrays into one flat array.
 * Only one level deep is needed.
 *
 * Input:   nested - an array whose items are arrays
 * Output:  a single array
 *
 * Examples:
 *   flatten([[1, 2], [3], [4, 5]])  ->  [1, 2, 3, 4, 5]
 *   flatten([[], [1], []])          ->  [1]
 */
function flatten(nested) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * binarySearch(sortedNumbers, target)
 *
 * Find the position of target in an array that is already sorted from
 * smallest to largest. Return -1 if it is not there.
 * Do it the fast way: look at the middle item; if target is smaller,
 * search the left half, if bigger, the right half, and repeat.
 *
 * Input:   sortedNumbers - an array of numbers in ascending order
 *          target        - a number
 * Output:  a position (number), or -1
 *
 * Examples:
 *   binarySearch([1, 3, 5, 7, 9], 7)   ->  3
 *   binarySearch([1, 3, 5, 7, 9], 1)   ->  0
 *   binarySearch([1, 3, 5, 7, 9], 4)   ->  -1
 */
function binarySearch(sortedNumbers, target) {
  // your code here
}

/**
 * bubbleSort(numbers)
 *
 * Return a NEW array sorted from smallest to largest, WITHOUT using
 * .sort(). Bubble sort: repeatedly walk through the array and swap any
 * two neighbours that are in the wrong order, until no swaps are needed.
 *
 * Input:   numbers - an array of numbers
 * Output:  a new sorted array
 *
 * Examples:
 *   bubbleSort([5, 1, 4, 2, 8])  ->  [1, 2, 4, 5, 8]
 *   bubbleSort([])               ->  []
 */
function bubbleSort(numbers) {
  // your code here
}

/**
 * romanToInt(roman)
 *
 * Convert a Roman numeral to a number.
 *   I = 1, V = 5, X = 10, L = 50, C = 100, D = 500, M = 1000
 * Normally you add the values up, but when a smaller value comes
 * BEFORE a bigger one, you subtract it instead (IV = 4, IX = 9, XL = 40).
 *
 * Input:   roman - a valid Roman numeral string in uppercase
 * Output:  a number
 *
 * Examples:
 *   romanToInt("III")      ->  3
 *   romanToInt("IV")       ->  4
 *   romanToInt("LVIII")    ->  58
 *   romanToInt("MCMXCIV")  ->  1994
 */
function romanToInt(roman) {
  // your code here
}

/**
 * matrixSum(matrix)
 *
 * A matrix is an array of rows, and each row is an array of numbers.
 * Return the sum of every number in it.
 *
 * Input:   matrix - an array of arrays of numbers
 * Output:  a number
 *
 * Examples:
 *   matrixSum([[1, 2], [3, 4]])  ->  10
 *   matrixSum([[5]])             ->  5
 *   matrixSum([])                ->  0
 */
function matrixSum(matrix) {
  // your code here
}

/**
 * balancedBrackets(str)
 *
 * Check whether every opening bracket ( [ { has a matching closing
 * bracket ) ] } in the right order.
 * Hint: walk through the string, pushing openers onto an array (a
 * "stack") and popping when you see a closer. At the end the stack
 * must be empty.
 *
 * Input:   str - a string made only of the characters ( ) [ ] { }
 * Output:  true or false
 *
 * Examples:
 *   balancedBrackets("()")        ->  true
 *   balancedBrackets("([]{})")    ->  true
 *   balancedBrackets("(]")        ->  false
 *   balancedBrackets("((")        ->  false
 *   balancedBrackets("")          ->  true
 */
function balancedBrackets(str) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  sumOfDigits, longestWord, isAnagram,
  runLengthEncode, caesarCipher, twoSum, flatten,
  binarySearch, bubbleSort, romanToInt, matrixSum, balancedBrackets,
};
