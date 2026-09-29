// ============================================================
//  10 - Functions as values
// ============================================================
//
//  In JavaScript a function is a value. You can pass it to another
//  function, store it in a variable, or return it from a function.
//
//    const double = (x) => x * 2;      // arrow function
//    applyTo(double, 5)                // pass it along
//
//  A function returned from another function remembers the variables
//  around it. This is called a closure:
//
//    function makeGreeter(greeting) {
//      return function (name) { return greeting + ", " + name; };
//    }
//    const hi = makeGreeter("Hi");  hi("Sam")  ->  "Hi, Sam"
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * applyTo(fn, value)
 *
 * Call the function with the value and return whatever it gives back.
 *
 * Input:   fn    - a function that takes one argument
 *          value - anything
 * Output:  the result of fn(value)
 *
 * Examples:
 *   applyTo((x) => x + 1, 5)              ->  6
 *   applyTo((s) => s.toUpperCase(), "hi") ->  "HI"
 */
function applyTo(fn, value) {
  // your code here
}

/**
 * applyTwice(fn, value)
 *
 * Call the function on the value, then call it again on the result.
 *
 * Input:   fn    - a function that takes one argument
 *          value - anything
 * Output:  fn(fn(value))
 *
 * Examples:
 *   applyTwice((x) => x * 2, 5)     ->  20
 *   applyTwice((s) => s + "!", "hi") ->  "hi!!"
 */
function applyTwice(fn, value) {
  // your code here
}

/**
 * repeat(fn, n)
 *
 * Call the function n times. Return nothing.
 *
 * Input:   fn - a function that takes no arguments
 *          n  - a whole number, 0 or more
 * Output:  nothing
 *
 * Example:
 *   let count = 0;
 *   repeat(() => count++, 3);
 *   count  ->  3
 */
function repeat(fn, n) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * makeAdder(n)
 *
 * Return a NEW function that adds n to whatever it is given.
 *
 * Input:   n - a number
 * Output:  a function that takes one number and returns a number
 *
 * Example:
 *   const add5 = makeAdder(5);
 *   add5(10)  ->  15
 *   add5(0)   ->  5
 */
function makeAdder(n) {
  // your code here
}

/**
 * makeMultiplier(n)
 *
 * Return a NEW function that multiplies its argument by n.
 *
 * Input:   n - a number
 * Output:  a function
 *
 * Example:
 *   const triple = makeMultiplier(3);
 *   triple(7)  ->  21
 */
function makeMultiplier(n) {
  // your code here
}

/**
 * makeCounter()
 *
 * Return a NEW function that, each time it is called, returns how many
 * times it has been called so far. Each counter keeps its own count.
 *
 * Input:   nothing
 * Output:  a function that returns a number
 *
 * Example:
 *   const c = makeCounter();
 *   c()  ->  1
 *   c()  ->  2
 *   const other = makeCounter();
 *   other()  ->  1
 */
function makeCounter() {
  // your code here
}

/**
 * myMap(arr, fn)
 *
 * Build a NEW array by calling fn on every item. Write your own loop
 * instead of using .map().
 *
 * Input:   arr - an array
 *          fn  - a function that takes one item and returns a new value
 * Output:  a new array, same length as arr
 *
 * Examples:
 *   myMap([1, 2, 3], (x) => x * 10)  ->  [10, 20, 30]
 *   myMap([], (x) => x)              ->  []
 */
function myMap(arr, fn) {
  // your code here
}

/**
 * myFilter(arr, fn)
 *
 * Build a NEW array containing only the items for which fn returns true.
 * Write your own loop instead of using .filter().
 *
 * Input:   arr - an array
 *          fn  - a function that takes one item and returns true or false
 * Output:  a new array
 *
 * Examples:
 *   myFilter([1, 2, 3, 4], (x) => x % 2 === 0)    ->  [2, 4]
 *   myFilter(["a", "bb"], (s) => s.length > 1)    ->  ["bb"]
 */
function myFilter(arr, fn) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * myReduce(arr, fn, start)
 *
 * Combine all items into one value. Begin with `start`, then for each
 * item set  result = fn(result, item).  Return the final result.
 * Write your own loop instead of using .reduce().
 *
 * Input:   arr   - an array
 *          fn    - a function (resultSoFar, item) => newResult
 *          start - the initial value
 * Output:  a single value
 *
 * Examples:
 *   myReduce([1, 2, 3], (total, x) => total + x, 0)         ->  6
 *   myReduce(["a", "b"], (s, x) => s + x, "")               ->  "ab"
 *   myReduce([], (t, x) => t + x, 100)                      ->  100
 */
function myReduce(arr, fn, start) {
  // your code here
}

/**
 * compose(f, g)
 *
 * Return a NEW function that applies g first, then f.
 *
 * Input:   f, g - functions that each take one argument
 * Output:  a function x => f(g(x))
 *
 * Example:
 *   const addOne = (x) => x + 1;
 *   const double = (x) => x * 2;
 *   const doubleThenAddOne = compose(addOne, double);
 *   doubleThenAddOne(5)  ->  11      (double 5 = 10, then + 1)
 */
function compose(f, g) {
  // your code here
}

/**
 * once(fn)
 *
 * Return a NEW function that only ever runs fn the first time it is
 * called. Every later call returns the same result from that first run,
 * without running fn again.
 *
 * Input:   fn - a function that takes no arguments
 * Output:  a function
 *
 * Example:
 *   let runs = 0;
 *   const init = once(() => { runs++; return "ready"; });
 *   init()  ->  "ready"
 *   init()  ->  "ready"
 *   runs    ->  1
 */
function once(fn) {
  // your code here
}

/**
 * pipe(fns)
 *
 * Given an ARRAY of functions, return a NEW function that passes its
 * argument through each of them in order, left to right.
 *
 * Input:   fns - an array of one-argument functions
 * Output:  a function
 *
 * Examples:
 *   const run = pipe([(x) => x + 1, (x) => x * 2, (x) => x - 3]);
 *   run(5)  ->  9        ((5 + 1) * 2 - 3)
 *   pipe([])(42)  ->  42 (no functions: value passes through unchanged)
 */
function pipe(fns) {
  // your code here
}

/**
 * memoize(fn)
 *
 * Return a NEW function that remembers results. The first time it is
 * called with some argument it runs fn and stores the answer. Any
 * later call with the SAME argument returns the stored answer without
 * running fn again. Hint: store answers in an object keyed by the argument.
 *
 * Input:   fn - a function that takes one argument
 * Output:  a function
 *
 * Example:
 *   let runs = 0;
 *   const slowSquare = (n) => { runs++; return n * n; };
 *   const fastSquare = memoize(slowSquare);
 *   fastSquare(4)  ->  16     (runs is now 1)
 *   fastSquare(4)  ->  16     (runs is still 1)
 *   fastSquare(5)  ->  25     (runs is now 2)
 */
function memoize(fn) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  applyTo, applyTwice, repeat,
  makeAdder, makeMultiplier, makeCounter, myMap, myFilter,
  myReduce, compose, once, pipe, memoize,
};
