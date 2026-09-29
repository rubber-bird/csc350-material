// ============================================================
//  01 - Numbers and Arithmetic
// ============================================================
//
//  Fill in each function below. The comment above each function
//  tells you what it must do, what it receives (Input), what it must
//  give back (Output), and a few examples.
//
//  Run the tests:   open index.html in a browser
//
//  Tip: a function gives back a value with the `return` keyword.
//  If a test says "Received: undefined", you forgot to return.
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * fortyTwo()
 *
 * Return the number 42. That's it. This one just checks that you
 * know how `return` works.
 *
 * Input:   nothing
 * Output:  the number 42
 *
 * Example:
 *   fortyTwo()  ->  42
 */
function fortyTwo() {
  // your code here
}

/**
 * add(a, b)
 *
 * Add two numbers together.
 *
 * Input:   a - a number
 *          b - a number
 * Output:  a number, the sum of a and b
 *
 * Examples:
 *   add(2, 3)    ->  5
 *   add(-1, 1)   ->  0
 */
function add(a, b) {
  // your code here
}

/**
 * subtract(a, b)
 *
 * Subtract b from a.
 *
 * Input:   a - a number
 *          b - a number
 * Output:  a number, a minus b
 *
 * Examples:
 *   subtract(10, 4)  ->  6
 *   subtract(0, 5)   ->  -5
 */
function subtract(a, b) {
  // your code here
}

/**
 * multiply(a, b)
 *
 * Multiply two numbers.
 *
 * Input:   a - a number
 *          b - a number
 * Output:  a number, a times b
 *
 * Examples:
 *   multiply(3, 4)  ->  12
 *   multiply(7, 0)  ->  0
 */
function multiply(a, b) {
  // your code here
}

/**
 * divide(a, b)
 *
 * Divide a by b.
 *
 * Input:   a - a number
 *          b - a number (never 0 in the tests)
 * Output:  a number, a divided by b
 *
 * Examples:
 *   divide(20, 5)  ->  4
 *   divide(1, 2)   ->  0.5
 */
function divide(a, b) {
  // your code here
}

/**
 * remainder(a, b)
 *
 * Return what is left over after dividing a by b.
 * JavaScript has an operator for this: %
 *
 * Input:   a - a whole number
 *          b - a whole number
 * Output:  a number, the remainder
 *
 * Examples:
 *   remainder(10, 3)  ->  1     (10 = 3 * 3 + 1)
 *   remainder(8, 4)   ->  0
 */
function remainder(a, b) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * square(n)
 *
 * Multiply a number by itself.
 *
 * Input:   n - a number
 * Output:  a number, n times n
 *
 * Examples:
 *   square(5)   ->  25
 *   square(-3)  ->  9
 */
function square(n) {
  // your code here
}

/**
 * averageOfThree(a, b, c)
 *
 * Return the average (mean) of three numbers: add them up and
 * divide by 3.
 *
 * Input:   a, b, c - numbers
 * Output:  a number, the average
 *
 * Examples:
 *   averageOfThree(1, 2, 3)     ->  2
 *   averageOfThree(10, 20, 60)  ->  30
 */
function averageOfThree(a, b, c) {
  // your code here
}

/**
 * isDivisible(a, b)
 *
 * Return true if a can be divided by b with nothing left over.
 *
 * Input:   a - a whole number
 *          b - a whole number
 * Output:  true or false
 *
 * Examples:
 *   isDivisible(10, 5)  ->  true
 *   isDivisible(10, 3)  ->  false
 */
function isDivisible(a, b) {
  // your code here
}

/**
 * celsiusToFahrenheit(c)
 *
 * Convert a temperature from Celsius to Fahrenheit.
 * Formula:  F = C * 9 / 5 + 32
 *
 * Input:   c - temperature in Celsius (number)
 * Output:  temperature in Fahrenheit (number)
 *
 * Examples:
 *   celsiusToFahrenheit(0)    ->  32
 *   celsiusToFahrenheit(100)  ->  212
 *   celsiusToFahrenheit(-40)  ->  -40
 */
function celsiusToFahrenheit(c) {
  // your code here
}

/**
 * absoluteDifference(a, b)
 *
 * Return how far apart two numbers are. The answer is never negative.
 * Hint: Math.abs(x) turns a negative number positive.
 *
 * Input:   a, b - numbers
 * Output:  a number that is 0 or greater
 *
 * Examples:
 *   absoluteDifference(10, 3)  ->  7
 *   absoluteDifference(3, 10)  ->  7
 *   absoluteDifference(5, 5)   ->  0
 */
function absoluteDifference(a, b) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * clamp(n, min, max)
 *
 * Keep a number inside a range. If n is smaller than min, return min.
 * If n is bigger than max, return max. Otherwise return n unchanged.
 *
 * Input:   n   - a number
 *          min - the lowest allowed value
 *          max - the highest allowed value
 * Output:  a number between min and max (inclusive)
 *
 * Examples:
 *   clamp(5, 1, 10)   ->  5     (already inside the range)
 *   clamp(-3, 1, 10)  ->  1     (too small, so we get min)
 *   clamp(50, 1, 10)  ->  10    (too big, so we get max)
 */
function clamp(n, min, max) {
  // your code here
}

/**
 * percentOf(part, whole)
 *
 * What percentage of `whole` is `part`? Round to one decimal place.
 * Hint: Math.round(x * 10) / 10 rounds to one decimal.
 *
 * Input:   part  - a number
 *          whole - a number (never 0 in the tests)
 * Output:  a number, the percentage rounded to 1 decimal
 *
 * Examples:
 *   percentOf(50, 200)  ->  25
 *   percentOf(1, 3)     ->  33.3
 *   percentOf(2, 3)     ->  66.7
 */
function percentOf(part, whole) {
  // your code here
}

/**
 * hypotenuse(a, b)
 *
 * Given the two short sides of a right triangle, return the length
 * of the long side (Pythagoras:  c = sqrt(a*a + b*b)).
 * Hint: Math.sqrt(x)
 *
 * Input:   a, b - lengths of the two short sides (numbers)
 * Output:  a number, the length of the long side
 *
 * Examples:
 *   hypotenuse(3, 4)   ->  5
 *   hypotenuse(5, 12)  ->  13
 */
function hypotenuse(a, b) {
  // your code here
}

/**
 * secondsToClock(totalSeconds)
 *
 * Turn a number of seconds into a "m:ss" clock string.
 * Seconds must always have two digits.
 *
 * Input:   totalSeconds - a whole number, 0 or more
 * Output:  a string in the form "minutes:seconds"
 *
 * Examples:
 *   secondsToClock(90)   ->  "1:30"
 *   secondsToClock(5)    ->  "0:05"
 *   secondsToClock(600)  ->  "10:00"
 */
function secondsToClock(totalSeconds) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  fortyTwo, add, subtract, multiply, divide, remainder,
  square, averageOfThree, isDivisible, celsiusToFahrenheit, absoluteDifference,
  clamp, percentOf, hypotenuse, secondsToClock,
};
