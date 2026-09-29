// ============================================================
//  08 - JavaScript Koans (solution)
// ============================================================
//
//  A koan is a small piece of code with a blank in it. Here you do not
//  write functions; you predict what JavaScript does. Each function below
//  runs some code and returns your predictions. Replace every FILL_ME_IN
//  with the value you think the code produces, then let the tests tell
//  you whether you were right.
//
//  Do not run the code in your head only: reason it out, fill in the
//  value, and check. When you are wrong, work out why before moving on.
//
//  Run the tests:   open index.html in a browser
//
// ============================================================

const FILL_ME_IN = "Fill this value in";

// ---------------------------------------------- WARM UP ------

/**
 * warmUp()
 *
 * The first two blanks. Notice that the second one is a string.
 */
function warmUp() {
  return {
    onePlusOne: 2,               // 1 + 1
    onePlusOneAsString: "2",       // (1 + 1).toString()
  };
}

// ---------------------------------------------- ARRAYS -------

/**
 * arrayBasics()
 *
 * Arrays can hold anything, including functions, objects and arrays.
 */
function arrayBasics() {
  var emptyArray = [];
  var multiTypeArray = [0, 1, "two", function() { return 3; }, { value1: 4, value2: 5 }, [6, 7]];

  return {
    typeofEmptyArray: "object",         // typeof emptyArray   (a mistake? see crockford.com/javascript/remedial.html)
    emptyArrayLength: 0,         // emptyArray.length
    first: 0,                    // multiTypeArray[0]
    third: "two",                    // multiTypeArray[2]
    fourthCalled: 3,             // multiTypeArray[3]()
    fifthValue1: 4,              // multiTypeArray[4].value1
    fifthValue2: 5,              // multiTypeArray[4]["value2"]
    sixthFirst: 6,               // multiTypeArray[5][0]
  };
}

/**
 * arrayLiterals()
 *
 * Assigning to an index and pushing both grow an array.
 */
function arrayLiterals() {
  var array = [];
  array[0] = 1;
  array[1] = 2;
  var afterAssignments = array.slice();
  array.push(3);

  return {
    afterAssignments: [1, 2],         // the whole array after the two assignments
    afterPush: [1, 2, 3],                // the whole array after the push
  };
}

/**
 * arrayLength()
 *
 * length is not just read-only bookkeeping.
 */
function arrayLength() {
  var fourNumberArray = [1, 2, 3, 4];
  var lengthBefore = fourNumberArray.length;
  fourNumberArray.push(5, 6);
  var lengthAfterPush = fourNumberArray.length;

  var tenEmptyElementArray = new Array(10);
  var lengthOfNew = tenEmptyElementArray.length;
  tenEmptyElementArray.length = 5;
  var lengthAfterSet = tenEmptyElementArray.length;

  return {
    lengthBefore: 4,
    lengthAfterPush: 6,
    lengthOfNew: 10,
    lengthAfterSet: 5,
  };
}

/**
 * sliceArrays()
 *
 * slice(start, end) copies from start up to but not including end.
 */
function sliceArrays() {
  var array = ["peanut", "butter", "and", "jelly"];

  return {
    "slice(0, 1)": ["peanut"],
    "slice(0, 2)": ["peanut", "butter"],
    "slice(2, 2)": [],
    "slice(2, 20)": ["and", "jelly"],
    "slice(3, 0)": [],
    "slice(3, 100)": ["jelly"],
    "slice(5, 1)": [],
  };
}

/**
 * arrayReferences()
 *
 * Arrays are passed and assigned by reference. slice() makes a copy.
 */
function arrayReferences() {
  var array = ["zero", "one", "two", "three", "four", "five"];

  var passedByReference = function(refArray) {
    refArray[1] = "changed in function";
  };
  passedByReference(array);

  var assignedArray = array;
  assignedArray[5] = "changed in assignedArray";

  var copyOfArray = array.slice();
  copyOfArray[3] = "changed in copyOfArray";

  return {
    index1: "changed in function",                   // array[1]
    index5: "changed in assignedArray",                   // array[5]
    index3: "three",                   // array[3]
  };
}

