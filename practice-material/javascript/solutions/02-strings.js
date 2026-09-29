// Reference solutions - 02 Strings
function greet(name) { return "Hello, " + name + "!"; }
function shout(str) { return str.toUpperCase(); }
function whisper(str) { return str.toLowerCase(); }
function countChars(str) { return str.length; }
function firstChar(str) { return str[0]; }
function lastChar(str) { return str[str.length - 1]; }

function joinWithSpace(a, b) { return a + " " + b; }
function hasLetter(str, letter) { return str.includes(letter); }
function capitalize(str) { return str[0].toUpperCase() + str.slice(1).toLowerCase(); }
function initials(fullName) {
  const words = fullName.split(" ");
  let result = "";
  for (const word of words) result += word[0].toUpperCase();
  return result;
}
function removeSpaces(str) { return str.split(" ").join(""); }

function reverse(str) {
  let out = "";
  for (let i = str.length - 1; i >= 0; i--) out += str[i];
  return out;
}
function countVowels(str) {
  let count = 0;
  for (const ch of str.toLowerCase()) {
    if ("aeiou".includes(ch)) count++;
  }
  return count;
}
function isPalindrome(str) {
  const clean = str.toLowerCase().split(" ").join("");
  return clean === reverse(clean);
}
function titleCase(sentence) {
  const words = sentence.split(" ");
  const out = [];
  for (const word of words) out.push(capitalize(word));
  return out.join(" ");
}
function truncate(str, maxLength) {
  if (str.length > maxLength) return str.slice(0, maxLength) + "...";
  return str;
}

module.exports = {
  greet, shout, whisper, countChars, firstChar, lastChar,
  joinWithSpace, hasLetter, capitalize, initials, removeSpaces,
  reverse, countVowels, isPalindrome, titleCase, truncate,
};
