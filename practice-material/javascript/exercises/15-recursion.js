// ============================================================
//  15 - Recursion
// ============================================================
//
//  A recursive function is one that calls itself. It needs two things:
//
//    a BASE case      the simplest input, answered directly, no recursion
//    a RECURSIVE case  shrink the problem a little and call yourself
//
//    function countDown(n) {
//      if (n === 0) return "done";        // base case
//      return countDown(n - 1);           // recursive case: smaller n
//    }
//
//  RULES for every function in this file (the tests check them):
//    - the function must call ITSELF by name
//    - no loops: no for, while, do, forEach, map, filter, reduce
//      (exception: prompts 22, 23, 24, 29, 37 and 40 walk an OBJECT or the
//      DOM, and may loop over the keys of one level. They must still call
//      themselves for each nested value.)
//    - do not add extra parameters unless the prompt already has them
//    - some prompts also forbid a shortcut, for example the % operator
//
//  Useful for shrinking the problem:
//    array.slice(1)      everything except the first item (a new array)
//    str.slice(1)        everything except the first character
//    [x].concat(rest)    build a new array from a head and a tail
//
//  Forty prompts, roughly easy to hard. They do not depend on each
//  other, so skip a stuck one and come back.
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * 1. rFactorial(n)
 *
 * n! is n * (n-1) * (n-2) * ... * 1.  0! is 1.
 * Negative numbers have no factorial: return null.
 *
 * Input:   n - a whole number
 * Output:  a number, or null
 *
 * Examples:
 *   rFactorial(5)   ->  120
 *   rFactorial(0)   ->  1
 *   rFactorial(-3)  ->  null
 */
function rFactorial(n) {
  // your code here
}

/**
 * 2. rSum(array)
 *
 * Add up all the numbers. Do not change the input array.
 * Hint: the sum is the first number plus the sum of the rest.
 *
 * Input:   array - an array of whole numbers (may be empty)
 * Output:  a number
 *
 * Examples:
 *   rSum([1, 2, 3, 4, 5, 6])  ->  21
 *   rSum([])                  ->  0
 *   rSum([-2, -5])            ->  -7
 */
function rSum(array) {
  // your code here
}

/**
 * 3. arraySum(array)
 *
 * Add up all the numbers in an array that may contain nested arrays,
 * as deep as they go. Do not use Array.prototype.flat.
 *
 * Input:   array - an array of numbers and arrays
 * Output:  a number
 *
 * Examples:
 *   arraySum([1, [2, 3], [[4]], 5])  ->  15
 *   arraySum([])                     ->  0
 */
function arraySum(array) {
  // your code here
}

/**
 * 4. rIsEven(n)
 *
 * Is the number even? Do not use the % operator.
 * Hint: 0 is even, 1 is odd, and n has the same answer as n - 2.
 * Negative numbers: make them positive first.
 *
 * Input:   n - a whole number
 * Output:  true or false
 *
 * Examples:
 *   rIsEven(4)   ->  true
 *   rIsEven(7)   ->  false
 *   rIsEven(-6)  ->  true
 */
function rIsEven(n) {
  // your code here
}

/**
 * 5. sumBelow(n)
 *
 * Add up all whole numbers between 0 and n, not including n.
 * For negative n, add the numbers between n and 0, not including n.
 *
 * Input:   n - a whole number
 * Output:  a number
 *
 * Examples:
 *   sumBelow(10)  ->  45      (1 + 2 + ... + 9)
 *   sumBelow(7)   ->  21
 *   sumBelow(1)   ->  0
 *   sumBelow(-6)  ->  -15     (-1 + -2 + ... + -5)
 */
function sumBelow(n) {
  // your code here
}

/**
 * 6. rRange(x, y)
 *
 * The whole numbers strictly between x and y. If x is bigger than y,
 * count downwards.
 *
 * Input:   x, y - whole numbers
 * Output:  an array of numbers (empty if nothing lies between)
 *
 * Examples:
 *   rRange(2, 9)   ->  [3, 4, 5, 6, 7, 8]
 *   rRange(7, 2)   ->  [6, 5, 4, 3]
 *   rRange(5, 5)   ->  []
 *   rRange(2, 3)   ->  []
 *   rRange(-3, 2)  ->  [-2, -1, 0, 1]
 */
function rRange(x, y) {
  // your code here
}

