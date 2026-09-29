// ============================================================
//  03 - Conditionals and Comparison
// ============================================================
//
//  Make decisions in code:
//
//    if (x > 5) { ... } else if (x > 2) { ... } else { ... }
//
//  Comparison:   ===  !==  <  >  <=  >=
//  Combine:      &&  (and)     ||  (or)     !  (not)
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * isEven(n)
 *
 * Return true if the number is even. Hint: even numbers have a
 * remainder of 0 when divided by 2.
 *
 * Input:   n - a whole number
 * Output:  true or false
 *
 * Examples:
 *   isEven(4)  ->  true
 *   isEven(7)  ->  false
 *   isEven(0)  ->  true
 */
function isEven(n) {
  // your code here
}

/**
 * max(a, b)
 *
 * Return the larger of two numbers. If they are equal, return either.
 *
 * Input:   a, b - numbers
 * Output:  a number
 *
 * Examples:
 *   max(3, 9)   ->  9
 *   max(10, 2)  ->  10
 *   max(5, 5)   ->  5
 */
function max(a, b) {
  // your code here
}

/**
 * canVote(age)
 *
 * Return true if the person is 18 or older.
 *
 * Input:   age - a number
 * Output:  true or false
 *
 * Examples:
 *   canVote(18)  ->  true
 *   canVote(17)  ->  false
 */
function canVote(age) {
  // your code here
}

/**
 * sign(n)
 *
 * Describe whether a number is positive, negative, or zero.
 *
 * Input:   n - a number
 * Output:  one of the strings "positive", "negative", "zero"
 *
 * Examples:
 *   sign(12)  ->  "positive"
 *   sign(-3)  ->  "negative"
 *   sign(0)   ->  "zero"
 */
function sign(n) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * maxOfThree(a, b, c)
 *
 * Return the largest of three numbers.
 *
 * Input:   a, b, c - numbers
 * Output:  a number
 *
 * Examples:
 *   maxOfThree(1, 5, 3)   ->  5
 *   maxOfThree(9, 2, 4)   ->  9
 *   maxOfThree(-1, -5, -2) ->  -1
 */
function maxOfThree(a, b, c) {
  // your code here
}

/**
 * letterGrade(score)
 *
 * Convert a score from 0 to 100 into a letter grade.
 *
 *   90 and above  ->  "A"
 *   80 to 89      ->  "B"
 *   70 to 79      ->  "C"
 *   60 to 69      ->  "D"
 *   below 60      ->  "F"
 *
 * Input:   score - a number from 0 to 100
 * Output:  a one-letter string
 *
 * Examples:
 *   letterGrade(95)  ->  "A"
 *   letterGrade(80)  ->  "B"
 *   letterGrade(59)  ->  "F"
 */
function letterGrade(score) {
  // your code here
}

/**
 * fizzBuzz(n)
 *
 * The classic:
 *   divisible by both 3 and 5  ->  "FizzBuzz"
 *   divisible by 3 only        ->  "Fizz"
 *   divisible by 5 only        ->  "Buzz"
 *   anything else              ->  the number itself
 *
 * Input:   n - a whole number
 * Output:  a string or the number
 *
 * Examples:
 *   fizzBuzz(3)   ->  "Fizz"
 *   fizzBuzz(5)   ->  "Buzz"
 *   fizzBuzz(15)  ->  "FizzBuzz"
 *   fizzBuzz(7)   ->  7
 */
function fizzBuzz(n) {
  // your code here
}

/**
 * isLeapYear(year)
 *
 * A year is a leap year if:
 *   - it is divisible by 4,
 *   - EXCEPT years divisible by 100 are NOT leap years,
 *   - EXCEPT years divisible by 400 ARE leap years.
 *
 * Input:   year - a whole number
 * Output:  true or false
 *
 * Examples:
 *   isLeapYear(2024)  ->  true    (divisible by 4)
 *   isLeapYear(2023)  ->  false
 *   isLeapYear(1900)  ->  false   (divisible by 100, not by 400)
 *   isLeapYear(2000)  ->  true    (divisible by 400)
 */
function isLeapYear(year) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * dayType(day)
 *
 * Given a day name, say whether it is a weekday or the weekend.
 * Accept any capitalization. If it isn't a real day, return "invalid".
 *
 * Input:   day - a string
 * Output:  "weekday", "weekend", or "invalid"
 *
 * Examples:
 *   dayType("Monday")    ->  "weekday"
 *   dayType("saturday")  ->  "weekend"
 *   dayType("SUNDAY")    ->  "weekend"
 *   dayType("Funday")    ->  "invalid"
 */
function dayType(day) {
  // your code here
}

/**
 * triangleType(a, b, c)
 *
 * Classify a triangle by its three side lengths:
 *   all three equal          ->  "equilateral"
 *   exactly two equal        ->  "isosceles"
 *   all different            ->  "scalene"
 * But first: if any side is 0 or less, or if the two shorter sides
 * added together are not longer than the longest side, it is not
 * a valid triangle -> "invalid".
 *
 * Input:   a, b, c - numbers
 * Output:  "equilateral", "isosceles", "scalene", or "invalid"
 *
 * Examples:
 *   triangleType(3, 3, 3)  ->  "equilateral"
 *   triangleType(3, 4, 3)  ->  "isosceles"
 *   triangleType(3, 4, 5)  ->  "scalene"
 *   triangleType(1, 2, 3)  ->  "invalid"   (1 + 2 is not > 3)
 *   triangleType(0, 4, 5)  ->  "invalid"
 */
function triangleType(a, b, c) {
  // your code here
}

/**
 * shippingCost(weightKg, isExpress)
 *
 * Work out the cost of shipping a parcel:
 *   up to 1 kg           ->  5
 *   over 1 kg, up to 5 kg ->  10
 *   over 5 kg            ->  20
 * If isExpress is true, double the cost.
 *
 * Input:   weightKg  - a number greater than 0
 *          isExpress - true or false
 * Output:  a number
 *
 * Examples:
 *   shippingCost(0.5, false)  ->  5
 *   shippingCost(1, false)    ->  5
 *   shippingCost(3, false)    ->  10
 *   shippingCost(3, true)     ->  20
 *   shippingCost(12, true)    ->  40
 */
function shippingCost(weightKg, isExpress) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  isEven, max, canVote, sign,
  maxOfThree, letterGrade, fizzBuzz, isLeapYear,
  dayType, triangleType, shippingCost,
};
