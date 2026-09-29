describe("08 - Koans", () => {
  const ex = require("../exercises/08-koans");

  // Each koan function returns an object of predictions. `koan` makes one
  // test per key, comparing the prediction to the real answer.
  const koan = (fnName, expected) => {
    describe(fnName + "()", () => {
      Object.keys(expected).forEach((key) => {
        it(key + " is " + JSON.stringify(expected[key]), () => {
          const actual = ex[fnName]()[key];
          expect(actual).to.deep.equal(expected[key]);
        });
      });
    });
  };
  // For koans that return a single value.
  const single = (fnName, expected) => {
    it(fnName + "() is " + JSON.stringify(expected), () => {
      expect(ex[fnName]()).to.deep.equal(expected);
    });
  };

  describe("warm up", () => {
    koan("warmUp", { onePlusOne: 2, onePlusOneAsString: "2" });
  });

  describe("arrays", () => {
    koan("arrayBasics", {
      typeofEmptyArray: "object", emptyArrayLength: 0,
      first: 0, third: "two", fourthCalled: 3, fifthValue1: 4, fifthValue2: 5, sixthFirst: 6,
    });
    koan("arrayLiterals", { afterAssignments: [1, 2], afterPush: [1, 2, 3] });
    koan("arrayLength", { lengthBefore: 4, lengthAfterPush: 6, lengthOfNew: 10, lengthAfterSet: 5 });
    koan("sliceArrays", {
      "slice(0, 1)": ["peanut"],
      "slice(0, 2)": ["peanut", "butter"],
      "slice(2, 2)": [],
      "slice(2, 20)": ["and", "jelly"],
      "slice(3, 0)": [],
      "slice(3, 100)": ["jelly"],
      "slice(5, 1)": [],
    });
    koan("arrayReferences", { index1: "changed in function", index5: "changed in assignedArray", index3: "three" });
    koan("pushAndPop", { afterPush: [1, 2, 3], poppedValue: 3, afterPop: [1, 2] });
    koan("shiftArrays", { afterUnshift: [3, 1, 2], shiftedValue: 3, afterShift: [1, 2] });
  });

  describe("functions", () => {
    single("declareFunctions", 3);
    koan("innerVariablesOverrideOuter", { getMessage: "Outer", overrideMessage: "Inner", message: "Outer" });
    single("lexicalScoping", "local");
    single("synthesiseFunctions", 28);
    koan("extraArguments", { first: "first", second: undefined, all: "first,second,third" });
    koan("functionsAsValues", { john: "John rules!", mary: "Mary totally rules!" });
  });

  describe("objects", () => {
    koan("objectProperties", { mastermind: "Joker", henchwoman: "Harley", henchWoman: undefined });
    single("methods", "They are Pinky and the Brain Brain Brain Brain");
    koan("thisInMethods", { currentYear: 2020, age: 50 });
    koan("inKeyword", { hasBomb: true, hasDetonator: false });
    koan("addAndDeleteProperties", { secretaryBefore: false, secretaryAfter: true, henchmanAfterDelete: false });
    koan("prototypes", {
      simpleColour: undefined, colouredColour: "red",
      simpleDescribe: "This circle has a radius of: 10",
      colouredDescribe: "This circle has a radius of: 5",
    });
  });

  describe("mutability", () => {
    single("mutableProperties", "Alan");
    single("mutableConstructedProperties", "Alan");
    koan("mutablePrototypeProperties", { before: "John Smith", after: "Smith, John" });
    koan("privateVariables", { firstName: "John", lastName: "Smith", fullName: "John Smith", afterOverride: "Andrews, Penny" });
  });

  describe("higher order functions", () => {
    koan("filterKoan", { odd: [1, 3], oddLength: 2, numbersLength: 3 });
    koan("mapKoan", { numbersPlus1: [2, 3, 4], numbers: [1, 2, 3] });
    koan("reduceKoan", { reduction: 6, numbers: [1, 2, 3] });
    koan("forEachKoan", { msg: "falsetruefalse", numbers: [1, 2, 3] });
    koan("everyKoan", { onlyEven: true, mixedBag: false });
    koan("someKoan", { onlyEven: true, mixedBag: true });
    koan("arrayFromKoan", { zeroToTwo: [0, 1, 2], oneToThree: [1, 2, 3], countingDown: [0, -1, -2, -3] });
    koan("flatKoan", { oneLevel: [1, 2, 3, 4], allLevels: [1, 2, 3, 4] });
    single("chainKoan", 6);
  });

  describe("inheritance", () => {
    koan("inheritanceWithCall", { cook: "Mmmm soup!", answerNanny: "Everything's cool!", age: 2, hobby: "cooking", mood: "chillin" });
    koan("inheritanceWithObjectCreate", {
      doTrick: "eat a tire", answerNanny: "Everything's cool!", age: 3, hobby: "daredevil performer", trick: "eat a tire", isMuppet: true,
    });
  });

  describe("applying what we have learnt", () => {
    const products = () => [
      { name: "Sonoma", ingredients: ["artichoke", "sundried tomatoes", "mushrooms"], containsNuts: false },
      { name: "Pizza Primavera", ingredients: ["roma", "sundried tomatoes", "goats cheese", "rosemary"], containsNuts: false },
      { name: "South Of The Border", ingredients: ["black beans", "jalapenos", "mushrooms"], containsNuts: false },
      { name: "Blue Moon", ingredients: ["blue cheese", "garlic", "walnuts"], containsNuts: true },
      { name: "Taste Of Athens", ingredients: ["spinach", "kalamata olives", "sesame seeds"], containsNuts: true },
    ];
    // The body of a function with comments stripped, for checking style.
    const body = (fn) => fn.toString().replace(/\/\*[\s\S]*?\*\//g, "").replace(/\/\/.*$/gm, "");
    const usesNoLoops = (fn) => !/\b(for|while)\s*\(/.test(body(fn));

    it("pizzasICanEat finds the one pizza with no nuts and no mushrooms", () => {
      const result = ex.pizzasICanEat(products());
      expect(result).to.have.length(1);
      expect(result[0].name).to.equal("Pizza Primavera");
    });
    it("pizzasICanEat does not change the products array", () => {
      const input = products();
      ex.pizzasICanEat(input);
      expect(input).to.deep.equal(products());
    });
    it("pizzasICanEat uses no loops", () => {
      expect(usesNoLoops(ex.pizzasICanEat)).to.equal(true);
    });

    it("sumOfMultiplesOf3Or5Below(10) -> 23", () => {
      expect(ex.sumOfMultiplesOf3Or5Below(10)).to.equal(23);
    });
    it("sumOfMultiplesOf3Or5Below(1000) -> 233168", () => {
      expect(ex.sumOfMultiplesOf3Or5Below(1000)).to.equal(233168);
    });
    it("sumOfMultiplesOf3Or5Below uses no loops", () => {
      expect(usesNoLoops(ex.sumOfMultiplesOf3Or5Below)).to.equal(true);
    });

    it("countIngredients counts mushrooms twice and sundried tomatoes twice", () => {
      const counts = ex.countIngredients(products());
      expect(counts["mushrooms"]).to.equal(2);
      expect(counts["sundried tomatoes"]).to.equal(2);
      expect(counts["garlic"]).to.equal(1);
    });
    it("countIngredients on a small input", () => {
      expect(ex.countIngredients([{ ingredients: ["a", "b"] }, { ingredients: ["b"] }])).to.deep.equal({ a: 1, b: 2 });
    });
    it("countIngredients uses no loops", () => {
      expect(usesNoLoops(ex.countIngredients)).to.equal(true);
    });
  });
});
