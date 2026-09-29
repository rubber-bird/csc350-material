describe("02 - Strings", () => {
  const ex = require("../exercises/02-strings");

  describe("easy", () => {
    it("greet('Ana') -> 'Hello, Ana!'", () => {
      expect(ex.greet("Ana")).to.equal("Hello, Ana!");
      expect(ex.greet("Bob")).to.equal("Hello, Bob!");
    });
    it("shout('hello') -> 'HELLO'", () => {
      expect(ex.shout("hello")).to.equal("HELLO");
      expect(ex.shout("Mixed Case")).to.equal("MIXED CASE");
    });
    it("whisper('HELLO') -> 'hello'", () => {
      expect(ex.whisper("HELLO")).to.equal("hello");
      expect(ex.whisper("QuIeT")).to.equal("quiet");
    });
    it("countChars('banana') -> 6", () => {
      expect(ex.countChars("banana")).to.equal(6);
      expect(ex.countChars("")).to.equal(0);
    });
    it("firstChar('hello') -> 'h'", () => {
      expect(ex.firstChar("hello")).to.equal("h");
    });
    it("lastChar('hello') -> 'o'", () => {
      expect(ex.lastChar("hello")).to.equal("o");
      expect(ex.lastChar("x")).to.equal("x");
    });
  });

  describe("medium", () => {
    it("joinWithSpace('good', 'morning') -> 'good morning'", () => {
      expect(ex.joinWithSpace("good", "morning")).to.equal("good morning");
    });
    it("hasLetter('cat', 'a') -> true", () => {
      expect(ex.hasLetter("cat", "a")).to.equal(true);
      expect(ex.hasLetter("dog", "a")).to.equal(false);
      expect(ex.hasLetter("Apple", "a")).to.equal(false);
    });
    it("capitalize('wORLD') -> 'World'", () => {
      expect(ex.capitalize("hello")).to.equal("Hello");
      expect(ex.capitalize("wORLD")).to.equal("World");
    });
    it("initials('Ada Lovelace') -> 'AL'", () => {
      expect(ex.initials("Ada Lovelace")).to.equal("AL");
      expect(ex.initials("grace brewster hopper")).to.equal("GBH");
    });
    it("removeSpaces('a b c') -> 'abc'", () => {
      expect(ex.removeSpaces("a b c")).to.equal("abc");
      expect(ex.removeSpaces("  hi  there  ")).to.equal("hithere");
    });
  });

  describe("hard", () => {
    it("reverse('abc') -> 'cba'", () => {
      expect(ex.reverse("abc")).to.equal("cba");
      expect(ex.reverse("racecar")).to.equal("racecar");
      expect(ex.reverse("")).to.equal("");
    });
    it("countVowels('hello') -> 2", () => {
      expect(ex.countVowels("hello")).to.equal(2);
      expect(ex.countVowels("rhythm")).to.equal(0);
      expect(ex.countVowels("AEIOU aei")).to.equal(8);
    });
    it("isPalindrome ignores case and spaces", () => {
      expect(ex.isPalindrome("racecar")).to.equal(true);
      expect(ex.isPalindrome("hello")).to.equal(false);
      expect(ex.isPalindrome("Never odd or even")).to.equal(true);
    });
    it("titleCase('hello world') -> 'Hello World'", () => {
      expect(ex.titleCase("hello world")).to.equal("Hello World");
      expect(ex.titleCase("tHE quick BROWN fox")).to.equal("The Quick Brown Fox");
    });
    it("truncate('Hello world', 5) -> 'Hello...'", () => {
      expect(ex.truncate("Hello world", 5)).to.equal("Hello...");
      expect(ex.truncate("Hi", 5)).to.equal("Hi");
      expect(ex.truncate("Hello", 5)).to.equal("Hello");
    });
  });
});