/**
 * 7. exponent(base, exp)
 *
 * base raised to the power exp, without Math.pow or **.
 * A negative exponent means 1 divided by the positive power.
 *
 * Input:   base - a whole number
 *          exp  - a whole number
 * Output:  a number
 *
 * Examples:
 *   exponent(4, 3)   ->  64
 *   exponent(8, 0)   ->  1
 *   exponent(-3, 4)  ->  81
 *   exponent(4, -2)  ->  0.0625
 */
function exponent(base, exp) {
  // your code here
}

/**
 * 8. powerOfTwo(n)
 *
 * Is n a power of two (1, 2, 4, 8, 16, ...)?
 * Hint: keep halving. If you reach exactly 1 it was a power of two.
 *
 * Input:   n - a whole number
 * Output:  true or false
 *
 * Examples:
 *   powerOfTwo(1)   ->  true
 *   powerOfTwo(16)  ->  true
 *   powerOfTwo(10)  ->  false
 *   powerOfTwo(0)   ->  false
 */
function powerOfTwo(n) {
  // your code here
}

/**
 * 9. rReverse(string)
 *
 * Reverse a string without .reverse().
 * Hint: the reverse of "abc" is the reverse of "bc" followed by "a".
 *
 * Input:   string - a string
 * Output:  a string
 *
 * Examples:
 *   rReverse("abc")  ->  "cba"
 *   rReverse("")     ->  ""
 */
function rReverse(string) {
  // your code here
}

/**
 * 10. palindrome(string)
 *
 * Does the string read the same backwards? Ignore spaces and capital
 * letters. Do not use .reverse(). Hint: compare the first and last
 * characters, then check what is between them.
 *
 * Input:   string - a string
 * Output:  true or false
 *
 * Examples:
 *   palindrome("racecar")            ->  true
 *   palindrome("Never odd or even")  ->  true
 *   palindrome("hello")              ->  false
 */
function palindrome(string) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * 11. modulo(x, y)
 *
 * The remainder of x divided by y, WITHOUT using %, *, / or Math.
 * The result has the same sign as x, like the real % does.
 * Hint: keep subtracting y from x until x is smaller than y.
 * modulo(0, 0) is NaN.
 *
 * Input:   x, y - whole numbers
 * Output:  a number
 *
 * Examples:
 *   modulo(5, 2)     ->  1
 *   modulo(17, 5)    ->  2
 *   modulo(22, 6)    ->  4
 *   modulo(-4, 2)    ->  -0  (or 0)
 *   modulo(-79, 82)  ->  -79
 */
function modulo(x, y) {
  // your code here
}

/**
 * 12. rMultiply(x, y)
 *
 * Multiply WITHOUT using *, /, % or Math.
 * Hint: x * y is x added to itself y times. Watch the signs.
 *
 * Input:   x, y - whole numbers
 * Output:  a number
 *
 * Examples:
 *   rMultiply(17, 5)   ->  85
 *   rMultiply(0, 32)   ->  0
 *   rMultiply(-2, -2)  ->  4
 *   rMultiply(-8, 3)   ->  -24
 */
function rMultiply(x, y) {
  // your code here
}

/**
 * 13. rDivide(x, y)
 *
 * Whole-number division WITHOUT using /, *, % or Math. Throw away any
 * remainder (round towards zero). Hint: how many times can you
 * subtract y from x? rDivide(0, 0) is NaN.
 *
 * Input:   x, y - whole numbers
 * Output:  a number
 *
 * Examples:
 *   rDivide(17, 5)     ->  3
 *   rDivide(78, 453)   ->  0
 *   rDivide(-79, 82)   ->  0  (or -0)
 *   rDivide(-275, -582) ->  0
 */
function rDivide(x, y) {
  // your code here
}

/**
 * 14. gcd(x, y)
 *
 * The greatest common divisor: the largest number that divides both.
 * Euclid's method: gcd(x, y) equals gcd(y, x % y), and gcd(x, 0) is x.
 * If either number is negative, return null.
 *
 * Input:   x, y - whole numbers
 * Output:  a number, or null
 *
 * Examples:
 *   gcd(4, 36)    ->  4
 *   gcd(24, 88)   ->  8
 *   gcd(339, 17)  ->  1
 *   gcd(-4, 2)    ->  null
 */
function gcd(x, y) {
  // your code here
}

/**
 * 15. compareStr(str1, str2)
 *
 * Are two strings identical? Compare one character at a time.
 * Do not use ===  on the whole strings.
 *
 * Input:   str1, str2 - strings
 * Output:  true or false
 *
 * Examples:
 *   compareStr("tomato", "tomato")  ->  true
 *   compareStr("house", "houses")   ->  false
 *   compareStr("", "")              ->  true
 */
