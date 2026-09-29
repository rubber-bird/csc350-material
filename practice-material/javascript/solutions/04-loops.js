// Reference solutions - 04 Loops
function sumTo(n) {
  let total = 0;
  for (let i = 1; i <= n; i++) total += i;
  return total;
}
function repeatChar(char, n) {
  let out = "";
  for (let i = 0; i < n; i++) out += char;
  return out;
}
function countdown(n) {
  const out = [];
  for (let i = n; i >= 1; i--) out.push(i);
  return out;
}

function factorial(n) {
  let result = 1;
  for (let i = 2; i <= n; i++) result *= i;
  return result;
}
function countOccurrences(str, target) {
  let count = 0;
  for (let i = 0; i < str.length; i++) if (str[i] === target) count++;
  return count;
}
function range(start, end) {
  const out = [];
  for (let i = start; i <= end; i++) out.push(i);
  return out;
}
function multiplicationTable(n) {
  const out = [];
  for (let i = 1; i <= 10; i++) out.push(n * i);
  return out;
}

function countDigits(n) {
  let count = 0;
  while (n > 0) { n = Math.floor(n / 10); count++; }
  return count;
}
function reverseNumber(n) {
  let result = 0;
  while (n > 0) {
    result = result * 10 + (n % 10);
    n = Math.floor(n / 10);
  }
  return result;
}
function isPrime(n) {
  if (n < 2) return false;
  for (let i = 2; i < n; i++) if (n % i === 0) return false;
  return true;
}
function fibonacci(n) {
  let a = 0, b = 1;
  for (let i = 0; i < n; i++) {
    const next = a + b;
    a = b;
    b = next;
  }
  return a;
}
function starTriangle(n) {
  const rows = [];
  for (let i = 1; i <= n; i++) rows.push(repeatChar("*", i));
  return rows.join("\n");
}

module.exports = {
  sumTo, repeatChar, countdown,
  factorial, countOccurrences, range, multiplicationTable,
  countDigits, reverseNumber, isPrime, fibonacci, starTriangle,
};
