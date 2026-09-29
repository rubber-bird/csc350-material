describe("07 - Objects", () => {
  const ex = require("../exercises/07-objects");

  const sam = { name: "Sam", age: 30 };
  const people = [
    { name: "Ana", age: 25 },
    { name: "Ben", age: 41 },
    { name: "Cy", age: 33 },
  ];

  describe("easy", () => {
    it("makePerson('Sam', 30) -> { name: 'Sam', age: 30 }", () => {
      expect(ex.makePerson("Sam", 30)).to.deep.equal({ name: "Sam", age: 30 });
    });
    it("getName(sam) -> 'Sam'", () => {
      expect(ex.getName(sam)).to.equal("Sam");
    });
    it("hasKey(sam, 'name') -> true", () => {
      expect(ex.hasKey(sam, "name")).to.equal(true);
      expect(ex.hasKey(sam, "email")).to.equal(false);
    });
    it("countKeys(sam) -> 2", () => {
      expect(ex.countKeys(sam)).to.equal(2);
      expect(ex.countKeys({})).to.equal(0);
    });
  });

  describe("medium", () => {
    it("describePerson(sam) -> 'Sam is 30 years old'", () => {
      expect(ex.describePerson(sam)).to.equal("Sam is 30 years old");
    });
    it("keys(sam) -> ['name', 'age']", () => {
      expect(ex.keys(sam)).to.deep.equal(["name", "age"]);
      expect(ex.keys({})).to.deep.equal([]);
    });
    it("getOrDefault falls back when the key is missing", () => {
      expect(ex.getOrDefault({ color: "red" }, "color", "none")).to.equal("red");
      expect(ex.getOrDefault({ color: "red" }, "size", "none")).to.equal("none");
    });
    it("withBirthday returns a new object with age + 1", () => {
      const original = { name: "Sam", age: 30 };
      expect(ex.withBirthday(original)).to.deep.equal({ name: "Sam", age: 31 });
      expect(original.age).to.equal(30);
    });
    it("names(people) -> ['Ana', 'Ben', 'Cy']", () => {
      expect(ex.names(people)).to.deep.equal(["Ana", "Ben", "Cy"]);
      expect(ex.names([])).to.deep.equal([]);
    });
    it("oldest(people) -> Ben", () => {
      expect(ex.oldest(people)).to.deep.equal({ name: "Ben", age: 41 });
    });
  });

  describe("hard", () => {
    it("totalPrice adds price * quantity", () => {
      expect(ex.totalPrice([{ price: 2, quantity: 3 }, { price: 10, quantity: 1 }])).to.equal(16);
      expect(ex.totalPrice([])).to.equal(0);
    });
    it("countWords('the cat and the dog') -> { the: 2, cat: 1, and: 1, dog: 1 }", () => {
      expect(ex.countWords("the cat and the dog")).to.deep.equal({ the: 2, cat: 1, and: 1, dog: 1 });
      expect(ex.countWords("a a a")).to.deep.equal({ a: 3 });
    });
    it("invert({ a: 'x', b: 'y' }) -> { x: 'a', y: 'b' }", () => {
      expect(ex.invert({ a: "x", b: "y" })).to.deep.equal({ x: "a", y: "b" });
      expect(ex.invert({})).to.deep.equal({});
    });
    it("groupBy groups items by a property", () => {
      const items = [
        { name: "apple", type: "fruit" },
        { name: "carrot", type: "veg" },
        { name: "pear", type: "fruit" },
      ];
      expect(ex.groupBy(items, "type")).to.deep.equal({
        fruit: [{ name: "apple", type: "fruit" }, { name: "pear", type: "fruit" }],
        veg: [{ name: "carrot", type: "veg" }],
      });
      expect(ex.groupBy([], "type")).to.deep.equal({});
    });
    it("deepGet follows a dotted path", () => {
      expect(ex.deepGet({ user: { address: { city: "Kyiv" } } }, "user.address.city")).to.equal("Kyiv");
      expect(ex.deepGet({ user: { name: "Sam" } }, "user.name")).to.equal("Sam");
      expect(ex.deepGet({ user: { name: "Sam" } }, "user.email")).to.equal(undefined);
      expect(ex.deepGet({}, "a.b.c")).to.equal(undefined);
    });
  });
});