function compareStr(str1, str2) {
  // your code here
}

/**
 * 16. createArray(str)
 *
 * Turn a string into an array of its characters, without .split().
 *
 * Input:   str - a string
 * Output:  an array of one-character strings
 *
 * Examples:
 *   createArray("hello")  ->  ["h", "e", "l", "l", "o"]
 *   createArray("")       ->  []
 */
function createArray(str) {
  // your code here
}

/**
 * 17. reverseArr(array)
 *
 * Reverse an array without .reverse(). Return a new array.
 *
 * Input:   array - an array
 * Output:  a new array
 *
 * Examples:
 *   reverseArr([1, 2, 3, 4])  ->  [4, 3, 2, 1]
 *   reverseArr([])            ->  []
 */
function reverseArr(array) {
  // your code here
}

/**
 * 18. buildList(value, length)
 *
 * Make an array of the given length, filled with the value.
 *
 * Input:   value  - anything
 *          length - a whole number, 0 or more
 * Output:  an array
 *
 * Examples:
 *   buildList(0, 5)  ->  [0, 0, 0, 0, 0]
 *   buildList(7, 3)  ->  [7, 7, 7]
 *   buildList("x", 0) ->  []
 */
function buildList(value, length) {
  // your code here
}

/**
 * 19. rFizzBuzz(n)
 *
 * The strings "1" to "n", except multiples of 3 become "Fizz",
 * multiples of 5 become "Buzz", and multiples of both "FizzBuzz".
 *
 * Input:   n - a whole number, 1 or more
 * Output:  an array of strings
 *
 * Example:
 *   rFizzBuzz(5)   ->  ["1", "2", "Fizz", "4", "Buzz"]
 *   rFizzBuzz(15)  ->  [..., "14", "FizzBuzz"]
 */
function rFizzBuzz(n) {
  // your code here
}

/**
 * 20. countOccurrence(array, value)
 *
 * How many times does the value appear? Use strict equality (===).
 *
 * Input:   array - an array
 *          value - anything
 * Output:  a number
 *
 * Examples:
 *   countOccurrence([2, 7, 4, 4, 1, 4], 4)                    ->  3
 *   countOccurrence([2, "banana", 4, 4, 1, "banana"], "banana") ->  2
 *   countOccurrence(["", null, 0, "0", false], 0)             ->  1
 */
function countOccurrence(array, value) {
  // your code here
}

/**
 * 21. rMap(array, callback)
 *
 * A recursive version of map: a new array where every item has been
 * passed through the callback. Do not use .map(). Do not change the
 * input array.
 *
 * Input:   array    - an array
 *          callback - a function of one argument
 * Output:  a new array
 *
 * Example:
 *   rMap([1, 2, 3], (x) => x * 2)  ->  [2, 4, 6]
 */
function rMap(array, callback) {
  // your code here
}

/**
 * 22. countKeysInObj(obj, key)
 *
 * How many times does a KEY appear in an object, counting nested
 * objects too?
 *
 * Input:   obj - an object (values may be objects)
 *          key - a string
 * Output:  a number
 *
 * Examples:
 *   var obj = { e: { x: "y" }, t: { r: { e: "r" }, p: { y: "r" } }, y: "e" };
 *   countKeysInObj(obj, "r")  ->  1
 *   countKeysInObj(obj, "e")  ->  2
 */
function countKeysInObj(obj, key) {
  // your code here
}

/**
 * 23. countValuesInObj(obj, value)
 *
 * How many times does a VALUE appear in an object, counting nested
 * objects too?
 *
 * Input:   obj   - an object
 *          value - a string
 * Output:  a number
 *
 * Examples:
 *   var obj = { e: { x: "y" }, t: { r: { e: "r" }, p: { y: "r" } }, y: "e" };
 *   countValuesInObj(obj, "r")  ->  2
 *   countValuesInObj(obj, "e")  ->  1
 */
function countValuesInObj(obj, value) {
  // your code here
}

/**
 * 24. replaceKeysInObj(obj, oldKey, newKey)
 *
 * Rename every key called oldKey to newKey, in the object and in every
 * nested object, keeping the values. Change the object IN PLACE and
 * return it. Hint: obj[newKey] = obj[oldKey]; delete obj[oldKey];
 *
 * Input:   obj            - an object
 *          oldKey, newKey - strings
 * Output:  the same object
 *
 * Example:
 *   replaceKeysInObj({ e: { x: "y" }, t: { r: { e: "r" } } }, "e", "f")
 *     ->  { f: { x: "y" }, t: { r: { f: "r" } } }
 */
