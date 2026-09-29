// ============================================================
//  09 - Real-world data
// ============================================================
//
//  Real programs work with lists of objects that describe things:
//  users, products, orders. These exercises use data shaped like a
//  small online shop. The tests pass the data in; here is what it
//  looks like so you know which properties exist:
//
//  A user:
//    { id: 1, firstName: "Ana", lastName: "Silva", email: "ana@example.com",
//      age: 29, isActive: true }
//
//  A product:
//    { id: 101, name: "Laptop", price: 999.99, category: "electronics",
//      stock: 5, tags: ["computer", "work"] }
//
//  An order (items point at products by id):
//    { id: 5001, userId: 1, status: "shipped",
//      items: [{ productId: 101, quantity: 1 }, { productId: 103, quantity: 2 }] }
//
//  A student:
//    { name: "Ana", grades: { math: 90, science: 82, art: 75 } }
//
//  Useful tools:
//    arr.find(fn)            first item where fn returns true, or undefined
//    arr.filter(fn)          new array of items where fn returns true
//    arr.map(fn)             new array made by transforming each item
//    Object.values(obj)      the values of an object as an array
//    n.toFixed(2)            "999.99" - a number as a string with 2 decimals
//    { ...obj, price: 5 }    a copy of obj with price changed
//
//  "Do not change the original" means the object or array you were
//  given must look the same after your function runs.
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * fullName(user)
 *
 * Join the first and last name with a space.
 *
 * Input:   user - a user object
 * Output:  a string
 *
 * Example:
 *   fullName({ firstName: "Ana", lastName: "Silva", ... })  ->  "Ana Silva"
 */
function fullName(user) {
  // your code here
}

/**
 * isAdult(user)
 *
 * Return true if the user is 18 or older.
 *
 * Input:   user - a user object
 * Output:  true or false
 *
 * Examples:
 *   isAdult({ age: 29, ... })  ->  true
 *   isAdult({ age: 15, ... })  ->  false
 */
function isAdult(user) {
  // your code here
}

/**
 * emailDomain(user)
 *
 * Return the part of the email after the "@".
 * Hint: email.split("@") gives ["ana", "example.com"].
 *
 * Input:   user - a user object
 * Output:  a string
 *
 * Example:
 *   emailDomain({ email: "ana@example.com", ... })  ->  "example.com"
 */
function emailDomain(user) {
  // your code here
}

/**
 * productLabel(product)
 *
 * Build a label showing the name and price with exactly two decimals.
 *
 * Input:   product - a product object
 * Output:  a string "<name> - $<price>"
 *
 * Examples:
 *   productLabel({ name: "Laptop", price: 999.99, ... })  ->  "Laptop - $999.99"
 *   productLabel({ name: "Pen", price: 2, ... })          ->  "Pen - $2.00"
 */
function productLabel(product) {
  // your code here
}

/**
 * isInStock(product)
 *
 * Return true if there is at least one in stock.
 *
 * Input:   product - a product object
 * Output:  true or false
 *
 * Examples:
 *   isInStock({ stock: 5, ... })  ->  true
 *   isInStock({ stock: 0, ... })  ->  false
 */
function isInStock(product) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * findById(items, id)
 *
 * Find the object with the matching id. Works for users, products,
 * orders, anything with an id property.
 *
 * Input:   items - an array of objects that each have an id
 *          id    - a number
 * Output:  the matching object, or undefined if none matches
 *
 * Examples:
 *   findById(products, 101)   ->  { id: 101, name: "Laptop", ... }
 *   findById(products, 999)   ->  undefined
 */
function findById(items, id) {
  // your code here
}

/**
 * activeUserNames(users)
 *
 * Return the full names of only the active users, in the same order.
 *
 * Input:   users - an array of user objects
 * Output:  an array of strings
 *
 * Example:
 *   activeUserNames([
 *     { firstName: "Ana", lastName: "Silva", isActive: true, ... },
 *     { firstName: "Ben", lastName: "Wu", isActive: false, ... },
 *   ])  ->  ["Ana Silva"]
 */
function activeUserNames(users) {
  // your code here
}

/**
 * productsInCategory(products, category)
 *
 * Return only the products in the given category.
 *
 * Input:   products - an array of product objects
 *          category - a string
 * Output:  an array of product objects (possibly empty)
 *
 * Example:
 *   productsInCategory(products, "electronics").length  ->  2
 *   productsInCategory(products, "toys")                ->  []
 */
function productsInCategory(products, category) {
  // your code here
}

/**
 * cheapestProduct(products)
 *
 * Return the product with the lowest price.
 *
 * Input:   products - an array of product objects, at least one
 * Output:  a product object
 *
 * Example:
 *   cheapestProduct(products).name  ->  "Pen"
 */
function cheapestProduct(products) {
  // your code here
}

/**
 * totalStock(products)
 *
 * Add up the stock of every product.
 *
 * Input:   products - an array of product objects
 * Output:  a number
 *
 * Example:
 *   totalStock([{ stock: 5, ... }, { stock: 0, ... }, { stock: 12, ... }])  ->  17
 */
function totalStock(products) {
  // your code here
}