/**
 * pushAndPop()
 */
function pushAndPop() {
  var array = [1, 2];
  array.push(3);
  var afterPush = array.slice();
  var poppedValue = array.pop();

  return {
    afterPush: [1, 2, 3],
    poppedValue: 3,
    afterPop: [1, 2],                 // the array after the pop
  };
}

/**
 * shiftArrays()
 *
 * unshift and shift work at the front instead of the back.
 */
function shiftArrays() {
  var array = [1, 2];
  array.unshift(3);
  var afterUnshift = array.slice();
  var shiftedValue = array.shift();

  return {
    afterUnshift: [3, 1, 2],
    shiftedValue: 3,
    afterShift: [1, 2],               // the array after the shift
  };
}

// ---------------------------------------------- FUNCTIONS ----

/**
 * declareFunctions()
 */
function declareFunctions() {
  var add = function(a, b) {
    return a + b;
  };

  return 3;                      // add(1, 2)
}

/**
 * innerVariablesOverrideOuter()
 *
 * A var inside a function is a new variable, even with the same name.
 */
function innerVariablesOverrideOuter() {
  var message = "Outer";

  var getMessage = function() {
    return message;
  };

  var overrideMessage = function() {
    var message = "Inner";
    return message;
  };

  return {
    getMessage: "Outer",               // getMessage()
    overrideMessage: "Inner",          // overrideMessage()
    message: "Outer",                  // message, after both calls
  };
}

/**
 * lexicalScoping()
 *
 * A function sees the variables of the place where it was written.
 */
function lexicalScoping() {
  var variable = "top-level";

  var parentfunction = function() {
    var variable = "local";

    var childfunction = function() {
      return variable;
    };
    return childfunction();
  };

  return "local";                      // parentfunction()
}

/**
 * synthesiseFunctions()
 *
 * A function that returns a function remembers the arguments it was made with.
 */
function synthesiseFunctions() {
  var makeIncreaseByFunction = function(increaseByAmount) {
    return function(numberToIncrease) {
      return numberToIncrease + increaseByAmount;
    };
  };

  var increaseBy3 = makeIncreaseByFunction(3);
  var increaseBy5 = makeIncreaseByFunction(5);

  return 28;                      // increaseBy3(10) + increaseBy5(10)
}

/**
 * extraArguments()
 *
 * JavaScript never complains about too many or too few arguments.
 */
function extraArguments() {
  var returnFirstArg = function(firstArg) {
    return firstArg;
  };

  var returnSecondArg = function(firstArg, secondArg) {
    return secondArg;
  };

  var returnAllArgs = function() {
    var argsArray = [];
    for (var i = 0; i < arguments.length; i += 1) {
      argsArray.push(arguments[i]);
    }
    return argsArray.join(",");
  };

  return {
    first: "first",                    // returnFirstArg("first", "second", "third")
    second: undefined,                   // returnSecondArg("only give first arg")
    all: "first,second,third",                      // returnAllArgs("first", "second", "third")
  };
}

/**
 * functionsAsValues()
 *
 * A property that holds a function can be swapped for another function.
 */
function functionsAsValues() {
  var appendRules = function(name) {
    return name + " rules!";
  };

  var appendDoubleRules = function(name) {
    return name + " totally rules!";
  };

  var praiseSinger = { givePraise: appendRules };
  var john = praiseSinger.givePraise("John");

  praiseSinger.givePraise = appendDoubleRules;
  var mary = praiseSinger.givePraise("Mary");

  return {
    john: "John rules!",
    mary: "Mary totally rules!",
  };
}

// ---------------------------------------------- OBJECTS ------

