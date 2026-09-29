// Reference solutions - 09 Real-world data
const round2 = (n) => Math.round(n * 100) / 100;

function fullName(user) { return user.firstName + " " + user.lastName; }
function isAdult(user) { return user.age >= 18; }
function emailDomain(user) { return user.email.split("@")[1]; }
function productLabel(product) { return product.name + " - $" + product.price.toFixed(2); }
function isInStock(product) { return product.stock > 0; }

function findById(items, id) { return items.find((item) => item.id === id); }
function activeUserNames(users) { return users.filter((u) => u.isActive).map(fullName); }
function productsInCategory(products, category) { return products.filter((p) => p.category === category); }
function cheapestProduct(products) {
  let best = products[0];
  for (const p of products) if (p.price < best.price) best = p;
  return best;
}
function totalStock(products) {
  let total = 0;
  for (const p of products) total += p.stock;
  return total;
}
function applyDiscount(product, percent) {
  return { ...product, price: round2(product.price * (1 - percent / 100)) };
}
function averageGrade(student) {
  const values = Object.values(student.grades);
  let total = 0;
  for (const v of values) total += v;
  return total / values.length;
}

function orderTotal(order, products) {
  let total = 0;
  for (const item of order.items) {
    const product = findById(products, item.productId);
    total += product.price * item.quantity;
  }
  return round2(total);
}
function restock(products, id, amount) {
  return products.map((p) => (p.id === id ? { ...p, stock: p.stock + amount } : p));
}
function countByStatus(orders) {
  const counts = {};
  for (const order of orders) counts[order.status] = (counts[order.status] || 0) + 1;
  return counts;
}
function bestSeller(orders, products) {
  const sold = {};
  for (const order of orders) {
    for (const item of order.items) sold[item.productId] = (sold[item.productId] || 0) + item.quantity;
  }
  let bestId = null;
  for (const id in sold) if (bestId === null || sold[id] > sold[bestId]) bestId = id;
  return findById(products, Number(bestId)).name;
}
function userSummary(user, orders, products) {
  const mine = orders.filter((o) => o.userId === user.id);
  let spent = 0;
  for (const order of mine) spent += orderTotal(order, products);
  return { name: fullName(user), orderCount: mine.length, totalSpent: round2(spent) };
}
function gradeReport(students) {
  return students
    .map((s) => {
      const average = averageGrade(s);
      return { name: s.name, average: average, passed: average >= 60 };
    })
    .sort((a, b) => b.average - a.average);
}
function searchProducts(products, query) {
  const q = query.toLowerCase();
  return products.filter(
    (p) => p.name.toLowerCase().includes(q) || p.tags.some((t) => t.toLowerCase().includes(q))
  );
}

module.exports = {
  fullName, isAdult, emailDomain, productLabel, isInStock,
  findById, activeUserNames, productsInCategory, cheapestProduct, totalStock, applyDiscount, averageGrade,
  orderTotal, restock, countByStatus, bestSeller, userSummary, gradeReport, searchProducts,
};
