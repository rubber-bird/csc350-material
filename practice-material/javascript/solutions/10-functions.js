// Reference solutions - 10 Functions as values
function applyTo(fn, value) { return fn(value); }
function applyTwice(fn, value) { return fn(fn(value)); }
function repeat(fn, n) { for (let i = 0; i < n; i++) fn(); }

function makeAdder(n) { return function (x) { return x + n; }; }
function makeMultiplier(n) { return function (x) { return x * n; }; }
function makeCounter() {
  let count = 0;
  return function () { count++; return count; };
}
function myMap(arr, fn) {
  const out = [];
  for (const item of arr) out.push(fn(item));
  return out;
}
function myFilter(arr, fn) {
  const out = [];
  for (const item of arr) if (fn(item)) out.push(item);
  return out;
}

function myReduce(arr, fn, start) {
  let result = start;
  for (const item of arr) result = fn(result, item);
  return result;
}
function compose(f, g) { return function (x) { return f(g(x)); }; }
function once(fn) {
  let done = false;
  let result;
  return function () {
    if (!done) { result = fn(); done = true; }
    return result;
  };
}
function pipe(fns) {
  return function (x) {
    let value = x;
    for (const fn of fns) value = fn(value);
    return value;
  };
}
function memoize(fn) {
  const cache = {};
  return function (arg) {
    if (!(arg in cache)) cache[arg] = fn(arg);
    return cache[arg];
  };
}

module.exports = {
  applyTo, applyTwice, repeat,
  makeAdder, makeMultiplier, makeCounter, myMap, myFilter,
  myReduce, compose, once, pipe, memoize,
};