/**
 * objectProperties()
 *
 * Objects are collections of properties. Property names are case sensitive.
 */
function objectProperties() {
  var meglomaniac = { mastermind: "Joker", henchwoman: "Harley" };

  return {
    mastermind: "Joker",               // meglomaniac.mastermind
    henchwoman: "Harley",               // meglomaniac.henchwoman
    henchWoman: undefined,               // meglomaniac.henchWoman   (capital W)
  };
}

/**
 * methods()
 *
 * A property that is a function acts like a method. Inside it, `this`
 * is the object it was called on.
 */
function methods() {
  var meglomaniac = {
    mastermind: "Brain",
    henchman: "Pinky",
    battleCry: function(noOfBrains) {
      return "They are " + this.henchman + " and the" +
        Array(noOfBrains + 1).join(" " + this.mastermind);
    },
  };

  return "They are Pinky and the Brain Brain Brain Brain";                      // meglomaniac.battleCry(4)
}

/**
 * thisInMethods()
 */
function thisInMethods() {
  var currentDate = new Date("2020-06-15");
  var currentYear = currentDate.getFullYear();
  var meglomaniac = {
    mastermind: "James Wood",
    henchman: "Adam West",
    birthYear: 1970,
    calculateAge: function() {
      return currentYear - this.birthYear;
    },
  };

  return {
    currentYear: 2020,
    age: 50,                      // meglomaniac.calculateAge()
  };
}

/**
 * inKeyword()
 *
 * "name" in object asks whether the object has that property.
 */
function inKeyword() {
  var meglomaniac = {
    mastermind: "The Monarch",
    henchwoman: "Dr Girlfriend",
    theBomb: true,
  };

  return {
    hasBomb: true,                  // "theBomb" in meglomaniac
    hasDetonator: false,             // "theDetonator" in meglomaniac
  };
}

/**
 * addAndDeleteProperties()
 */
function addAndDeleteProperties() {
  var meglomaniac = { mastermind: "Agent Smith", henchman: "Agent Smith" };
  var secretaryBefore = "secretary" in meglomaniac;

  meglomaniac.secretary = "Agent Smith";
  var secretaryAfter = "secretary" in meglomaniac;

  delete meglomaniac.henchman;
  var henchmanAfterDelete = "henchman" in meglomaniac;

  return {
    secretaryBefore: false,
    secretaryAfter: true,
    henchmanAfterDelete: false,
  };
}

/**
 * prototypes()
 *
 * Adding to Constructor.prototype gives the new thing to every instance,
 * even ones that already exist.
 */
function prototypes() {
  var Circle = function(radius) {
    this.radius = radius;
  };

  var simpleCircle = new Circle(10);
  var colouredCircle = new Circle(5);
  colouredCircle.colour = "red";

  var simpleColour = simpleCircle.colour;
  var colouredColour = colouredCircle.colour;

  Circle.prototype.describe = function() {
    return "This circle has a radius of: " + this.radius;
  };

  return {
    simpleColour: undefined,
    colouredColour: "red",
    simpleDescribe: "This circle has a radius of: 10",           // simpleCircle.describe()
    colouredDescribe: "This circle has a radius of: 5",         // colouredCircle.describe()
  };
}

// ---------------------------------------------- MUTABILITY ---

/**
 * mutableProperties()
 *
 * Object properties are public and can be changed by anyone.
 */
function mutableProperties() {
  var aPerson = { firstname: "John", lastname: "Smith" };
  aPerson.firstname = "Alan";

  return "Alan";                      // aPerson.firstname
}

/**
 * mutableConstructedProperties()
 *
 * Properties set in a constructor are just as public.
 */
function mutableConstructedProperties() {
  var Person = function(firstname, lastname) {
    this.firstname = firstname;
    this.lastname = lastname;
  };
  var aPerson = new Person("John", "Smith");
  aPerson.firstname = "Alan";

  return "Alan";                      // aPerson.firstname
}

