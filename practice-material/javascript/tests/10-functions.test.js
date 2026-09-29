describe("10 - Functions as values", () => {
  const ex = require("../exercises/10-functions");

  describe("easy", () => {
    it("applyTo((x) => x + 1, 5) -> 6", () => {
      expect(ex.applyTo((x) => x + 1, 5)).to.equal(6);
      expect(ex.applyTo((s) => s.toUpperCase(), "hi")).to.equal("HI");
    });
    it("applyTwice((x) => x * 2, 5) -> 20", () => {
      expect(ex.applyTwice((x) => x * 2, 5)).to.equal(20);
      expect(ex.applyTwice((s) => s + "!", "hi")).to.equal("hi!!");
    });
    it("repeat calls the function n times", () => {
      let calls = 0;
      ex.repeat(() => calls++, 3);
      expect(calls).to.equal(3);
      ex.repeat(() => calls++, 0);
      expect(calls).to.equal(3);
    });
  });

  describe("medium", () => {
    it("makeAdder(5)(10) -> 15", () => {
      const add5 = ex.makeAdder(5);
      expect(add5(10)).to.equal(15);
      expect(add5(0)).to.equal(5);
      expect(ex.makeAdder(-2)(2)).to.equal(0);
    });
    it("makeMultiplier(3)(7) -> 21", () => {
      const triple = ex.makeMultiplier(3);
      expect(triple(7)).to.equal(21);
      expect(ex.makeMultiplier(0)(99)).to.equal(0);
    });
    it("makeCounter keeps its own count", () => {
      const c = ex.makeCounter();
      expect(c()).to.equal(1);
      expect(c()).to.equal(2);
      expect(c()).to.equal(3);
      const other = ex.makeCounter();
      expect(other()).to.equal(1);
    });
    it("myMap([1, 2, 3], (x) => x * 10) -> [10, 20, 30]", () => {
      expect(ex.myMap([1, 2, 3], (x) => x * 10)).to.deep.equal([10, 20, 30]);
      expect(ex.myMap([], (x) => x)).to.deep.equal([]);
    });
    it("myFilter([1, 2, 3, 4], isEven) -> [2, 4]", () => {
      expect(ex.myFilter([1, 2, 3, 4], (x) => x % 2 === 0)).to.deep.equal([2, 4]);
      expect(ex.myFilter(["a", "bb", "ccc"], (s) => s.length > 1)).to.deep.equal(["bb", "ccc"]);
    });
  });

  describe("hard", () => {
    it("myReduce([1, 2, 3], add, 0) -> 6", () => {
      expect(ex.myReduce([1, 2, 3], (total, x) => total + x, 0)).to.equal(6);
      expect(ex.myReduce(["a", "b"], (s, x) => s + x, "")).to.equal("ab");
      expect(ex.myReduce([], (t, x) => t + x, 100)).to.equal(100);
    });
    it("compose(addOne, double)(5) -> 11", () => {
      const addOne = (x) => x + 1;
      const double = (x) => x * 2;
      expect(ex.compose(addOne, double)(5)).to.equal(11);
      expect(ex.compose(double, addOne)(5)).to.equal(12);
    });
    it("once only runs the function the first time", () => {
      let runs = 0;
      const init = ex.once(() => { runs++; return "ready"; });
      expect(init()).to.equal("ready");
      expect(init()).to.equal("ready");
      expect(runs).to.equal(1);
    });
    it("pipe runs functions left to right", () => {
      const run = ex.pipe([(x) => x + 1, (x) => x * 2, (x) => x - 3]);
      expect(run(5)).to.equal(9);
      expect(ex.pipe([])(42)).to.equal(42);
    });
    it("memoize remembers previous answers", () => {
      let runs = 0;
      const fastSquare = ex.memoize((n) => { runs++; return n * n; });
      expect(fastSquare(4)).to.equal(16);
      expect(runs).to.equal(1);
      expect(fastSquare(4)).to.equal(16);
      expect(runs).to.equal(1);
      expect(fastSquare(5)).to.equal(25);
      expect(runs).to.equal(2);
    });
  });
});