function replaceKeysInObj(obj, oldKey, newKey) {
  // your code here
}

/**
 * 25. rFibonacci(n)
 *
 * The first n Fibonacci numbers, with the leading 0 included but not
 * counted:  0, 1, 1, 2, 3, 5, 8, ...
 * For n of 0 or less, return null.
 *
 * Input:   n - a whole number
 * Output:  an array of numbers, or null
 *
 * Examples:
 *   rFibonacci(1)  ->  [0, 1]
 *   rFibonacci(5)  ->  [0, 1, 1, 2, 3, 5]
 *   rFibonacci(0)  ->  null
 */
function rFibonacci(n) {
  // your code here
}

/**
 * 26. nthFibo(n)
 *
 * The Fibonacci number at position n:  0, 1, 1, 2, 3, 5, 8, 13, 21
 * Negative n: return null.
 *
 * Input:   n - a whole number
 * Output:  a number, or null
 *
 * Examples:
 *   nthFibo(5)   ->  5
 *   nthFibo(7)   ->  13
 *   nthFibo(0)   ->  0
 *   nthFibo(-5)  ->  null
 */
function nthFibo(n) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * 27. capitalizeWords(array)
 *
 * Every word in ALL CAPS. Return a new array.
 *
 * Input:   array - an array of strings
 * Output:  a new array of strings
 *
 * Example:
 *   capitalizeWords(["i", "am", "learning", "recursion"])
 *     ->  ["I", "AM", "LEARNING", "RECURSION"]
 */
function capitalizeWords(array) {
  // your code here
}

/**
 * 28. capitalizeFirst(array)
 *
 * The first letter of every word in uppercase. Return a new array.
 *
 * Input:   array - an array of strings
 * Output:  a new array of strings
 *
 * Example:
 *   capitalizeFirst(["car", "poop", "banana"])  ->  ["Car", "Poop", "Banana"]
 */
function capitalizeFirst(array) {
  // your code here
}

/**
 * 29. nestedEvenSum(obj)
 *
 * Add up every even NUMBER anywhere in the object, including inside
 * nested objects. Ignore strings and odd numbers.
 *
 * Input:   obj - an object
 * Output:  a number
 *
 * Example:
 *   nestedEvenSum({
 *     a: 2,
 *     b: { b: 2, bb: { b: 3, bb: { b: 2 } } },
 *     c: { c: { c: 2 }, cc: "ball", ccc: 5 },
 *     d: 1,
 *     e: { e: { e: 2 }, ee: "car" },
 *   })  ->  10
 */
function nestedEvenSum(obj) {
  // your code here
}

/**
 * 30. rFlatten(array)
 *
 * Flatten nested arrays of any depth into one array.
 * Do not use Array.prototype.flat.
 *
 * Input:   array - an array that may contain arrays
 * Output:  a new flat array
 *
 * Example:
 *   rFlatten([1, [2], [3, [[4]]], 5])  ->  [1, 2, 3, 4, 5]
 */
function rFlatten(array) {
  // your code here
}

/**
 * 31. letterTally(str, obj)
 *
 * Count each letter. The second parameter is there so you can pass the
 * tally object along through the recursion; callers leave it out.
 *
 * Input:   str - a string
 *          obj - (used internally) the tally so far
 * Output:  an object mapping each letter to its count
 *
 * Examples:
 *   letterTally("potato")       ->  { p: 1, o: 2, t: 2, a: 1 }
 *   letterTally("mississippi")  ->  { m: 1, i: 4, s: 4, p: 2 }
 */
function letterTally(str, obj) {
  // your code here
}

/**
 * 32. compress(list)
 *
 * Collapse runs of the same value into one. Keep the order. Return a
 * new array and do not change the input.
 *
 * Input:   list - an array
 * Output:  a new array
 *
 * Examples:
 *   compress([1, 2, 2, 3, 4, 4, 5, 5, 5])            ->  [1, 2, 3, 4, 5]
 *   compress([1, 2, 2, 3, 4, 4, 2, 5, 5, 5, 4, 4])   ->  [1, 2, 3, 4, 2, 5, 4]
 */
function compress(list) {
  // your code here
}

/**
 * 33. augmentElements(array, aug)
 *
 * Every element is itself an array. Add aug to the end of each one.
 *
 * Input:   array - an array of arrays
 *          aug   - anything
 * Output:  an array of arrays
 *
 * Example:
 *   augmentElements([[], [3], [7]], 5)  ->  [[5], [3, 5], [7, 5]]
 */
