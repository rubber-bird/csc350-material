// Reference solutions - 03 Conditionals
function isEven(n) { return n % 2 === 0; }
function max(a, b) { return a > b ? a : b; }
function canVote(age) { return age >= 18; }
function sign(n) {
  if (n > 0) return "positive";
  if (n < 0) return "negative";
  return "zero";
}

function maxOfThree(a, b, c) {
  let biggest = a;
  if (b > biggest) biggest = b;
  if (c > biggest) biggest = c;
  return biggest;
}
function letterGrade(score) {
  if (score >= 90) return "A";
  if (score >= 80) return "B";
  if (score >= 70) return "C";
  if (score >= 60) return "D";
  return "F";
}
function fizzBuzz(n) {
  if (n % 15 === 0) return "FizzBuzz";
  if (n % 3 === 0) return "Fizz";
  if (n % 5 === 0) return "Buzz";
  return n;
}
function isLeapYear(year) {
  if (year % 400 === 0) return true;
  if (year % 100 === 0) return false;
  return year % 4 === 0;
}

function dayType(day) {
  const d = day.toLowerCase();
  if (d === "saturday" || d === "sunday") return "weekend";
  if (d === "monday" || d === "tuesday" || d === "wednesday" || d === "thursday" || d === "friday") return "weekday";
  return "invalid";
}
function triangleType(a, b, c) {
  if (a <= 0 || b <= 0 || c <= 0) return "invalid";
  const longest = Math.max(a, b, c);
  if (a + b + c - longest <= longest) return "invalid";
  if (a === b && b === c) return "equilateral";
  if (a === b || b === c || a === c) return "isosceles";
  return "scalene";
}
function shippingCost(weightKg, isExpress) {
  let cost;
  if (weightKg <= 1) cost = 5;
  else if (weightKg <= 5) cost = 10;
  else cost = 20;
  if (isExpress) cost = cost * 2;
  return cost;
}

module.exports = {
  isEven, max, canVote, sign,
  maxOfThree, letterGrade, fizzBuzz, isLeapYear,
  dayType, triangleType, shippingCost,
};
