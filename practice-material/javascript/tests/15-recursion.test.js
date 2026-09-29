describe("15 - Recursion", () => {
  const ex = require("../exercises/15-recursion");

  // ---- helpers that inspect the student's source code ----------------
  // The body of a function with comments and the signature stripped.
  const body = (fn) => {
    let src = fn.toString();
    src = src.slice(src.indexOf("{") + 1);
    return src.replace(/\/\*[\s\S]*?\*\//g, "").replace(/\/\/.*$/gm, "");
  };
  const LOOPS = [/\bfor\s*\(/, /\bwhile\s*\(/, /\bdo\s*\{/, /\.forEach\s*\(/, /\.map\s*\(/, /\.filter\s*\(/, /\.reduce\s*\(/, /\.some\s*\(/, /\.every\s*\(/];
  // Asserts: calls itself, and uses no loops. `banned` is an optional list
  // of extra strings that must not appear (like "%" or "Math").
  // Object and DOM prompts may loop over the keys of ONE level (allowLoops),
  // but must still recurse into nested values.
  const recursive = (name, banned = [], allowLoops = false) => {
    const src = body(ex[name]);
    const callsItself = new RegExp("\\b" + name + "\\s*\\(").test(src);
    expect(callsItself ? "calls itself" : name + " must call itself").to.equal("calls itself");
    for (const re of allowLoops ? [] : LOOPS) {
      expect(re.test(src) ? name + " must not use a loop: " + re.source : "no loops").to.equal("no loops");
    }
    for (const b of banned) {
      expect(src.includes(b) ? name + " must not use " + b : "ok").to.equal("ok");
    }
  };
  const isNaNValue = (v) => typeof v === "number" && Number.isNaN(v);

  describe("easy", () => {
    it("1. rFactorial(5) -> 120", () => {
      expect(ex.rFactorial(5)).to.equal(120);
      expect(ex.rFactorial(1)).to.equal(1);
      expect(ex.rFactorial(0)).to.equal(1);
      expect(ex.rFactorial(-3)).to.equal(null);
      recursive("rFactorial");
    });
    it("2. rSum([1, 2, 3, 4, 5, 6]) -> 21", () => {
      const input = [1, 2, 3, 4, 5, 6];
      expect(ex.rSum(input)).to.equal(21);
      expect(input).to.deep.equal([1, 2, 3, 4, 5, 6]);
      expect(ex.rSum([])).to.equal(0);
      expect(ex.rSum([-2, -5])).to.equal(-7);
      expect(ex.rSum([9])).to.equal(9);
      recursive("rSum");
    });
    it("3. arraySum([1, [2, 3], [[4]], 5]) -> 15", () => {
      const input = [1, [2, 3], [[4]], 5];
      expect(ex.arraySum(input)).to.equal(15);
      expect(input).to.deep.equal([1, [2, 3], [[4]], 5]);
      expect(ex.arraySum([])).to.equal(0);
      expect(ex.arraySum([[-1], [-2, [-3]]])).to.equal(-6);
      recursive("arraySum", [".flat("]);
    });
    it("4. rIsEven without %", () => {
      expect(ex.rIsEven(4)).to.equal(true);
      expect(ex.rIsEven(7)).to.equal(false);
      expect(ex.rIsEven(0)).to.equal(true);
      expect(ex.rIsEven(-6)).to.equal(true);
      expect(ex.rIsEven(-3)).to.equal(false);
      recursive("rIsEven", ["%"]);
    });
    it("5. sumBelow(10) -> 45", () => {
      expect(ex.sumBelow(10)).to.equal(45);
      expect(ex.sumBelow(7)).to.equal(21);
      expect(ex.sumBelow(1)).to.equal(0);
      expect(ex.sumBelow(0)).to.equal(0);
      expect(ex.sumBelow(-6)).to.equal(-15);
      recursive("sumBelow");
    });
    it("6. rRange(2, 9) -> [3, 4, 5, 6, 7, 8]", () => {
      expect(ex.rRange(2, 9)).to.deep.equal([3, 4, 5, 6, 7, 8]);
      expect(ex.rRange(7, 2)).to.deep.equal([6, 5, 4, 3]);
      expect(ex.rRange(5, 5)).to.deep.equal([]);
      expect(ex.rRange(2, 3)).to.deep.equal([]);
      expect(ex.rRange(-3, 2)).to.deep.equal([-2, -1, 0, 1]);
      expect(ex.rRange(3, -3)).to.deep.equal([2, 1, 0, -1, -2]);
      recursive("rRange");
    });
    it("7. exponent(4, 3) -> 64", () => {
      expect(ex.exponent(4, 3)).to.equal(64);
      expect(ex.exponent(3, 4)).to.equal(81);
      expect(ex.exponent(8, 0)).to.equal(1);
      expect(ex.exponent(9, 1)).to.equal(9);
      expect(ex.exponent(-3, 4)).to.equal(81);
      expect(ex.exponent(-12, 5)).to.equal(-248832);
      expect(ex.exponent(4, -2)).to.equal(0.0625);
      expect(ex.exponent(2, -5)).to.equal(0.03125);
      recursive("exponent", ["Math", "**"]);
    });
    it("8. powerOfTwo(16) -> true", () => {
      expect(ex.powerOfTwo(1)).to.equal(true);
      expect(ex.powerOfTwo(16)).to.equal(true);
      expect(ex.powerOfTwo(10)).to.equal(false);
      expect(ex.powerOfTwo(0)).to.equal(false);
      recursive("powerOfTwo");
    });
    it("9. rReverse('abc') -> 'cba'", () => {
      expect(ex.rReverse("abc")).to.equal("cba");
      expect(ex.rReverse("")).to.equal("");
      expect(ex.rReverse("racecar")).to.equal("racecar");
      recursive("rReverse", [".reverse("]);
    });
    it("10. palindrome ignores case and spaces", () => {
      expect(ex.palindrome("racecar")).to.equal(true);
      expect(ex.palindrome("Never odd or even")).to.equal(true);
      expect(ex.palindrome("hello")).to.equal(false);
      expect(ex.palindrome("a")).to.equal(true);
      recursive("palindrome", [".reverse("]);
    });
  });

  describe("medium", () => {
    it("11. modulo without %, *, / or Math", () => {
      expect(ex.modulo(5, 2)).to.equal(1);
      expect(ex.modulo(17, 5)).to.equal(2);
      expect(ex.modulo(22, 6)).to.equal(4);
      expect(ex.modulo(78, 453)).to.equal(78);
      expect(ex.modulo(0, 32)).to.equal(0);
      expect(ex.modulo(-79, 82)).to.equal(-79);
      expect(ex.modulo(-275, -274)).to.equal(-1);
      expect(Math.abs(ex.modulo(-4, 2))).to.equal(0);
      expect(isNaNValue(ex.modulo(0, 0))).to.equal(true);
      recursive("modulo", ["%", "*", "/", "Math"]);
    });
    it("12. rMultiply without *, /, % or Math", () => {
      expect(ex.rMultiply(1, 2)).to.equal(2);
      expect(ex.rMultiply(17, 5)).to.equal(85);
      expect(ex.rMultiply(5, 17)).to.equal(85);
      expect(ex.rMultiply(0, 32)).to.equal(0);
      expect(ex.rMultiply(-2, -2)).to.equal(4);
      expect(ex.rMultiply(-8, 3)).to.equal(-24);
      expect(ex.rMultiply(8, -3)).to.equal(-24);
      recursive("rMultiply", ["*", "/", "%", "Math"]);
    });
    it("13. rDivide without /, *, % or Math", () => {
      expect(ex.rDivide(2, 1)).to.equal(2);
      expect(ex.rDivide(17, 5)).to.equal(3);
      expect(ex.rDivide(78, 453)).to.equal(0);
      expect(Math.abs(ex.rDivide(-79, 82))).to.equal(0);
      expect(ex.rDivide(-275, -582)).to.equal(0);
      expect(ex.rDivide(-17, 5)).to.equal(-3);
      expect(ex.rDivide(0, 32)).to.equal(0);
      expect(isNaNValue(ex.rDivide(0, 0))).to.equal(true);
      recursive("rDivide", ["/", "*", "%", "Math"]);
    });
    it("14. gcd(4, 36) -> 4", () => {
      expect(ex.gcd(4, 36)).to.equal(4);
      expect(ex.gcd(24, 88)).to.equal(8);
      expect(ex.gcd(339, 17)).to.equal(1);
      expect(ex.gcd(126, 900)).to.equal(18);
      expect(ex.gcd(-4, 2)).to.equal(null);
      expect(ex.gcd(7, -36)).to.equal(null);
      recursive("gcd");
    });
    it("15. compareStr compares character by character", () => {
      expect(ex.compareStr("tomato", "tomato")).to.equal(true);
      expect(ex.compareStr("house", "houses")).to.equal(false);
      expect(ex.compareStr("", "")).to.equal(true);
      expect(ex.compareStr("", "pop")).to.equal(false);
      expect(ex.compareStr("foot", "")).to.equal(false);
      expect(ex.compareStr("big dog", "big dog")).to.equal(true);
      recursive("compareStr");
    });
    it("16. createArray('hello') -> ['h', 'e', 'l', 'l', 'o']", () => {
      expect(ex.createArray("hello")).to.deep.equal(["h", "e", "l", "l", "o"]);
      expect(ex.createArray("")).to.deep.equal([]);
      recursive("createArray", [".split("]);
    });
    it("17. reverseArr([1, 2, 3, 4]) -> [4, 3, 2, 1]", () => {
      const input = [1, 2, 3, 4];
      expect(ex.reverseArr(input)).to.deep.equal([4, 3, 2, 1]);
      expect(input).to.deep.equal([1, 2, 3, 4]);
      expect(ex.reverseArr([])).to.deep.equal([]);
      recursive("reverseArr", [".reverse("]);
    });
    it("18. buildList(0, 5) -> [0, 0, 0, 0, 0]", () => {
      expect(ex.buildList(0, 5)).to.deep.equal([0, 0, 0, 0, 0]);
      expect(ex.buildList(7, 3)).to.deep.equal([7, 7, 7]);
      expect(ex.buildList("x", 0)).to.deep.equal([]);
      recursive("buildList");
    });
    it("19. rFizzBuzz(5) -> ['1', '2', 'Fizz', '4', 'Buzz']", () => {
      expect(ex.rFizzBuzz(5)).to.deep.equal(["1", "2", "Fizz", "4", "Buzz"]);
      expect(ex.rFizzBuzz(15)[14]).to.equal("FizzBuzz");
      expect(ex.rFizzBuzz(15).length).to.equal(15);
      expect(ex.rFizzBuzz(1)).to.deep.equal(["1"]);
      recursive("rFizzBuzz");
    });
    it("20. countOccurrence uses strict equality", () => {
      expect(ex.countOccurrence([2, 7, 4, 4, 1, 4], 4)).to.equal(3);
      expect(ex.countOccurrence([2, "banana", 4, 4, 1, "banana"], "banana")).to.equal(2);
      expect(ex.countOccurrence([undefined, 7, undefined, 4, 1, 4], undefined)).to.equal(2);
      expect(ex.countOccurrence(["", null, 0, "0", false], 0)).to.equal(1);
      expect(ex.countOccurrence(["", null, 0, "false", false], false)).to.equal(1);
      expect(ex.countOccurrence(["", 7, null, 0, "0", false], null)).to.equal(1);
      expect(ex.countOccurrence(["", 7, null, 0, "0", false], "")).to.equal(1);
      recursive("countOccurrence");
    });
    it("21. rMap([1, 2, 3], double) -> [2, 4, 6]", () => {
      const input = [1, 2, 3];
      expect(ex.rMap(input, (x) => x * 2)).to.deep.equal([2, 4, 6]);
      expect(input).to.deep.equal([1, 2, 3]);
      expect(ex.rMap([], (x) => x)).to.deep.equal([]);
      expect(ex.rMap(["a", "b"], (s) => s.toUpperCase())).to.deep.equal(["A", "B"]);
      recursive("rMap");
    });
    it("22. countKeysInObj counts nested keys", () => {
      const input = { e: { x: "y" }, t: { r: { e: "r" }, p: { y: "r" } }, y: "e" };
      expect(ex.countKeysInObj(input, "e")).to.equal(2);
      expect(ex.countKeysInObj(input, "x")).to.equal(1);
      expect(ex.countKeysInObj(input, "y")).to.equal(2);
      expect(ex.countKeysInObj(input, "r")).to.equal(1);
      expect(ex.countKeysInObj(input, "zzz")).to.equal(0);
      recursive("countKeysInObj", [], true);
    });
    it("23. countValuesInObj counts nested values", () => {
      const input = { e: { x: "y" }, t: { r: { e: "r" }, p: { y: "r" } }, y: "e" };
      expect(ex.countValuesInObj(input, "r")).to.equal(2);
      expect(ex.countValuesInObj(input, "e")).to.equal(1);
      expect(ex.countValuesInObj(input, "y")).to.equal(1);
      expect(ex.countValuesInObj(input, "x")).to.equal(0);
      recursive("countValuesInObj", [], true);
    });
    it("24. replaceKeysInObj renames keys in place", () => {
      const input = { e: { x: "y" }, t: { r: { e: "r" }, p: { y: "r" } }, y: "e" };
      const output = ex.replaceKeysInObj(input, "e", "f");
      expect(output).to.equal(input);
      expect(input).to.deep.equal({ f: { x: "y" }, t: { r: { f: "r" }, p: { y: "r" } }, y: "e" });
      recursive("replaceKeysInObj", [], true);
    });
    it("25. rFibonacci(5) -> [0, 1, 1, 2, 3, 5]", () => {
      expect(ex.rFibonacci(1)).to.deep.equal([0, 1]);
      expect(ex.rFibonacci(2)).to.deep.equal([0, 1, 1]);
      expect(ex.rFibonacci(5)).to.deep.equal([0, 1, 1, 2, 3, 5]);
      expect(ex.rFibonacci(8)).to.deep.equal([0, 1, 1, 2, 3, 5, 8, 13, 21]);
      expect(ex.rFibonacci(0)).to.equal(null);
      expect(ex.rFibonacci(-7)).to.equal(null);
      recursive("rFibonacci");
    });
    it("26. nthFibo(7) -> 13", () => {
      expect(ex.nthFibo(0)).to.equal(0);
      expect(ex.nthFibo(1)).to.equal(1);
      expect(ex.nthFibo(2)).to.equal(1);
      expect(ex.nthFibo(5)).to.equal(5);
      expect(ex.nthFibo(7)).to.equal(13);
      expect(ex.nthFibo(8)).to.equal(21);
      expect(ex.nthFibo(-5)).to.equal(null);
      recursive("nthFibo");
    });
  });

  describe("hard", () => {
    it("27. capitalizeWords -> all caps", () => {
      const input = ["i", "am", "learning", "recursion"];
      expect(ex.capitalizeWords(input)).to.deep.equal(["I", "AM", "LEARNING", "RECURSION"]);
      expect(input).to.deep.equal(["i", "am", "learning", "recursion"]);
      expect(ex.capitalizeWords([])).to.deep.equal([]);
      recursive("capitalizeWords");
    });
    it("28. capitalizeFirst -> first letter uppercase", () => {
      expect(ex.capitalizeFirst(["car", "poop", "banana"])).to.deep.equal(["Car", "Poop", "Banana"]);
      expect(ex.capitalizeFirst([])).to.deep.equal([]);
      recursive("capitalizeFirst");
    });
    it("29. nestedEvenSum -> 10", () => {
      const obj1 = { a: 2, b: { b: 2, bb: { b: 3, bb: { b: 2 } } }, c: { c: { c: 2 }, cc: "ball", ccc: 5 }, d: 1, e: { e: { e: 2 }, ee: "car" } };
      expect(ex.nestedEvenSum(obj1)).to.equal(10);
      expect(ex.nestedEvenSum({ a: 1, b: { c: 3 } })).to.equal(0);
      expect(ex.nestedEvenSum({})).to.equal(0);
      recursive("nestedEvenSum", [], true);
    });
    it("30. rFlatten([1, [2], [3, [[4]]], 5]) -> [1, 2, 3, 4, 5]", () => {
      expect(ex.rFlatten([1, [2], [3, [[4]]], 5])).to.deep.equal([1, 2, 3, 4, 5]);
      expect(ex.rFlatten([])).to.deep.equal([]);
      expect(ex.rFlatten([[[[1]]]])).to.deep.equal([1]);
      recursive("rFlatten", [".flat("]);
    });
    it("31. letterTally('potato') -> { p: 1, o: 2, t: 2, a: 1 }", () => {
      expect(ex.letterTally("potato")).to.deep.equal({ p: 1, o: 2, t: 2, a: 1 });
      expect(ex.letterTally("mississippi")).to.deep.equal({ m: 1, i: 4, s: 4, p: 2 });
      expect(ex.letterTally("")).to.deep.equal({});
      recursive("letterTally");
    });
    it("32. compress removes consecutive duplicates", () => {
      const input = [1, 2, 2, 3, 4, 4, 5, 5, 5];
      expect(ex.compress(input)).to.deep.equal([1, 2, 3, 4, 5]);
      expect(input).to.deep.equal([1, 2, 2, 3, 4, 4, 5, 5, 5]);
      expect(ex.compress([1, 2, 2, 3, 4, 4, 2, 5, 5, 5, 4, 4])).to.deep.equal([1, 2, 3, 4, 2, 5, 4]);
      expect(ex.compress([])).to.deep.equal([]);
      recursive("compress");
    });
    it("33. augmentElements([[], [3], [7]], 5) -> [[5], [3, 5], [7, 5]]", () => {
      expect(ex.augmentElements([[], [3], [7]], 5)).to.deep.equal([[5], [3, 5], [7, 5]]);
      expect(ex.augmentElements([], 5)).to.deep.equal([]);
      recursive("augmentElements");
    });
    it("34. minimizeZeroes collapses runs of zeroes", () => {
      const input = [2, 0, 0, 0, 1, 4];
      expect(ex.minimizeZeroes(input)).to.deep.equal([2, 0, 1, 4]);
      expect(input).to.deep.equal([2, 0, 0, 0, 1, 4]);
      expect(ex.minimizeZeroes([2, 0, 0, 0, 1, 0, 0, 4])).to.deep.equal([2, 0, 1, 0, 4]);
      expect(ex.minimizeZeroes([0, 0])).to.deep.equal([0]);
      recursive("minimizeZeroes");
    });
    it("35. alternateSign alternates + and -", () => {
      const input = [2, 7, 8, 3, 1, 4];
      expect(ex.alternateSign(input)).to.deep.equal([2, -7, 8, -3, 1, -4]);
      expect(input).to.deep.equal([2, 7, 8, 3, 1, 4]);
      expect(ex.alternateSign([-2, -7, 8, 3, -1, 4])).to.deep.equal([2, -7, 8, -3, 1, -4]);
      expect(ex.alternateSign([])).to.deep.equal([]);
      recursive("alternateSign");
    });
    it("36. numToText replaces digits with words", () => {
      expect(ex.numToText("I have 5 dogs and 6 ponies")).to.equal("I have five dogs and six ponies");
      expect(ex.numToText("Give me 8 dollars")).to.equal("Give me eight dollars");
      expect(ex.numToText("no digits")).to.equal("no digits");
      expect(ex.numToText("")).to.equal("");
      recursive("numToText");
    });
    if (typeof document !== "undefined") {
      it("37. tagCount counts tags at any depth (browser only)", () => {
        const root = document.createElement("div");
        root.innerHTML = "<p>beep</p><div><p><span>blip</span></p></div><p>blorp</p>";
        expect(ex.tagCount("p", root)).to.equal(3);
        expect(ex.tagCount("span", root)).to.equal(1);
        expect(ex.tagCount("div", root)).to.equal(1);
        expect(ex.tagCount("h1", root)).to.equal(0);
        expect(typeof ex.tagCount("div")).to.equal("number");
        recursive("tagCount", ["querySelector", "getElementsByTagName"], true);
      });
    }
    it("38. rBinarySearch finds the index or returns null", () => {
      const input = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
      expect(ex.rBinarySearch(input, 5)).to.equal(5);
      expect(ex.rBinarySearch(input, 0)).to.equal(0);
      expect(ex.rBinarySearch(input, 15)).to.equal(15);
      expect(ex.rBinarySearch([1, 2, 3, 4, 5, 6], 6)).to.equal(5);
      expect(ex.rBinarySearch([-9, -4, 0, 3], -4)).to.equal(1);
      expect(ex.rBinarySearch([1, 3, 5, 7], 4)).to.equal(null);
      expect(ex.rBinarySearch([], 4)).to.equal(null);
      expect(input.length).to.equal(16);
      recursive("rBinarySearch", [".indexOf(", ".includes("]);
    });
    it("39. mergeSort sorts without .sort()", () => {
      const input = [34, 7, 23, 32, 5, 62];
      expect(ex.mergeSort(input)).to.deep.equal([5, 7, 23, 32, 34, 62]);
      expect(input).to.deep.equal([34, 7, 23, 32, 5, 62]);
      expect(ex.mergeSort([3, -1, 0, -7])).to.deep.equal([-7, -1, 0, 3]);
      expect(ex.mergeSort([])).to.deep.equal([]);
      expect(ex.mergeSort([1])).to.deep.equal([1]);
      recursive("mergeSort", [".sort("]);
    });
    it("40. clone makes a deep copy", () => {
      const object1 = { a: 1, b: { bb: { bbb: 2 } }, c: 3 };
      const array1 = [1, [2, []], 3, [[[4]], 5]];
      const objCopy = ex.clone(object1);
      const arrCopy = ex.clone(array1);
      expect(objCopy).to.deep.equal(object1);
      expect(objCopy).to.not.equal(object1);
      expect(objCopy.b).to.not.equal(object1.b);
      expect(objCopy.b.bb).to.not.equal(object1.b.bb);
      expect(Array.isArray(objCopy)).to.equal(false);
      expect(arrCopy).to.deep.equal(array1);
      expect(arrCopy).to.not.equal(array1);
      expect(arrCopy[1]).to.not.equal(array1[1]);
      expect(Array.isArray(arrCopy)).to.equal(true);
      objCopy.b.bb.bbb = 99;
      expect(object1.b.bb.bbb).to.equal(2);
      recursive("clone", ["JSON", "Object.assign", "structuredClone"], true);
    });
  });
});
