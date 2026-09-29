// Reference solutions - 01 Numbers and Arithmetic
function fortyTwo() { return 42; }
function add(a, b) { return a + b; }
function subtract(a, b) { return a - b; }
function multiply(a, b) { return a * b; }
function divide(a, b) { return a / b; }
function remainder(a, b) { return a % b; }

function square(n) { return n * n; }
function averageOfThree(a, b, c) { return (a + b + c) / 3; }
function isDivisible(a, b) { return a % b === 0; }
function celsiusToFahrenheit(c) { return c * 9 / 5 + 32; }
function absoluteDifference(a, b) { return Math.abs(a - b); }

function clamp(n, min, max) {
  if (n < min) return min;
  if (n > max) return max;
  return n;
}
function percentOf(part, whole) { return Math.round((part / whole) * 100 * 10) / 10; }
function hypotenuse(a, b) { return Math.sqrt(a * a + b * b); }
function secondsToClock(totalSeconds) {
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = totalSeconds % 60;
  const padded = seconds < 10 ? "0" + seconds : "" + seconds;
  return minutes + ":" + padded;
}

module.exports = {
  fortyTwo, add, subtract, multiply, divide, remainder,
  square, averageOfThree, isDivisible, celsiusToFahrenheit, absoluteDifference,
  clamp, percentOf, hypotenuse, secondsToClock,
};
