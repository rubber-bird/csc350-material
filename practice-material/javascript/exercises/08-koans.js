// ============================================================
//  08 - JavaScript Koans
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
    onePlusOne: FILL_ME_IN,               // 1 + 1
    onePlusOneAsString: FILL_ME_IN,       // (1 + 1).toString()
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
    typeofEmptyArray: FILL_ME_IN,         // typeof emptyArray   (a mistake? see crockford.com/javascript/remedial.html)
    emptyArrayLength: FILL_ME_IN,         // emptyArray.length
    first: FILL_ME_IN,                    // multiTypeArray[0]
    third: FILL_ME_IN,                    // multiTypeArray[2]
    fourthCalled: FILL_ME_IN,             // multiTypeArray[3]()
    fifthValue1: FILL_ME_IN,              // multiTypeArray[4].value1
    fifthValue2: FILL_ME_IN,              // multiTypeArray[4]["value2"]
    sixthFirst: FILL_ME_IN,               // multiTypeArray[5][0]
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
    afterAssignments: FILL_ME_IN,         // the whole array after the two assignments
    afterPush: FILL_ME_IN,                // the whole array after the push
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
    lengthBefore: FILL_ME_IN,
    lengthAfterPush: FILL_ME_IN,
    lengthOfNew: FILL_ME_IN,
    lengthAfterSet: FILL_ME_IN,
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
    "slice(0, 1)": FILL_ME_IN,
    "slice(0, 2)": FILL_ME_IN,
    "slice(2, 2)": FILL_ME_IN,
    "slice(2, 20)": FILL_ME_IN,
    "slice(3, 0)": FILL_ME_IN,
    "slice(3, 100)": FILL_ME_IN,
    "slice(5, 1)": FILL_ME_IN,
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
    index1: FILL_ME_IN,                   // array[1]
    index5: FILL_ME_IN,                   // array[5]
    index3: FILL_ME_IN,                   // array[3]
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
    afterPush: FILL_ME_IN,
    poppedValue: FILL_ME_IN,
    afterPop: FILL_ME_IN,                 // the array after the pop
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
    afterUnshift: FILL_ME_IN,
    shiftedValue: FILL_ME_IN,
    afterShift: FILL_ME_IN,               // the array after the shift
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

  return FILL_ME_IN;                      // add(1, 2)
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
    getMessage: FILL_ME_IN,               // getMessage()
    overrideMessage: FILL_ME_IN,          // overrideMessage()
    message: FILL_ME_IN,                  // message, after both calls
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

  return FILL_ME_IN;                      // parentfunction()
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

  return FILL_ME_IN;                      // increaseBy3(10) + increaseBy5(10)
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
    first: FILL_ME_IN,                    // returnFirstArg("first", "second", "third")
    second: FILL_ME_IN,                   // returnSecondArg("only give first arg")
    all: FILL_ME_IN,                      // returnAllArgs("first", "second", "third")
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
    john: FILL_ME_IN,
    mary: FILL_ME_IN,
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
    mastermind: FILL_ME_IN,               // meglomaniac.mastermind
    henchwoman: FILL_ME_IN,               // meglomaniac.henchwoman
    henchWoman: FILL_ME_IN,               // meglomaniac.henchWoman   (capital W)
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

  return FILL_ME_IN;                      // meglomaniac.battleCry(4)
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
    currentYear: FILL_ME_IN,
    age: FILL_ME_IN,                      // meglomaniac.calculateAge()
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
    hasBomb: FILL_ME_IN,                  // "theBomb" in meglomaniac
    hasDetonator: FILL_ME_IN,             // "theDetonator" in meglomaniac
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
    secretaryBefore: FILL_ME_IN,
    secretaryAfter: FILL_ME_IN,
    henchmanAfterDelete: FILL_ME_IN,
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
    simpleColour: FILL_ME_IN,
    colouredColour: FILL_ME_IN,
    simpleDescribe: FILL_ME_IN,           // simpleCircle.describe()
    colouredDescribe: FILL_ME_IN,         // colouredCircle.describe()
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

  return FILL_ME_IN;                      // aPerson.firstname
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

  return FILL_ME_IN;                      // aPerson.firstname
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
    before: FILL_ME_IN,
    after: FILL_ME_IN,
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
    firstName: FILL_ME_IN,
    lastName: FILL_ME_IN,
    fullName: FILL_ME_IN,
    afterOverride: FILL_ME_IN,
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
    odd: FILL_ME_IN,
    oddLength: FILL_ME_IN,                // odd.length
    numbersLength: FILL_ME_IN,            // numbers.length
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
    numbersPlus1: FILL_ME_IN,
    numbers: FILL_ME_IN,                  // numbers, afterwards
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
    reduction: FILL_ME_IN,
    numbers: FILL_ME_IN,                  // numbers, afterwards
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
    msg: FILL_ME_IN,
    numbers: FILL_ME_IN,                  // numbers, afterwards
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
    onlyEven: FILL_ME_IN,                 // onlyEven.every(isEven)
    mixedBag: FILL_ME_IN,                 // mixedBag.every(isEven)
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
    onlyEven: FILL_ME_IN,                 // onlyEven.some(isEven)
    mixedBag: FILL_ME_IN,                 // mixedBag.some(isEven)
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
    zeroToTwo: FILL_ME_IN,                // Array.from({ length: 3 }, function(v, i) { return i; })
    oneToThree: FILL_ME_IN,               // Array.from({ length: 3 }, function(v, i) { return i + 1; })
    countingDown: FILL_ME_IN,             // Array.from({ length: 4 }, function(v, i) { return 0 - i; })
  };
}

/**
 * flatKoan()
 *
 * flat() removes one level of nesting; flat(Infinity) removes them all.
 */
function flatKoan() {
  return {
    oneLevel: FILL_ME_IN,                 // [[1, 2], [3, 4]].flat()
    allLevels: FILL_ME_IN,                // [1, [2, [3, [4]]]].flat(Infinity)
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

  return FILL_ME_IN;                      // result
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
    cook: FILL_ME_IN,                     // swedishChef.cook()
    answerNanny: FILL_ME_IN,              // swedishChef.answerNanny()
    age: FILL_ME_IN,                      // swedishChef.age
    hobby: FILL_ME_IN,                    // swedishChef.hobby
    mood: FILL_ME_IN,                     // swedishChef.mood
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
    doTrick: FILL_ME_IN,                  // gonzo.doTrick()
    answerNanny: FILL_ME_IN,              // gonzo.answerNanny()
    age: FILL_ME_IN,                      // gonzo.age
    hobby: FILL_ME_IN,                    // gonzo.hobby
    trick: FILL_ME_IN,                    // gonzo.trick
    isMuppet: FILL_ME_IN,                 // gonzo instanceof Muppet
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
  // your code here, with filter() and some() or every()
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
  // your code here, with Array.from(), filter() and reduce()
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
  // your code here, with map(), flat() and reduce()
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
