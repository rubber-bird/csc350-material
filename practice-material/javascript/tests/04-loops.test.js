describe("04 - Loops", () => {
  const ex = require("../exercises/04-loops");

  describe("easy", () => {
    it("sumTo(4) -> 10", () => {
      expect(ex.sumTo(4)).to.equal(10);
      expect(ex.sumTo(1)).to.equal(1);
      expect(ex.sumTo(100)).to.equal(5050);
    });
    it("repeatChar('x', 3) -> 'xxx'", () => {
      expect(ex.repeatChar("x", 3)).to.equal("xxx");
      expect(ex.repeatChar("ab", 2)).to.equal("abab");
      expect(ex.repeatChar("z", 0)).to.equal("");
    });
    it("countdown(5) -> [5, 4, 3, 2, 1]", () => {
      expect(ex.countdown(5)).to.deep.equal([5, 4, 3, 2, 1]);
      expect(ex.countdown(1)).to.deep.equal([1]);
    });
  });

  describe("medium", () => {
    it("factorial(5) -> 120", () => {
      expect(ex.factorial(5)).to.equal(120);
      expect(ex.factorial(1)).to.equal(1);
      expect(ex.factorial(0)).to.equal(1);
    });
    it("countOccurrences('banana', 'a') -> 3", () => {
      expect(ex.countOccurrences("banana", "a")).to.equal(3);
      expect(ex.countOccurrences("hello", "z")).to.equal(0);
    });
    it("range(2, 5) -> [2, 3, 4, 5]", () => {
      expect(ex.range(2, 5)).to.deep.equal([2, 3, 4, 5]);
      expect(ex.range(1, 1)).to.deep.equal([1]);
    });
    it("multiplicationTable(2) -> [2, 4, ..., 20]", () => {
      expect(ex.multiplicationTable(2)).to.deep.equal([2, 4, 6, 8, 10, 12, 14, 16, 18, 20]);
      expect(ex.multiplicationTable(0)).to.deep.equal([0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
    });
  });

  describe("hard", () => {
    it("countDigits(4321) -> 4", () => {
      expect(ex.countDigits(4321)).to.equal(4);
      expect(ex.countDigits(7)).to.equal(1);
      expect(ex.countDigits(1000000)).to.equal(7);
    });
    it("reverseNumber(1234) -> 4321", () => {
      expect(ex.reverseNumber(1234)).to.equal(4321);
      expect(ex.reverseNumber(1200)).to.equal(21);
      expect(ex.reverseNumber(5)).to.equal(5);
    });
    it("isPrime(13) -> true", () => {
      expect(ex.isPrime(2)).to.equal(true);
      expect(ex.isPrime(9)).to.equal(false);
      expect(ex.isPrime(13)).to.equal(true);
      expect(ex.isPrime(1)).to.equal(false);
      expect(ex.isPrime(0)).to.equal(false);
    });
    it("fibonacci(10) -> 55", () => {
      expect(ex.fibonacci(0)).to.equal(0);
      expect(ex.fibonacci(1)).to.equal(1);
      expect(ex.fibonacci(6)).to.equal(8);
      expect(ex.fibonacci(10)).to.equal(55);
    });
    it("starTriangle(3) -> '*\\n**\\n***'", () => {
      expect(ex.starTriangle(1)).to.equal("*");
      expect(ex.starTriangle(3)).to.equal("*\n**\n***");
    });
  });
});
