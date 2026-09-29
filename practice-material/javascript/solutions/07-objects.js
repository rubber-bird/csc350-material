// Reference solutions - 07 Objects
function makePerson(name, age) { return { name: name, age: age }; }
function getName(person) { return person.name; }
function hasKey(obj, key) { return key in obj; }
function countKeys(obj) { return Object.keys(obj).length; }

function describePerson(person) { return person.name + " is " + person.age + " years old"; }
function keys(obj) { return Object.keys(obj); }
function getOrDefault(obj, key, fallback) { return key in obj ? obj[key] : fallback; }
function withBirthday(person) { return { ...person, age: person.age + 1 }; }
function names(people) {
  const out = [];
  for (const p of people) out.push(p.name);
  return out;
}
function oldest(people) {
  let best = people[0];
  for (const p of people) if (p.age > best.age) best = p;
  return best;
}

function totalPrice(cart) {
  let total = 0;
  for (const item of cart) total += item.price * item.quantity;
  return total;
}
function countWords(sentence) {
  const counts = {};
  for (const word of sentence.split(" ")) {
    if (word in counts) counts[word]++;
    else counts[word] = 1;
  }
  return counts;
}
function invert(obj) {
  const out = {};
  for (const key in obj) out[obj[key]] = key;
  return out;
}
function groupBy(items, key) {
  const groups = {};
  for (const item of items) {
    const value = item[key];
    if (!(value in groups)) groups[value] = [];
    groups[value].push(item);
  }
  return groups;
}
function deepGet(obj, path) {
  let current = obj;
  for (const part of path.split(".")) {
    if (current === undefined || current === null) return undefined;
    current = current[part];
  }
  return current;
}

module.exports = {
  makePerson, getName, hasKey, countKeys,
  describePerson, keys, getOrDefault, withBirthday, names, oldest,
  totalPrice, countWords, invert, groupBy, deepGet,
};
