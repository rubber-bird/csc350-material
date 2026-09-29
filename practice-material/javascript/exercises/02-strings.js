// ============================================================
//  02 - Strings
// ============================================================
//
//  A string is text inside quotes: "hello". Useful things to know:
//
//    str.length          how many characters
//    str[0]              the first character (counting starts at 0)
//    str.toUpperCase()   "abc" -> "ABC"
//    str.toLowerCase()   "ABC" -> "abc"
//    a + b               joins two strings together
//    str.includes("x")   true if "x" appears somewhere in str
//    str.slice(1, 3)     characters from position 1 up to (not including) 3
//    str.split(" ")      "a b c" -> ["a", "b", "c"]
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * greet(name)
 *
 * Build a greeting for the given name.
 *
 * Input:   name - a string
 * Output:  a string: "Hello, " + name + "!"
 *
 * Examples:
 *   greet("Ana")  ->  "Hello, Ana!"
 *   greet("Bob")  ->  "Hello, Bob!"
 */
function greet(name) {
  // your code here
}

/**
 * shout(str)
 *
 * Return the string in ALL CAPS.
 *
 * Input:   str - a string
 * Output:  the same string, uppercase
 *
 * Examples:
 *   shout("hello")       ->  "HELLO"
 *   shout("Mixed Case")  ->  "MIXED CASE"
 */
function shout(str) {
  // your code here
}

/**
 * whisper(str)
 *
 * Return the string in all lowercase.
 *
 * Input:   str - a string
 * Output:  the same string, lowercase
 *
 * Examples:
 *   whisper("HELLO")  ->  "hello"
 *   whisper("QuIeT")  ->  "quiet"
 */
function whisper(str) {
  // your code here
}

/**
 * countChars(str)
 *
 * Return how many characters are in the string.
 *
 * Input:   str - a string
 * Output:  a number
 *
 * Examples:
 *   countChars("banana")  ->  6
 *   countChars("")        ->  0
 */
function countChars(str) {
  // your code here
}

/**
 * firstChar(str)
 *
 * Return the first character.
 *
 * Input:   str - a string with at least one character
 * Output:  a string of length 1
 *
 * Example:
 *   firstChar("hello")  ->  "h"
 */
function firstChar(str) {
  // your code here
}

/**
 * lastChar(str)
 *
 * Return the last character. Remember the last position is length - 1.
 *
 * Input:   str - a string with at least one character
 * Output:  a string of length 1
 *
 * Examples:
 *   lastChar("hello")  ->  "o"
 *   lastChar("x")      ->  "x"
 */
function lastChar(str) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * joinWithSpace(a, b)
 *
 * Join two words with a single space between them.
 *
 * Input:   a - a string
 *          b - a string
 * Output:  a string
 *
 * Example:
 *   joinWithSpace("good", "morning")  ->  "good morning"
 */
function joinWithSpace(a, b) {
  // your code here
}

/**
 * hasLetter(str, letter)
 *
 * Return true if the letter appears anywhere in the string.
 * Uppercase and lowercase count as different letters.
 *
 * Input:   str    - a string
 *          letter - a string of length 1
 * Output:  true or false
 *
 * Examples:
 *   hasLetter("cat", "a")    ->  true
 *   hasLetter("dog", "a")    ->  false
 *   hasLetter("Apple", "a")  ->  false   ("A" is not "a")
 */
function hasLetter(str, letter) {
  // your code here
}

/**
 * capitalize(str)
 *
 * Make the first letter uppercase and everything else lowercase.
 * Hint: str[0] gives the first letter, str.slice(1) gives the rest.
 *
 * Input:   str - a string with at least one character
 * Output:  a string
 *
 * Examples:
 *   capitalize("hello")  ->  "Hello"
 *   capitalize("wORLD")  ->  "World"
 */
function capitalize(str) {
  // your code here
}

/**
 * initials(fullName)
 *
 * Return the first letter of each word, in uppercase, joined together.
 *
 * Input:   fullName - a string of words separated by single spaces
 * Output:  a string
 *
 * Examples:
 *   initials("Ada Lovelace")          ->  "AL"
 *   initials("grace brewster hopper") ->  "GBH"
 */
function initials(fullName) {
  // your code here
}

/**
 * removeSpaces(str)
 *
 * Return the string with every space removed.
 * Hint: str.replaceAll(" ", "") or str.split(" ").join("")
 *
 * Input:   str - a string
 * Output:  a string with no spaces
 *
 * Examples:
 *   removeSpaces("a b c")         ->  "abc"
 *   removeSpaces("  hi  there  ") ->  "hithere"
 */
function removeSpaces(str) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * reverse(str)
 *
 * Return the string backwards.
 * Try it with a loop that walks from the last character to the first.
 *
 * Input:   str - a string
 * Output:  a string
 *
 * Examples:
 *   reverse("abc")  ->  "cba"
 *   reverse("")     ->  ""
 */
function reverse(str) {
  // your code here
}

/**
 * countVowels(str)
 *
 * Count how many vowels (a, e, i, o, u) the string contains.
 * Uppercase vowels count too.
 *
 * Input:   str - a string
 * Output:  a number
 *
 * Examples:
 *   countVowels("hello")     ->  2
 *   countVowels("rhythm")    ->  0
 *   countVowels("AEIOU aei") ->  8
 */
function countVowels(str) {
  // your code here
}

/**
 * isPalindrome(str)
 *
 * A palindrome reads the same forwards and backwards.
 * Ignore capital letters and spaces.
 *
 * Input:   str - a string
 * Output:  true or false
 *
 * Examples:
 *   isPalindrome("racecar")          ->  true
 *   isPalindrome("hello")            ->  false
 *   isPalindrome("Never odd or even") ->  true
 */
function isPalindrome(str) {
  // your code here
}

/**
 * titleCase(sentence)
 *
 * Capitalize the first letter of every word. Words are separated
 * by single spaces. Other letters become lowercase.
 *
 * Input:   sentence - a string
 * Output:  a string
 *
 * Examples:
 *   titleCase("hello world")        ->  "Hello World"
 *   titleCase("tHE quick BROWN fox") ->  "The Quick Brown Fox"
 */
function titleCase(sentence) {
  // your code here
}

/**
 * truncate(str, maxLength)
 *
 * If the string is longer than maxLength, cut it down to maxLength
 * characters and add "..." to the end. Otherwise return it unchanged.
 *
 * Input:   str       - a string
 *          maxLength - a whole number
 * Output:  a string
 *
 * Examples:
 *   truncate("Hello world", 5)  ->  "Hello..."
 *   truncate("Hi", 5)           ->  "Hi"
 *   truncate("Hello", 5)        ->  "Hello"
 */
function truncate(str, maxLength) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  greet, shout, whisper, countChars, firstChar, lastChar,
  joinWithSpace, hasLetter, capitalize, initials, removeSpaces,
  reverse, countVowels, isPalindrome, titleCase, truncate,
};