function augmentElements(array, aug) {
  // your code here
}

/**
 * 34. minimizeZeroes(array)
 *
 * Reduce any run of zeroes to a single 0. Return a new array.
 *
 * Input:   array - an array of numbers
 * Output:  a new array
 *
 * Examples:
 *   minimizeZeroes([2, 0, 0, 0, 1, 4])        ->  [2, 0, 1, 4]
 *   minimizeZeroes([2, 0, 0, 0, 1, 0, 0, 4])  ->  [2, 0, 1, 0, 4]
 */
function minimizeZeroes(array) {
  // your code here
}

/**
 * 35. alternateSign(array)
 *
 * Make the numbers alternate positive, negative, positive... whatever
 * their original signs. The first is always positive. Return a new array.
 *
 * Input:   array - an array of numbers
 * Output:  a new array
 *
 * Examples:
 *   alternateSign([2, 7, 8, 3, 1, 4])      ->  [2, -7, 8, -3, 1, -4]
 *   alternateSign([-2, -7, 8, 3, -1, 4])   ->  [2, -7, 8, -3, 1, -4]
 */
function alternateSign(array) {
  // your code here
}

/**
 * 36. numToText(str)
 *
 * Replace every single digit with its word.
 *
 * Input:   str - a string containing digits 0 to 9
 * Output:  a string
 *
 * Example:
 *   numToText("I have 5 dogs and 6 ponies")  ->  "I have five dogs and six ponies"
 */
function numToText(str) {
  // your code here
}

/**
 * 37. tagCount(tag, node)   (browser only)
 *
 * How many elements with the given tag name are inside node, at any
 * depth? If node is not given, start at document.body.
 * Hint: node.children, element.tagName (uppercase).
 *
 * Input:   tag  - a tag name like "p" or "span"
 *          node - an element (optional)
 * Output:  a number
 *
 * Example:
 *   // <div><p>beep</p><div><p><span>blip</span></p></div><p>blorp</p></div>
 *   tagCount("p", thatDiv)     ->  3
 *   tagCount("span", thatDiv)  ->  1
 */
function tagCount(tag, node) {
  // your code here
}

/**
 * 38. rBinarySearch(array, target, min, max)
 *
 * Find the position of target in a SORTED array by looking at the
 * middle, then searching only the half that could contain it.
 * min and max are the bounds of the part you are searching; callers
 * leave them out. Return null if not found. Do not change the array.
 *
 * Input:   array    - an array of numbers in ascending order
 *          target   - a number
 *          min, max - (used internally) current search bounds
 * Output:  a position, or null
 *
 * Examples:
 *   rBinarySearch([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], 5)  ->  5
 *   rBinarySearch([1, 3, 5, 7], 4)                    ->  null
 */
function rBinarySearch(array, target, min, max) {
  // your code here
}

/**
 * 39. mergeSort(array)
 *
 * Sort numbers from smallest to largest WITHOUT .sort():
 * split the array in half, sort each half (recursively), then merge
 * the two sorted halves. Return a new array.
 *
 * Input:   array - an array of numbers
 * Output:  a new sorted array
 *
 * Example:
 *   mergeSort([34, 7, 23, 32, 5, 62])  ->  [5, 7, 23, 32, 34, 62]
 */
function mergeSort(array) {
  // your code here
}

/**
 * 40. clone(input)
 *
 * A deep copy: a new object or array with the same contents, where
 * every nested object and array is also a new copy. Do not use JSON
 * methods or Object.assign. Do not change the input.
 *
 * Input:   input - an object or array (may be nested)
 * Output:  a new object or array
 *
 * Example:
 *   var original = { a: 1, b: { bb: { bbb: 2 } }, c: 3 };
 *   var copy = clone(original);
 *   copy            ->  { a: 1, b: { bb: { bbb: 2 } }, c: 3 }
 *   copy === original      ->  false
 *   copy.b === original.b  ->  false
 */
function clone(input) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  rFactorial, rSum, arraySum, rIsEven, sumBelow, rRange, exponent, powerOfTwo, rReverse, palindrome,
  modulo, rMultiply, rDivide, gcd, compareStr, createArray, reverseArr, buildList, rFizzBuzz, countOccurrence,
  rMap, countKeysInObj, countValuesInObj, replaceKeysInObj, rFibonacci, nthFibo,
  capitalizeWords, capitalizeFirst, nestedEvenSum, rFlatten, letterTally, compress, augmentElements,
  minimizeZeroes, alternateSign, numToText, tagCount, rBinarySearch, mergeSort, clone,
};
