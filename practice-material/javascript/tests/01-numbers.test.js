describe("01 - Numbers and Arithmetic", () => {
  const ex = require("../exercises/01-numbers");

  describe("easy", () => {
    it("fortyTwo() -> 42", () => {
      expect(ex.fortyTwo()).to.equal(42);
    });
    it("add(2, 3) -> 5", () => {
      expect(ex.add(2, 3)).to.equal(5);
      expect(ex.add(-1, 1)).to.equal(0);
    });
    it("subtract(10, 4) -> 6", () => {
      expect(ex.subtract(10, 4)).to.equal(6);
      expect(ex.subtract(0, 5)).to.equal(-5);
    });
    it("multiply(3, 4) -> 12", () => {
      expect(ex.multiply(3, 4)).to.equal(12);
      expect(ex.multiply(7, 0)).to.equal(0);
    });
    it("divide(20, 5) -> 4", () => {
      expect(ex.divide(20, 5)).to.equal(4);
      expect(ex.divide(1, 2)).to.equal(0.5);
    });
    it("remainder(10, 3) -> 1", () => {
      expect(ex.remainder(10, 3)).to.equal(1);
      expect(ex.remainder(8, 4)).to.equal(0);
    });
  });

  describe("medium", () => {
    it("square(5) -> 25", () => {
      expect(ex.square(5)).to.equal(25);
      expect(ex.square(-3)).to.equal(9);
    });
    it("averageOfThree(1, 2, 3) -> 2", () => {
      expect(ex.averageOfThree(1, 2, 3)).to.equal(2);
      expect(ex.averageOfThree(10, 20, 60)).to.equal(30);
    });
    it("isDivisible(10, 5) -> true", () => {
      expect(ex.isDivisible(10, 5)).to.equal(true);
      expect(ex.isDivisible(10, 3)).to.equal(false);
    });
    it("celsiusToFahrenheit(100) -> 212", () => {
      expect(ex.celsiusToFahrenheit(0)).to.equal(32);
      expect(ex.celsiusToFahrenheit(100)).to.equal(212);
      expect(ex.celsiusToFahrenheit(-40)).to.equal(-40);
    });
    it("absoluteDifference(3, 10) -> 7", () => {
      expect(ex.absoluteDifference(10, 3)).to.equal(7);
      expect(ex.absoluteDifference(3, 10)).to.equal(7);
      expect(ex.absoluteDifference(5, 5)).to.equal(0);
    });
  });

  describe("hard", () => {
    it("clamp keeps a number inside a range", () => {
      expect(ex.clamp(5, 1, 10)).to.equal(5);
      expect(ex.clamp(-3, 1, 10)).to.equal(1);
      expect(ex.clamp(50, 1, 10)).to.equal(10);
      expect(ex.clamp(10, 1, 10)).to.equal(10);
    });
    it("percentOf(1, 3) -> 33.3", () => {
      expect(ex.percentOf(50, 200)).to.equal(25);
      expect(ex.percentOf(1, 3)).to.equal(33.3);
      expect(ex.percentOf(2, 3)).to.equal(66.7);
    });
    it("hypotenuse(3, 4) -> 5", () => {
      expect(ex.hypotenuse(3, 4)).to.equal(5);
      expect(ex.hypotenuse(5, 12)).to.equal(13);
    });
    it("secondsToClock(90) -> '1:30'", () => {
      expect(ex.secondsToClock(90)).to.equal("1:30");
      expect(ex.secondsToClock(5)).to.equal("0:05");
      expect(ex.secondsToClock(600)).to.equal("10:00");
      expect(ex.secondsToClock(0)).to.equal("0:00");
    });
  });
});