/**
 * mutablePrototypeProperties()
 *
 * An instance can shadow a method from its prototype.
 */
function mutablePrototypeProperties() {
  var Person = function(firstname, lastname) {
    this.firstname = firstname;
    this.lastname = lastname;
  };
  Person.prototype.getFullName = function() {
    return this.firstname + " " + this.lastname;
  };

  var aPerson = new Person("John", "Smith");
  var before = aPerson.getFullName();

  aPerson.getFullName = function() {
    return this.lastname + ", " + this.firstname;
  };
  var after = aPerson.getFullName();

  return {
    before: "John Smith",
    after: "Smith, John",
  };
}

/**
 * privateVariables()
 *
 * Variables and parameters inside a constructor are private. Setting a
 * property of the same name on the object does not touch them.
 */
function privateVariables() {
  var Person = function(firstname, lastname) {
    var fullName = firstname + " " + lastname;

    this.getFirstName = function() { return firstname; };
    this.getLastName = function() { return lastname; };
    this.getFullName = function() { return fullName; };
  };
  var aPerson = new Person("John", "Smith");

  aPerson.firstname = "Penny";
  aPerson.lastname = "Andrews";
  aPerson.fullName = "Penny Andrews";

  var firstName = aPerson.getFirstName();
  var lastName = aPerson.getLastName();
  var fullName = aPerson.getFullName();

  aPerson.getFullName = function() {
    return aPerson.lastname + ", " + aPerson.firstname;
  };
  var afterOverride = aPerson.getFullName();

  return {
    firstName: "John",
    lastName: "Smith",
    fullName: "John Smith",
    afterOverride: "Andrews, Penny",
  };
}

// ---------------------------------------------- HIGHER ORDER -

/**
 * filterKoan()
 *
 * filter keeps the items the callback says yes to. It does not change
 * the original array.
 */
function filterKoan() {
  var numbers = [1, 2, 3];
  var odd = numbers.filter(function(x) { return x % 2 !== 0; });

  return {
    odd: [1, 3],
    oddLength: 2,                // odd.length
    numbersLength: 3,            // numbers.length
  };
}

/**
 * mapKoan()
 *
 * map transforms every item into a new array.
 */
function mapKoan() {
  var numbers = [1, 2, 3];
  var numbersPlus1 = numbers.map(function(x) { return x + 1; });

  return {
    numbersPlus1: [2, 3, 4],
    numbers: [1, 2, 3],                  // numbers, afterwards
  };
}

/**
 * reduceKoan()
 *
 * reduce carries one result through every item.
 */
function reduceKoan() {
  var numbers = [1, 2, 3];
  var reduction = numbers.reduce(
    function(memo, x) {
      // memo is the result from the last call, x is the current number
      return memo + x;
    },
    /* initial */ 0
  );

  return {
    reduction: 6,
    numbers: [1, 2, 3],                  // numbers, afterwards
  };
}

/**
 * forEachKoan()
 *
 * forEach is for side effects. Note that (item % 2) === 0 is a boolean,
 * and adding a boolean to a string turns it into text.
 */
function forEachKoan() {
  var numbers = [1, 2, 3];
  var msg = "";
  var isEven = function(item) {
    msg += (item % 2) === 0;
  };

  numbers.forEach(isEven);

  return {
    msg: "falsetruefalse",
    numbers: [1, 2, 3],                  // numbers, afterwards
  };
}

/**
 * everyKoan()
 *
 * every asks whether all items pass.
 */
function everyKoan() {
  var onlyEven = [2, 4, 6];
  var mixedBag = [2, 4, 5, 6];
  var isEven = function(x) { return x % 2 === 0; };

  return {
    onlyEven: true,                 // onlyEven.every(isEven)
    mixedBag: false,                 // mixedBag.every(isEven)
  };
}

/**
 * someKoan()
 *
 * some asks whether at least one item passes.
 */
