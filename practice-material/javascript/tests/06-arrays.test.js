describe("06 - Arrays", () => {
  const ex = require("../exercises/06-arrays");

  describe("easy", () => {
    it("first([5, 6, 7]) -> 5", () => {
      expect(ex.first([5, 6, 7])).to.equal(5);
      expect(ex.first(["a"])).to.equal("a");
    });
    it("last([5, 6, 7]) -> 7", () => {
      expect(ex.last([5, 6, 7])).to.equal(7);
      expect(ex.last(["a"])).to.equal("a");
    });
    it("sum([1, 2, 3, 4]) -> 10", () => {
      expect(ex.sum([1, 2, 3, 4])).to.equal(10);
      expect(ex.sum([])).to.equal(0);
    });
    it("contains([1, 2, 3], 2) -> true", () => {
      expect(ex.contains([1, 2, 3], 2)).to.equal(true);
      expect(ex.contains(["a", "b"], "z")).to.equal(false);
    });
    it("doubleAll([1, 2, 3]) -> [2, 4, 6] and leaves the original alone", () => {
      const input = [1, 2, 3];
      expect(ex.doubleAll(input)).to.deep.equal([2, 4, 6]);
      expect(input).to.deep.equal([1, 2, 3]);
      expect(ex.doubleAll([])).to.deep.equal([]);
    });
  });

  describe("medium", () => {
    it("largest([3, 9, 2]) -> 9", () => {
      expect(ex.largest([3, 9, 2])).to.equal(9);
      expect(ex.largest([-5, -1, -10])).to.equal(-1);
    });
    it("smallest([3, 9, 2]) -> 2", () => {
      expect(ex.smallest([3, 9, 2])).to.equal(2);
      expect(ex.smallest([-5, -1, -10])).to.equal(-10);
    });
    it("onlyEvens([1, 2, 3, 4, 5, 6]) -> [2, 4, 6]", () => {
      expect(ex.onlyEvens([1, 2, 3, 4, 5, 6])).to.deep.equal([2, 4, 6]);
      expect(ex.onlyEvens([1, 3])).to.deep.equal([]);
    });
    it("average([2, 4, 6]) -> 4", () => {
      expect(ex.average([2, 4, 6])).to.equal(4);
      expect(ex.average([1, 2])).to.equal(1.5);
    });
    it("countValue([1, 2, 1, 1], 1) -> 3", () => {
      expect(ex.countValue([1, 2, 1, 1], 1)).to.equal(3);
      expect(ex.countValue(["a", "b"], "c")).to.equal(0);
    });
    it("reverseArray([1, 2, 3]) -> [3, 2, 1] without changing the original", () => {
      const input = [1, 2, 3];
      expect(ex.reverseArray(input)).to.deep.equal([3, 2, 1]);
      expect(input).to.deep.equal([1, 2, 3]);
      expect(ex.reverseArray([])).to.deep.equal([]);
    });
  });

  describe("hard", () => {
    it("removeDuplicates([1, 2, 2, 3, 1]) -> [1, 2, 3]", () => {
      expect(ex.removeDuplicates([1, 2, 2, 3, 1])).to.deep.equal([1, 2, 3]);
      expect(ex.removeDuplicates(["a", "a", "a"])).to.deep.equal(["a"]);
      expect(ex.removeDuplicates([])).to.deep.equal([]);
    });
    it("secondLargest([5, 5, 4, 1]) -> 4", () => {
      expect(ex.secondLargest([3, 9, 2])).to.equal(3);
      expect(ex.secondLargest([5, 5, 4, 1])).to.equal(4);
      expect(ex.secondLargest([1, 2])).to.equal(1);
    });
    it("chunk([1, 2, 3, 4, 5], 2) -> [[1, 2], [3, 4], [5]]", () => {
      expect(ex.chunk([1, 2, 3, 4, 5], 2)).to.deep.equal([[1, 2], [3, 4], [5]]);
      expect(ex.chunk([1, 2, 3], 3)).to.deep.equal([[1, 2, 3]]);
      expect(ex.chunk([], 2)).to.deep.equal([]);
    });
    it("zip([1, 2, 3], ['a', 'b', 'c']) -> [[1, 'a'], [2, 'b'], [3, 'c']]", () => {
      expect(ex.zip([1, 2, 3], ["a", "b", "c"])).to.deep.equal([[1, "a"], [2, "b"], [3, "c"]]);
      expect(ex.zip([], [])).to.deep.equal([]);
    });
    it("mostFrequent([1, 3, 3, 2, 1, 3]) -> 3", () => {
      expect(ex.mostFrequent([1, 3, 3, 2, 1, 3])).to.equal(3);
      expect(ex.mostFrequent(["a", "b", "b", "a"])).to.equal("a");
      expect(ex.mostFrequent([7])).to.equal(7);
    });
  });
});
