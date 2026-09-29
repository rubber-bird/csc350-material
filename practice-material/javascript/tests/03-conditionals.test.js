describe("03 - Conditionals", () => {
  const ex = require("../exercises/03-conditionals");

  describe("easy", () => {
    it("isEven(4) -> true", () => {
      expect(ex.isEven(4)).to.equal(true);
      expect(ex.isEven(7)).to.equal(false);
      expect(ex.isEven(0)).to.equal(true);
    });
    it("max(3, 9) -> 9", () => {
      expect(ex.max(3, 9)).to.equal(9);
      expect(ex.max(10, 2)).to.equal(10);
      expect(ex.max(5, 5)).to.equal(5);
    });
    it("canVote(18) -> true", () => {
      expect(ex.canVote(18)).to.equal(true);
      expect(ex.canVote(30)).to.equal(true);
      expect(ex.canVote(17)).to.equal(false);
    });
    it("sign(-3) -> 'negative'", () => {
      expect(ex.sign(12)).to.equal("positive");
      expect(ex.sign(-3)).to.equal("negative");
      expect(ex.sign(0)).to.equal("zero");
    });
  });

  describe("medium", () => {
    it("maxOfThree(1, 5, 3) -> 5", () => {
      expect(ex.maxOfThree(1, 5, 3)).to.equal(5);
      expect(ex.maxOfThree(9, 2, 4)).to.equal(9);
      expect(ex.maxOfThree(1, 2, 8)).to.equal(8);
      expect(ex.maxOfThree(-1, -5, -2)).to.equal(-1);
    });
    it("letterGrade(85) -> 'B'", () => {
      expect(ex.letterGrade(95)).to.equal("A");
      expect(ex.letterGrade(90)).to.equal("A");
      expect(ex.letterGrade(85)).to.equal("B");
      expect(ex.letterGrade(72)).to.equal("C");
      expect(ex.letterGrade(60)).to.equal("D");
      expect(ex.letterGrade(59)).to.equal("F");
      expect(ex.letterGrade(0)).to.equal("F");
    });
    it("fizzBuzz(15) -> 'FizzBuzz'", () => {
      expect(ex.fizzBuzz(3)).to.equal("Fizz");
      expect(ex.fizzBuzz(5)).to.equal("Buzz");
      expect(ex.fizzBuzz(15)).to.equal("FizzBuzz");
      expect(ex.fizzBuzz(7)).to.equal(7);
    });
    it("isLeapYear(2000) -> true, isLeapYear(1900) -> false", () => {
      expect(ex.isLeapYear(2024)).to.equal(true);
      expect(ex.isLeapYear(2023)).to.equal(false);
      expect(ex.isLeapYear(1900)).to.equal(false);
      expect(ex.isLeapYear(2000)).to.equal(true);
    });
  });

  describe("hard", () => {
    it("dayType('saturday') -> 'weekend'", () => {
      expect(ex.dayType("Monday")).to.equal("weekday");
      expect(ex.dayType("friday")).to.equal("weekday");
      expect(ex.dayType("saturday")).to.equal("weekend");
      expect(ex.dayType("SUNDAY")).to.equal("weekend");
      expect(ex.dayType("Funday")).to.equal("invalid");
    });
    it("triangleType classifies triangles", () => {
      expect(ex.triangleType(3, 3, 3)).to.equal("equilateral");
      expect(ex.triangleType(3, 4, 3)).to.equal("isosceles");
      expect(ex.triangleType(4, 3, 3)).to.equal("isosceles");
      expect(ex.triangleType(3, 4, 5)).to.equal("scalene");
      expect(ex.triangleType(1, 2, 3)).to.equal("invalid");
      expect(ex.triangleType(0, 4, 5)).to.equal("invalid");
    });
    it("shippingCost(3, true) -> 20", () => {
      expect(ex.shippingCost(0.5, false)).to.equal(5);
      expect(ex.shippingCost(1, false)).to.equal(5);
      expect(ex.shippingCost(3, false)).to.equal(10);
      expect(ex.shippingCost(5, false)).to.equal(10);
      expect(ex.shippingCost(3, true)).to.equal(20);
      expect(ex.shippingCost(12, true)).to.equal(40);
    });
  });
});
