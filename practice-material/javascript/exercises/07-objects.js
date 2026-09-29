// ============================================================
//  07 - Objects
// ============================================================
//
//  An object holds named values:   { name: "Sam", age: 30 }
//
//    obj.name  or  obj["name"]    read a value
//    obj.age = 31                 set a value
//    "name" in obj                true if the key exists
//    Object.keys(obj)             ["name", "age"]
//    Object.values(obj)           ["Sam", 30]
//    for (const key in obj) { ... }
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * makePerson(name, age)
 *
 * Build a person object with exactly two properties.
 *
 * Input:   name - a string
 *          age  - a number
 * Output:  an object { name: ..., age: ... }
 *
 * Example:
 *   makePerson("Sam", 30)  ->  { name: "Sam", age: 30 }
 */
function makePerson(name, age) {
  // your code here
}

/**
 * getName(person)
 *
 * Read the name out of a person object.
 *
 * Input:   person - an object with a name property
 * Output:  a string
 *
 * Example:
 *   getName({ name: "Sam", age: 30 })  ->  "Sam"
 */
function getName(person) {
  // your code here
}

/**
 * hasKey(obj, key)
 *
 * Return true if the object has a property with that name.
 *
 * Input:   obj - an object
 *          key - a string
 * Output:  true or false
 *
 * Examples:
 *   hasKey({ name: "Sam" }, "name")   ->  true
 *   hasKey({ name: "Sam" }, "email")  ->  false
 */
function hasKey(obj, key) {
  // your code here
}

/**
 * countKeys(obj)
 *
 * Return how many properties the object has.
 *
 * Input:   obj - an object
 * Output:  a number
 *
 * Examples:
 *   countKeys({ name: "Sam", age: 30 })  ->  2
 *   countKeys({})                        ->  0
 */
function countKeys(obj) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * describePerson(person)
 *
 * Build a sentence about the person.
 *
 * Input:   person - an object with name and age
 * Output:  a string "<name> is <age> years old"
 *
 * Example:
 *   describePerson({ name: "Sam", age: 30 })  ->  "Sam is 30 years old"
 */
function describePerson(person) {
  // your code here
}

/**
 * keys(obj)
 *
 * Return an array of the object's property names.
 *
 * Input:   obj - an object
 * Output:  an array of strings
 *
 * Examples:
 *   keys({ name: "Sam", age: 30 })  ->  ["name", "age"]
 *   keys({})                        ->  []
 */
function keys(obj) {
  // your code here
}

/**
 * getOrDefault(obj, key, fallback)
 *
 * Return the value stored under key. If the key does not exist,
 * return fallback instead.
 *
 * Input:   obj      - an object
 *          key      - a string
 *          fallback - anything
 * Output:  the stored value, or fallback
 *
 * Examples:
 *   getOrDefault({ color: "red" }, "color", "none")  ->  "red"
 *   getOrDefault({ color: "red" }, "size", "none")   ->  "none"
 */
function getOrDefault(obj, key, fallback) {
  // your code here
}

/**
 * withBirthday(person)
 *
 * Return a NEW person object with the age increased by 1.
 * The original object must not change.
 * Hint: { ...person, age: person.age + 1 } copies then overrides.
 *
 * Input:   person - an object with name and age
 * Output:  a new object
 *
 * Example:
 *   withBirthday({ name: "Sam", age: 30 })  ->  { name: "Sam", age: 31 }
 */
function withBirthday(person) {
  // your code here
}

/**
 * names(people)
 *
 * Given a list of people, collect their names.
 *
 * Input:   people - an array of person objects
 * Output:  an array of strings
 *
 * Example:
 *   names([{ name: "A", age: 1 }, { name: "B", age: 2 }])  ->  ["A", "B"]
 */
function names(people) {
  // your code here
}

/**
 * oldest(people)
 *
 * Return the person with the highest age.
 *
 * Input:   people - an array of person objects, at least one
 * Output:  one of the person objects
 *
 * Example:
 *   oldest([{ name: "A", age: 25 }, { name: "B", age: 41 }])  ->  { name: "B", age: 41 }
 */
function oldest(people) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * totalPrice(cart)
 *
 * Each item in the cart has a price and a quantity. Return the total.
 *
 * Input:   cart - an array of objects like { price: 2.5, quantity: 4 }
 * Output:  a number
 *
 * Examples:
 *   totalPrice([{ price: 2, quantity: 3 }, { price: 10, quantity: 1 }])  ->  16
 *   totalPrice([])                                                        ->  0
 */
function totalPrice(cart) {
  // your code here
}

/**
 * countWords(sentence)
 *
 * Count how often each word appears. Words are separated by single
 * spaces. Return an object where each key is a word and each value
 * is how many times it appeared.
 *
 * Input:   sentence - a string
 * Output:  an object
 *
 * Examples:
 *   countWords("the cat and the dog")  ->  { the: 2, cat: 1, and: 1, dog: 1 }
 *   countWords("a a a")                ->  { a: 3 }
 */
function countWords(sentence) {
  // your code here
}

/**
 * invert(obj)
 *
 * Swap keys and values.
 *
 * Input:   obj - an object whose values are strings
 * Output:  a new object
 *
 * Example:
 *   invert({ a: "x", b: "y" })  ->  { x: "a", y: "b" }
 */
function invert(obj) {
  // your code here
}

/**
 * groupBy(items, key)
 *
 * Group objects by the value of one of their properties.
 *
 * Input:   items - an array of objects
 *          key   - the property name to group by
 * Output:  an object whose keys are the distinct values, and whose
 *          values are arrays of the matching items (in original order)
 *
 * Example:
 *   groupBy([
 *     { name: "apple",  type: "fruit" },
 *     { name: "carrot", type: "veg" },
 *     { name: "pear",   type: "fruit" },
 *   ], "type")
 *   ->  {
 *         fruit: [{ name: "apple", type: "fruit" }, { name: "pear", type: "fruit" }],
 *         veg:   [{ name: "carrot", type: "veg" }],
 *       }
 */
function groupBy(items, key) {
  // your code here
}

/**
 * deepGet(obj, path)
 *
 * Follow a dotted path into nested objects. If any step is missing,
 * return undefined.
 *
 * Input:   obj  - an object (possibly nested)
 *          path - a string like "a.b.c"
 * Output:  the value found, or undefined
 *
 * Examples:
 *   deepGet({ user: { address: { city: "Kyiv" } } }, "user.address.city")  ->  "Kyiv"
 *   deepGet({ user: { name: "Sam" } }, "user.name")                        ->  "Sam"
 *   deepGet({ user: { name: "Sam" } }, "user.email")                       ->  undefined
 *   deepGet({}, "a.b.c")                                                   ->  undefined
 */
function deepGet(obj, path) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  makePerson, getName, hasKey, countKeys,
  describePerson, keys, getOrDefault, withBirthday, names, oldest,
  totalPrice, countWords, invert, groupBy, deepGet,
};
