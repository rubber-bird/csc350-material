// Reference solutions - 06 Arrays
function first(arr) { return arr[0]; }
function last(arr) { return arr[arr.length - 1]; }
function sum(arr) {
  let total = 0;
  for (const n of arr) total += n;
  return total;
}
function contains(arr, value) { return arr.includes(value); }
function doubleAll(arr) {
  const out = [];
  for (const n of arr) out.push(n * 2);
  return out;
}

function largest(arr) {
  let best = arr[0];
  for (const n of arr) if (n > best) best = n;
  return best;
}
function smallest(arr) {
  let best = arr[0];
  for (const n of arr) if (n < best) best = n;
  return best;
}
function onlyEvens(arr) {
  const out = [];
  for (const n of arr) if (n % 2 === 0) out.push(n);
  return out;
}
function average(arr) { return sum(arr) / arr.length; }
function countValue(arr, value) {
  let count = 0;
  for (const item of arr) if (item === value) count++;
  return count;
}
function reverseArray(arr) {
  const out = [];
  for (let i = arr.length - 1; i >= 0; i--) out.push(arr[i]);
  return out;
}

function removeDuplicates(arr) {
  const out = [];
  for (const item of arr) if (!out.includes(item)) out.push(item);
  return out;
}
function secondLargest(arr) {
  const unique = removeDuplicates(arr);
  const top = largest(unique);
  let second = -Infinity;
  for (const n of unique) if (n !== top && n > second) second = n;
  return second;
}
function chunk(arr, size) {
  const out = [];
  for (let i = 0; i < arr.length; i += size) out.push(arr.slice(i, i + size));
  return out;
}
function zip(a, b) {
  const out = [];
  for (let i = 0; i < a.length; i++) out.push([a[i], b[i]]);
  return out;
}
function mostFrequent(arr) {
  let best = arr[0];
  let bestCount = 0;
  for (const item of arr) {
    const count = countValue(arr, item);
    if (count > bestCount) { best = item; bestCount = count; }
  }
  return best;
}

module.exports = {
  first, last, sum, contains, doubleAll,
  largest, smallest, onlyEvens, average, countValue, reverseArray,
  removeDuplicates, secondLargest, chunk, zip, mostFrequent,
};