/**
 * applyDiscount(product, percent)
 *
 * Return a NEW product with the price reduced by the given percent,
 * rounded to two decimals. Do not change the original product.
 * Hint: Math.round(x * 100) / 100 rounds to two decimals.
 *
 * Input:   product - a product object
 *          percent - a number from 0 to 100
 * Output:  a new product object
 *
 * Examples:
 *   applyDiscount({ name: "Laptop", price: 1000, ... }, 10).price  ->  900
 *   applyDiscount({ name: "Pen", price: 2, ... }, 25).price        ->  1.5
 *   (the original product's price is unchanged)
 */
function applyDiscount(product, percent) {
  // your code here
}

/**
 * averageGrade(student)
 *
 * Return the mean of all the student's grades.
 *
 * Input:   student - a student object with a grades object
 * Output:  a number
 *
 * Example:
 *   averageGrade({ name: "Ana", grades: { math: 90, science: 80, art: 70 } })  ->  80
 */
function averageGrade(student) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * orderTotal(order, products)
 *
 * Work out how much an order costs. For each item, look up the
 * product by its id to get the price, and multiply by the quantity.
 * Round the final total to two decimals.
 *
 * Input:   order    - an order object
 *          products - an array of product objects
 * Output:  a number
 *
 * Example:
 *   products: Laptop (id 101) costs 999.99, Mouse (id 103) costs 25
 *   orderTotal({ items: [{ productId: 101, quantity: 1 }, { productId: 103, quantity: 2 }], ... }, products)
 *     ->  1049.99
 */
function orderTotal(order, products) {
  // your code here
}

/**
 * restock(products, id, amount)
 *
 * Return a NEW array of products where the product with the given id
 * has its stock increased by amount. Every other product stays the
 * same. Do not change the original array or its objects.
 *
 * Input:   products - an array of product objects
 *          id       - a number
 *          amount   - a number
 * Output:  a new array of product objects
 *
 * Example:
 *   const updated = restock(products, 102, 10);
 *   findById(updated, 102).stock    ->  10 more than before
 *   findById(products, 102).stock   ->  unchanged
 */
function restock(products, id, amount) {
  // your code here
}

/**
 * countByStatus(orders)
 *
 * Count how many orders have each status.
 *
 * Input:   orders - an array of order objects
 * Output:  an object mapping each status to a count
 *
 * Example:
 *   countByStatus([{ status: "shipped" }, { status: "pending" }, { status: "shipped" }])
 *     ->  { shipped: 2, pending: 1 }
 */
function countByStatus(orders) {
  // your code here
}

/**
 * bestSeller(orders, products)
 *
 * Return the NAME of the product with the highest total quantity
 * sold across all orders.
 *
 * Input:   orders   - an array of order objects
 *          products - an array of product objects
 * Output:  a string
 *
 * Example:
 *   two orders: one buys 1 Laptop + 2 Mice, another buys 3 Mice
 *   bestSeller(orders, products)  ->  "Mouse"
 */
function bestSeller(orders, products) {
  // your code here
}

/**
 * userSummary(user, orders, products)
 *
 * Build a summary of one user's shopping: their full name, how many
 * orders they placed, and the total spent across those orders
 * (rounded to two decimals).
 *
 * Input:   user     - a user object
 *          orders   - an array of ALL orders (filter by user.id)
 *          products - an array of product objects
 * Output:  an object { name, orderCount, totalSpent }
 *
 * Example:
 *   userSummary(ana, orders, products)
 *     ->  { name: "Ana Silva", orderCount: 2, totalSpent: 1124.99 }
 *   userSummary(userWithNoOrders, orders, products)
 *     ->  { name: "...", orderCount: 0, totalSpent: 0 }
 */
function userSummary(user, orders, products) {
  // your code here
}

/**
 * gradeReport(students)
 *
 * Build a report line for every student and sort it from the highest
 * average to the lowest. A student passes with an average of 60 or more.
 * Hint: arr.sort((a, b) => b.average - a.average) sorts descending.
 *
 * Input:   students - an array of student objects
 * Output:  an array of { name, average, passed } objects
 *
 * Example:
 *   gradeReport([
 *     { name: "Ana", grades: { math: 90, art: 70 } },
 *     { name: "Ben", grades: { math: 50, art: 40 } },
 *   ])
 *   ->  [
 *         { name: "Ana", average: 80, passed: true },
 *         { name: "Ben", average: 45, passed: false },
 *       ]
 */
function gradeReport(students) {
  // your code here
}

/**
 * searchProducts(products, query)
 *
 * Find products whose name OR any of whose tags contains the query.
 * Ignore case.
 *
 * Input:   products - an array of product objects
 *          query    - a string
 * Output:  an array of product objects
 *
 * Examples:
 *   searchProducts(products, "lap")   ->  [Laptop]           (name matches)
 *   searchProducts(products, "WORK")  ->  [Laptop, Notebook]  (tag matches)
 *   searchProducts(products, "zzz")   ->  []
 */
function searchProducts(products, query) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  fullName, isAdult, emailDomain, productLabel, isInStock,
  findById, activeUserNames, productsInCategory, cheapestProduct, totalStock, applyDiscount, averageGrade,
  orderTotal, restock, countByStatus, bestSeller, userSummary, gradeReport, searchProducts,
};
