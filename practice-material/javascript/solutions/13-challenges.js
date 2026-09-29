// Reference solutions - 14 Challenges
function sumOfDigits(n) {
  let total = 0;
  for (const digit of String(n)) total += Number(digit);
  return total;
}
function longestWord(sentence) {
  let best = "";
  for (const word of sentence.split(" ")) if (word.length > best.length) best = word;
  return best;
}
function isAnagram(a, b) {
  const sortLetters = (s) => s.toLowerCase().split("").sort().join("");
  return sortLetters(a) === sortLetters(b);
}

function runLengthEncode(str) {
  let out = "";
  let i = 0;
  while (i < str.length) {
    let count = 1;
    while (str[i + count] === str[i]) count++;
    out += count + str[i];
    i += count;
  }
  return out;
}
function caesarCipher(str, shift) {
  let out = "";
  for (const ch of str) {
    if (ch >= "a" && ch <= "z") {
      const code = ch.charCodeAt(0) - 97;
      out += String.fromCharCode(((code + shift) % 26) + 97);
    } else {
      out += ch;
    }
  }
  return out;
}
function twoSum(numbers, target) {
  for (let i = 0; i < numbers.length; i++) {
    for (let j = i + 1; j < numbers.length; j++) {
      if (numbers[i] + numbers[j] === target) return [i, j];
    }
  }
  return null;
}
function flatten(nested) {
  const out = [];
  for (const inner of nested) for (const item of inner) out.push(item);
  return out;
}

function binarySearch(sortedNumbers, target) {
  let low = 0;
  let high = sortedNumbers.length - 1;
  while (low <= high) {
    const mid = Math.floor((low + high) / 2);
    if (sortedNumbers[mid] === target) return mid;
    if (sortedNumbers[mid] < target) low = mid + 1;
    else high = mid - 1;
  }
  return -1;
}
function bubbleSort(numbers) {
  const arr = numbers.slice();
  let swapped = true;
  while (swapped) {
    swapped = false;
    for (let i = 0; i < arr.length - 1; i++) {
      if (arr[i] > arr[i + 1]) {
        const tmp = arr[i];
        arr[i] = arr[i + 1];
        arr[i + 1] = tmp;
        swapped = true;
      }
    }
  }
  return arr;
}
function romanToInt(roman) {
  const values = { I: 1, V: 5, X: 10, L: 50, C: 100, D: 500, M: 1000 };
  let total = 0;
  for (let i = 0; i < roman.length; i++) {
    const current = values[roman[i]];
    const next = values[roman[i + 1]];
    if (next > current) total -= current;
    else total += current;
  }
  return total;
}
function matrixSum(matrix) {
  let total = 0;
  for (const row of matrix) for (const n of row) total += n;
  return total;
}
function balancedBrackets(str) {
  const pairs = { ")": "(", "]": "[", "}": "{" };
  const stack = [];
  for (const ch of str) {
    if (ch === "(" || ch === "[" || ch === "{") {
      stack.push(ch);
    } else {
      if (stack.pop() !== pairs[ch]) return false;
    }
  }
  return stack.length === 0;
}

module.exports = {
  sumOfDigits, longestWord, isAnagram,
  runLengthEncode, caesarCipher, twoSum, flatten,
  binarySearch, bubbleSort, romanToInt, matrixSum, balancedBrackets,
};
