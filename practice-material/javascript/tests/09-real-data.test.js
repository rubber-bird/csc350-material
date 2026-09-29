describe("09 - Real-world data", () => {
  const ex = require("../exercises/09-real-data");

  // Fresh copies for every test, so one test can't break another.
  const makeUsers = () => [
    { id: 1, firstName: "Ana", lastName: "Silva", email: "ana@example.com", age: 29, isActive: true },
    { id: 2, firstName: "Ben", lastName: "Wu", email: "ben@shop.org", age: 15, isActive: false },
    { id: 3, firstName: "Cy", lastName: "Ortiz", email: "cy@example.com", age: 41, isActive: true },
  ];
  const makeProducts = () => [
    { id: 101, name: "Laptop", price: 999.99, category: "electronics", stock: 5, tags: ["computer", "work"] },
    { id: 102, name: "Notebook", price: 4.5, category: "stationery", stock: 0, tags: ["paper", "work", "school"] },
    { id: 103, name: "Mouse", price: 25, category: "electronics", stock: 12, tags: ["computer"] },
    { id: 104, name: "Pen", price: 2, category: "stationery", stock: 100, tags: ["school"] },
  ];
  const makeOrders = () => [
    { id: 5001, userId: 1, status: "shipped", items: [{ productId: 101, quantity: 1 }, { productId: 103, quantity: 2 }] },
    { id: 5002, userId: 3, status: "pending", items: [{ productId: 103, quantity: 3 }] },
    { id: 5003, userId: 1, status: "shipped", items: [{ productId: 103, quantity: 3 }] },
  ];
  const makeStudents = () => [
    { name: "Ana", grades: { math: 90, science: 80, art: 70 } },
    { name: "Ben", grades: { math: 50, science: 40 } },
    { name: "Cy", grades: { math: 100, science: 95, art: 99 } },
  ];

  describe("easy", () => {
    it("fullName -> 'Ana Silva'", () => {
      expect(ex.fullName(makeUsers()[0])).to.equal("Ana Silva");
      expect(ex.fullName(makeUsers()[1])).to.equal("Ben Wu");
    });
    it("isAdult checks age 18+", () => {
      expect(ex.isAdult(makeUsers()[0])).to.equal(true);
      expect(ex.isAdult(makeUsers()[1])).to.equal(false);
      expect(ex.isAdult({ age: 18 })).to.equal(true);
    });
    it("emailDomain -> 'example.com'", () => {
      expect(ex.emailDomain(makeUsers()[0])).to.equal("example.com");
      expect(ex.emailDomain(makeUsers()[1])).to.equal("shop.org");
    });
    it("productLabel -> 'Laptop - $999.99'", () => {
      expect(ex.productLabel(makeProducts()[0])).to.equal("Laptop - $999.99");
      expect(ex.productLabel(makeProducts()[3])).to.equal("Pen - $2.00");
      expect(ex.productLabel(makeProducts()[1])).to.equal("Notebook - $4.50");
    });
    it("isInStock checks stock > 0", () => {
      expect(ex.isInStock(makeProducts()[0])).to.equal(true);
      expect(ex.isInStock(makeProducts()[1])).to.equal(false);
    });
  });

  describe("medium", () => {
    it("findById(products, 103) -> the Mouse", () => {
      expect(ex.findById(makeProducts(), 103).name).to.equal("Mouse");
      expect(ex.findById(makeUsers(), 2).firstName).to.equal("Ben");
      expect(ex.findById(makeProducts(), 999)).to.equal(undefined);
    });
    it("activeUserNames -> ['Ana Silva', 'Cy Ortiz']", () => {
      expect(ex.activeUserNames(makeUsers())).to.deep.equal(["Ana Silva", "Cy Ortiz"]);
      expect(ex.activeUserNames([])).to.deep.equal([]);
    });
    it("productsInCategory(products, 'electronics') -> Laptop and Mouse", () => {
      const found = ex.productsInCategory(makeProducts(), "electronics");
      expect(found.map((p) => p.name)).to.deep.equal(["Laptop", "Mouse"]);
      expect(ex.productsInCategory(makeProducts(), "toys")).to.deep.equal([]);
    });
    it("cheapestProduct -> Pen", () => {
      expect(ex.cheapestProduct(makeProducts()).name).to.equal("Pen");
      expect(ex.cheapestProduct([{ name: "Only", price: 9 }]).name).to.equal("Only");
    });
    it("totalStock -> 117", () => {
      expect(ex.totalStock(makeProducts())).to.equal(117);
      expect(ex.totalStock([])).to.equal(0);
    });
    it("applyDiscount returns a new product and leaves the original alone", () => {
      const laptop = { id: 1, name: "Laptop", price: 1000, stock: 1 };
      const cheaper = ex.applyDiscount(laptop, 10);
      expect(cheaper.price).to.equal(900);
      expect(cheaper.name).to.equal("Laptop");
      expect(laptop.price).to.equal(1000);
      expect(ex.applyDiscount({ name: "Pen", price: 2 }, 25).price).to.equal(1.5);
      expect(ex.applyDiscount({ name: "Odd", price: 9.99 }, 33).price).to.equal(6.69);
    });
    it("averageGrade -> 80", () => {
      expect(ex.averageGrade(makeStudents()[0])).to.equal(80);
      expect(ex.averageGrade(makeStudents()[1])).to.equal(45);
    });
  });

  describe("hard", () => {
    it("orderTotal -> 1049.99", () => {
      const products = makeProducts();
      const orders = makeOrders();
      expect(ex.orderTotal(orders[0], products)).to.equal(1049.99);
      expect(ex.orderTotal(orders[1], products)).to.equal(75);
      expect(ex.orderTotal({ id: 1, userId: 1, status: "pending", items: [] }, products)).to.equal(0);
    });
    it("restock returns a new array and does not touch the original", () => {
      const products = makeProducts();
      const updated = ex.restock(products, 102, 10);
      expect(updated.find((p) => p.id === 102).stock).to.equal(10);
      expect(updated.find((p) => p.id === 101).stock).to.equal(5);
      expect(updated.length).to.equal(4);
      expect(products.find((p) => p.id === 102).stock).to.equal(0);
      expect(updated).to.not.equal(products);
    });
    it("countByStatus -> { shipped: 2, pending: 1 }", () => {
      expect(ex.countByStatus(makeOrders())).to.deep.equal({ shipped: 2, pending: 1 });
      expect(ex.countByStatus([])).to.deep.equal({});
    });
    it("bestSeller -> 'Mouse'", () => {
      expect(ex.bestSeller(makeOrders(), makeProducts())).to.equal("Mouse");
      const single = [{ id: 1, userId: 1, status: "shipped", items: [{ productId: 104, quantity: 1 }] }];
      expect(ex.bestSeller(single, makeProducts())).to.equal("Pen");
    });
    it("userSummary -> { name, orderCount, totalSpent }", () => {
      const users = makeUsers();
      expect(ex.userSummary(users[0], makeOrders(), makeProducts())).to.deep.equal({
        name: "Ana Silva", orderCount: 2, totalSpent: 1124.99,
      });
      expect(ex.userSummary(users[1], makeOrders(), makeProducts())).to.deep.equal({
        name: "Ben Wu", orderCount: 0, totalSpent: 0,
      });
    });
    it("gradeReport sorts by average, highest first", () => {
      expect(ex.gradeReport(makeStudents())).to.deep.equal([
        { name: "Cy", average: 98, passed: true },
        { name: "Ana", average: 80, passed: true },
        { name: "Ben", average: 45, passed: false },
      ]);
      expect(ex.gradeReport([])).to.deep.equal([]);
    });
    it("searchProducts matches name or tags, ignoring case", () => {
      const names = (arr) => arr.map((p) => p.name);
      expect(names(ex.searchProducts(makeProducts(), "lap"))).to.deep.equal(["Laptop"]);
      expect(names(ex.searchProducts(makeProducts(), "WORK"))).to.deep.equal(["Laptop", "Notebook"]);
      expect(names(ex.searchProducts(makeProducts(), "school"))).to.deep.equal(["Notebook", "Pen"]);
      expect(ex.searchProducts(makeProducts(), "zzz")).to.deep.equal([]);
    });
  });
});
