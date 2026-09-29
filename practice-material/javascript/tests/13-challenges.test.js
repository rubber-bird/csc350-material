describe("13 - Challenges", () => {
  const ex = require("../exercises/13-challenges");

  describe("easy", () => {
    it("sumOfDigits(1234) -> 10", () => {
      expect(ex.sumOfDigits(1234)).to.equal(10);
      expect(ex.sumOfDigits(0)).to.equal(0);
      expect(ex.sumOfDigits(999)).to.equal(27);
    });
    it("longestWord('the quick brown fox') -> 'quick'", () => {
      expect(ex.longestWord("the quick brown fox")).to.equal("quick");
      expect(ex.longestWord("a bb cc")).to.equal("bb");
      expect(ex.longestWord("word")).to.equal("word");
    });
    it("isAnagram('listen', 'silent') -> true", () => {
      expect(ex.isAnagram("listen", "silent")).to.equal(true);
      expect(ex.isAnagram("Hello", "olleh")).to.equal(true);
      expect(ex.isAnagram("abc", "abd")).to.equal(false);
      expect(ex.isAnagram("abc", "abcd")).to.equal(false);
    });
  });

  describe("medium", () => {
    it("runLengthEncode('aaabbc') -> '3a2b1c'", () => {
      expect(ex.runLengthEncode("aaabbc")).to.equal("3a2b1c");
      expect(ex.runLengthEncode("abc")).to.equal("1a1b1c");
      expect(ex.runLengthEncode("")).to.equal("");
      expect(ex.runLengthEncode("zzzz")).to.equal("4z");
    });
    it("caesarCipher('xyz', 3) -> 'abc'", () => {
      expect(ex.caesarCipher("abc", 1)).to.equal("bcd");
      expect(ex.caesarCipher("xyz", 3)).to.equal("abc");
      expect(ex.caesarCipher("hi there!", 2)).to.equal("jk vjgtg!");
      expect(ex.caesarCipher("abc", 0)).to.equal("abc");
    });
    it("twoSum([2, 7, 11, 15], 9) -> [0, 1]", () => {
      expect(ex.twoSum([2, 7, 11, 15], 9)).to.deep.equal([0, 1]);
      expect(ex.twoSum([3, 2, 4], 6)).to.deep.equal([1, 2]);
    });
    it("flatten([[1, 2], [3], [4, 5]]) -> [1, 2, 3, 4, 5]", () => {
      expect(ex.flatten([[1, 2], [3], [4, 5]])).to.deep.equal([1, 2, 3, 4, 5]);
      expect(ex.flatten([[], [1], []])).to.deep.equal([1]);
      expect(ex.flatten([])).to.deep.equal([]);
    });
  });

  describe("hard", () => {
    it("binarySearch([1, 3, 5, 7, 9], 7) -> 3", () => {
      expect(ex.binarySearch([1, 3, 5, 7, 9], 7)).to.equal(3);
      expect(ex.binarySearch([1, 3, 5, 7, 9], 1)).to.equal(0);
      expect(ex.binarySearch([1, 3, 5, 7, 9], 9)).to.equal(4);
      expect(ex.binarySearch([1, 3, 5, 7, 9], 4)).to.equal(-1);
      expect(ex.binarySearch([], 4)).to.equal(-1);
    });
    it("bubbleSort([5, 1, 4, 2, 8]) -> [1, 2, 4, 5, 8] without changing the original", () => {
      const input = [5, 1, 4, 2, 8];
      expect(ex.bubbleSort(input)).to.deep.equal([1, 2, 4, 5, 8]);
      expect(input).to.deep.equal([5, 1, 4, 2, 8]);
      expect(ex.bubbleSort([3, 3, 1])).to.deep.equal([1, 3, 3]);
      expect(ex.bubbleSort([])).to.deep.equal([]);
    });
    it("romanToInt('MCMXCIV') -> 1994", () => {
      expect(ex.romanToInt("III")).to.equal(3);
      expect(ex.romanToInt("IV")).to.equal(4);
      expect(ex.romanToInt("IX")).to.equal(9);
      expect(ex.romanToInt("LVIII")).to.equal(58);
      expect(ex.romanToInt("MCMXCIV")).to.equal(1994);
    });
    it("matrixSum([[1, 2], [3, 4]]) -> 10", () => {
      expect(ex.matrixSum([[1, 2], [3, 4]])).to.equal(10);
      expect(ex.matrixSum([[5]])).to.equal(5);
      expect(ex.matrixSum([])).to.equal(0);
    });
    it("balancedBrackets('([]{})') -> true", () => {
      expect(ex.balancedBrackets("()")).to.equal(true);
      expect(ex.balancedBrackets("([]{})")).to.equal(true);
      expect(ex.balancedBrackets("(]")).to.equal(false);
      expect(ex.balancedBrackets("((")).to.equal(false);
      expect(ex.balancedBrackets("))")).to.equal(false);
      expect(ex.balancedBrackets("")).to.equal(true);
    });
  });
});