function someKoan() {
  var onlyEven = [2, 4, 6];
  var mixedBag = [2, 4, 5, 6];
  var isEven = function(x) { return x % 2 === 0; };

  return {
    onlyEven: true,                 // onlyEven.some(isEven)
    mixedBag: true,                 // mixedBag.some(isEven)
  };
}

/**
 * arrayFromKoan()
 *
 * Array.from({ length: n }, fn) calls fn(undefined, i) for i = 0 .. n-1
 * and collects the results. A handy way to generate a range.
 */
function arrayFromKoan() {
  return {
    zeroToTwo: [0, 1, 2],                // Array.from({ length: 3 }, function(v, i) { return i; })
    oneToThree: [1, 2, 3],               // Array.from({ length: 3 }, function(v, i) { return i + 1; })
    countingDown: [0, -1, -2, -3],             // Array.from({ length: 4 }, function(v, i) { return 0 - i; })
  };
}

/**
 * flatKoan()
 *
 * flat() removes one level of nesting; flat(Infinity) removes them all.
 */
function flatKoan() {
  return {
    oneLevel: [1, 2, 3, 4],                 // [[1, 2], [3, 4]].flat()
    allLevels: [1, 2, 3, 4],                // [1, [2, [3, [4]]]].flat(Infinity)
  };
}

/**
 * chainKoan()
 *
 * Because each method returns an array, you can chain them.
 */
function chainKoan() {
  var result = [[0, 1], 2]
    .flat()
    .map(function(x) { return x + 1; })
    .reduce(function(sum, x) { return sum + x; });

  return 6;                      // result
}

// ---------------------------------------------- INHERITANCE --

/**
 * inheritanceWithCall()
 *
 * Parent.call(this, ...) runs the parent constructor on the new object.
 */
function inheritanceWithCall() {
  var Muppet = function(age, hobby) {
    this.age = age;
    this.hobby = hobby;
    this.answerNanny = function() {
      return "Everything's cool!";
    };
  };

  var SwedishChef = function(age, hobby, mood) {
    Muppet.call(this, age, hobby);
    this.mood = mood;
    this.cook = function() {
      return "Mmmm soup!";
    };
  };

  SwedishChef.prototype = new Muppet();

  var swedishChef = new SwedishChef(2, "cooking", "chillin");

  return {
    cook: "Mmmm soup!",                     // swedishChef.cook()
    answerNanny: "Everything's cool!",              // swedishChef.answerNanny()
    age: 2,                      // swedishChef.age
    hobby: "cooking",                    // swedishChef.hobby
    mood: "chillin",                     // swedishChef.mood
  };
}

/**
 * inheritanceWithObjectCreate()
 *
 * Object.create(proto) makes a new empty object whose prototype is proto.
 * That links the prototypes without running the parent constructor, and
 * it makes instanceof work. See crockford.com/javascript/prototypal.html
 */
function inheritanceWithObjectCreate() {
  var Muppet = function(age, hobby) {
    this.age = age;
    this.hobby = hobby;
    this.answerNanny = function() {
      return "Everything's cool!";
    };
  };

  var Gonzo = function(age, hobby, trick) {
    Muppet.call(this, age, hobby);
    this.trick = trick;
    this.doTrick = function() {
      return this.trick;
    };
  };

  Gonzo.prototype = Object.create(Muppet.prototype);

  var gonzo = new Gonzo(3, "daredevil performer", "eat a tire");

  return {
    doTrick: "eat a tire",                  // gonzo.doTrick()
    answerNanny: "Everything's cool!",              // gonzo.answerNanny()
    age: 3,                      // gonzo.age
    hobby: "daredevil performer",                    // gonzo.hobby
    trick: "eat a tire",                    // gonzo.trick
    isMuppet: true,                 // gonzo instanceof Muppet
  };
}

