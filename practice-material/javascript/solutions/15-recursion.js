// Reference solutions - 16 Recursion
function rFactorial(n) {
  if (n < 0) return null;
  if (n === 0) return 1;
  return n * rFactorial(n - 1);
}
function rSum(array) {
  if (array.length === 0) return 0;
  return array[0] + rSum(array.slice(1));
}
function arraySum(array) {
  if (array.length === 0) return 0;
  const first = Array.isArray(array[0]) ? arraySum(array[0]) : array[0];
  return first + arraySum(array.slice(1));
}
function rIsEven(n) {
  if (n < 0) return rIsEven(-n);
  if (n === 0) return true;
  if (n === 1) return false;
  return rIsEven(n - 2);
}
function sumBelow(n) {
  if (n === 0 || n === 1 || n === -1) return 0;
  if (n < 0) return n + 1 + sumBelow(n + 1);
  return n - 1 + sumBelow(n - 1);
}
function rRange(x, y) {
  if (x === y || x + 1 === y || x - 1 === y) return [];
  if (x < y) return [x + 1].concat(rRange(x + 1, y));
  return [x - 1].concat(rRange(x - 1, y));
}
function exponent(base, exp) {
  if (exp === 0) return 1;
  if (exp < 0) return 1 / exponent(base, -exp);
  return base * exponent(base, exp - 1);
}
function powerOfTwo(n) {
  if (n === 1) return true;
  if (n < 1 || n !== Math.floor(n)) return false;
  return powerOfTwo(n / 2);
}
function rReverse(string) {
  if (string === "") return "";
  return rReverse(string.slice(1)) + string[0];
}
function palindrome(string) {
  const s = string.toLowerCase().split(" ").join("");
  if (s.length <= 1) return true;
  if (s[0] !== s[s.length - 1]) return false;
  return palindrome(s.slice(1, -1));
}
function modulo(x, y) {
  if (y === 0 || Number.isNaN(x) || Number.isNaN(y)) return NaN;
  if (x < 0) return -modulo(-x, y);
  if (y < 0) return modulo(x, -y);
  if (x < y) return x;
  return modulo(x - y, y);
}
function rMultiply(x, y) {
  if (y === 0 || x === 0) return 0;
  if (y < 0) return -rMultiply(x, -y);
  return x + rMultiply(x, y - 1);
}
function rDivide(x, y) {
  if (y === 0 && x === 0) return NaN;
  if (x < 0) return -rDivide(-x, y);
  if (y < 0) return -rDivide(x, -y);
  if (x < y) return 0;
  return 1 + rDivide(x - y, y);
}
function gcd(x, y) {
  if (x < 0 || y < 0) return null;
  if (y === 0) return x;
  return gcd(y, x % y);
}
function compareStr(str1, str2) {
  if (str1.length === 0 && str2.length === 0) return true;
  if (str1.length === 0 || str2.length === 0) return false;
  if (str1[0] !== str2[0]) return false;
  return compareStr(str1.slice(1), str2.slice(1));
}
function createArray(str) {
  if (str === "") return [];
  return [str[0]].concat(createArray(str.slice(1)));
}
function reverseArr(array) {
  if (array.length === 0) return [];
  return reverseArr(array.slice(1)).concat([array[0]]);
}
function buildList(value, length) {
  if (length === 0) return [];
  return [value].concat(buildList(value, length - 1));
}
function rFizzBuzz(n) {
  if (n === 0) return [];
  let word = String(n);
  if (n % 15 === 0) word = "FizzBuzz";
  else if (n % 3 === 0) word = "Fizz";
  else if (n % 5 === 0) word = "Buzz";
  return rFizzBuzz(n - 1).concat([word]);
}
function countOccurrence(array, value) {
  if (array.length === 0) return 0;
  return (array[0] === value ? 1 : 0) + countOccurrence(array.slice(1), value);
}
function rMap(array, callback) {
  if (array.length === 0) return [];
  return [callback(array[0])].concat(rMap(array.slice(1), callback));
}
function countKeysInObj(obj, key) {
  let count = 0;
  for (const k in obj) {
    if (k === key) count++;
    if (typeof obj[k] === "object" && obj[k] !== null) count += countKeysInObj(obj[k], key);
  }
  return count;
}
function countValuesInObj(obj, value) {
  let count = 0;
  for (const k in obj) {
    if (obj[k] === value) count++;
    if (typeof obj[k] === "object" && obj[k] !== null) count += countValuesInObj(obj[k], value);
  }
  return count;
}
function replaceKeysInObj(obj, oldKey, newKey) {
  for (const k in obj) {
    if (typeof obj[k] === "object" && obj[k] !== null) replaceKeysInObj(obj[k], oldKey, newKey);
    if (k === oldKey) {
      obj[newKey] = obj[k];
      delete obj[k];
    }
  }
  return obj;
}
function rFibonacci(n) {
  if (n <= 0) return null;
  if (n === 1) return [0, 1];
  const prev = rFibonacci(n - 1);
  return prev.concat([prev[prev.length - 1] + prev[prev.length - 2]]);
}
function nthFibo(n) {
  if (n < 0) return null;
  if (n < 2) return n;
  return nthFibo(n - 1) + nthFibo(n - 2);
}
function capitalizeWords(array) {
  if (array.length === 0) return [];
  return [array[0].toUpperCase()].concat(capitalizeWords(array.slice(1)));
}
function capitalizeFirst(array) {
  if (array.length === 0) return [];
  const word = array[0][0].toUpperCase() + array[0].slice(1);
  return [word].concat(capitalizeFirst(array.slice(1)));
}
function nestedEvenSum(obj) {
  let total = 0;
  for (const k in obj) {
    if (typeof obj[k] === "number" && obj[k] % 2 === 0) total += obj[k];
    if (typeof obj[k] === "object" && obj[k] !== null) total += nestedEvenSum(obj[k]);
  }
  return total;
}
function rFlatten(array) {
  if (array.length === 0) return [];
  const first = Array.isArray(array[0]) ? rFlatten(array[0]) : [array[0]];
  return first.concat(rFlatten(array.slice(1)));
}
function letterTally(str, obj) {
  obj = obj || {};
  if (str === "") return obj;
  obj[str[0]] = (obj[str[0]] || 0) + 1;
  return letterTally(str.slice(1), obj);
}
function compress(list) {
  if (list.length === 0) return [];
  if (list[0] === list[1]) return compress(list.slice(1));
  return [list[0]].concat(compress(list.slice(1)));
}
function augmentElements(array, aug) {
  if (array.length === 0) return [];
  return [array[0].concat([aug])].concat(augmentElements(array.slice(1), aug));
}
function minimizeZeroes(array) {
  if (array.length === 0) return [];
  if (array[0] === 0 && array[1] === 0) return minimizeZeroes(array.slice(1));
  return [array[0]].concat(minimizeZeroes(array.slice(1)));
}
function alternateSign(array) {
  if (array.length === 0) return [];
  const first = Math.abs(array[0]);
  const rest = alternateSign(array.slice(1));
  // Flip every sign of the rest, then make it start negative.
  const flipped = rMap(rest, (n) => -n);
  return [first].concat(flipped);
}
function numToText(str) {
  const words = ["zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
  if (str === "") return "";
  const first = str[0] >= "0" && str[0] <= "9" ? words[Number(str[0])] : str[0];
  return first + numToText(str.slice(1));
}
function tagCount(tag, node) {
  node = node || document.body;
  let count = 0;
  for (const child of node.children) {
    if (child.tagName.toLowerCase() === tag.toLowerCase()) count++;
    count += tagCount(tag, child);
  }
  return count;
}
function rBinarySearch(array, target, min, max) {
  if (min === undefined) min = 0;
  if (max === undefined) max = array.length - 1;
  if (min > max) return null;
  const mid = Math.floor((min + max) / 2);
  if (array[mid] === target) return mid;
  if (array[mid] < target) return rBinarySearch(array, target, mid + 1, max);
  return rBinarySearch(array, target, min, mid - 1);
}
function mergeSort(array) {
  if (array.length <= 1) return array.slice();
  const mid = Math.floor(array.length / 2);
  const left = mergeSort(array.slice(0, mid));
  const right = mergeSort(array.slice(mid));
  // merge two sorted arrays, recursively
  const merge = (a, b) => {
    if (a.length === 0) return b;
    if (b.length === 0) return a;
    if (a[0] <= b[0]) return [a[0]].concat(merge(a.slice(1), b));
    return [b[0]].concat(merge(a, b.slice(1)));
  };
  return merge(left, right);
}
function clone(input) {
  if (Array.isArray(input)) {
    if (input.length === 0) return [];
    return [clone(input[0])].concat(clone(input.slice(1)));
  }
  if (typeof input === "object" && input !== null) {
    const out = {};
    for (const k in input) out[k] = clone(input[k]);
    return out;
  }
  return input;
}

module.exports = {
  rFactorial, rSum, arraySum, rIsEven, sumBelow, rRange, exponent, powerOfTwo, rReverse, palindrome,
  modulo, rMultiply, rDivide, gcd, compareStr, createArray, reverseArr, buildList, rFizzBuzz, countOccurrence,
  rMap, countKeysInObj, countValuesInObj, replaceKeysInObj, rFibonacci, nthFibo,
  capitalizeWords, capitalizeFirst, nestedEvenSum, rFlatten, letterTally, compress, augmentElements,
  minimizeZeroes, alternateSign, numToText, tagCount, rBinarySearch, mergeSort, clone,
};