// ---------------------------------------------- APPLYING -----
//
//  The last three are different: you write the code, in the functional
//  style, using filter / map / every / some / reduce / flat / Array.from.
//  No for or while loops. The imperative version is shown as a comment
//  so you can compare.

/**
 * pizzasICanEat(products)
 *
 * I am allergic to nuts and I hate mushrooms. Return the products I can
 * eat: no nuts, and "mushrooms" is not among the ingredients.
 *
 * Input:   products - an array of { name, ingredients, containsNuts }
 * Output:  a new array of the products I can eat
 *
 * Imperative version:
 *   var result = [];
 *   for (var i = 0; i < products.length; i += 1) {
 *     if (products[i].containsNuts === false) {
 *       var hasMushrooms = false;
 *       for (var j = 0; j < products[i].ingredients.length; j += 1) {
 *         if (products[i].ingredients[j] === "mushrooms") hasMushrooms = true;
 *       }
 *       if (!hasMushrooms) result.push(products[i]);
 *     }
 *   }
 *   return result;
 */
function pizzasICanEat(products) {
  return products.filter(function(p) {
    return !p.containsNuts && !p.ingredients.some(function(ing) { return ing === "mushrooms"; });
  });
}

/**
 * sumOfMultiplesOf3Or5Below(limit)
 *
 * Add up every natural number below limit that is a multiple of 3 or 5.
 *
 * Input:   limit - a whole number
 * Output:  the sum
 *
 * Example:
 *   sumOfMultiplesOf3Or5Below(10)    ->  23        (3 + 5 + 6 + 9)
 *   sumOfMultiplesOf3Or5Below(1000)  ->  233168
 *
 * Imperative version:
 *   var sum = 0;
 *   for (var i = 1; i < limit; i += 1) {
 *     if (i % 3 === 0 || i % 5 === 0) sum += i;
 *   }
 *   return sum;
 */
function sumOfMultiplesOf3Or5Below(limit) {
  return Array.from({ length: limit - 1 }, function(v, i) { return i + 1; })
    .filter(function(n) { return n % 3 === 0 || n % 5 === 0; })
    .reduce(function(total, n) { return total + n; }, 0);
}

/**
 * countIngredients(products)
 *
 * Count how many products each ingredient appears in.
 *
 * Input:   products - an array of { name, ingredients, containsNuts }
 * Output:  an object mapping ingredient name to a count
 *
 * Example:
 *   countIngredients([{ ingredients: ["a", "b"] }, { ingredients: ["b"] }])
 *     ->  { a: 1, b: 2 }
 *
 * Imperative version:
 *   var counts = {};
 *   for (var i = 0; i < products.length; i += 1) {
 *     for (var j = 0; j < products[i].ingredients.length; j += 1) {
 *       var name = products[i].ingredients[j];
 *       counts[name] = (counts[name] || 0) + 1;
 *     }
 *   }
 *   return counts;
 */
function countIngredients(products) {
  return products
    .map(function(p) { return p.ingredients; })
    .flat()
    .reduce(function(counts, name) {
      counts[name] = (counts[name] || 0) + 1;
      return counts;
    }, {});
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  warmUp,
  arrayBasics, arrayLiterals, arrayLength, sliceArrays, arrayReferences, pushAndPop, shiftArrays,
  declareFunctions, innerVariablesOverrideOuter, lexicalScoping, synthesiseFunctions, extraArguments, functionsAsValues,
  objectProperties, methods, thisInMethods, inKeyword, addAndDeleteProperties, prototypes,
  mutableProperties, mutableConstructedProperties, mutablePrototypeProperties, privateVariables,
  filterKoan, mapKoan, reduceKoan, forEachKoan, everyKoan, someKoan, arrayFromKoan, flatKoan, chainKoan,
  inheritanceWithCall, inheritanceWithObjectCreate,
  pizzasICanEat, sumOfMultiplesOf3Or5Below, countIngredients,
};
