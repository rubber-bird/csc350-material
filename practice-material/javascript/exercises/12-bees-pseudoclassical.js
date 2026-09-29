// ============================================================
//  12 - Bees: pseudoclassical style
// ============================================================
//
//  Build the same bee family as exercise 11, but with constructor
//  functions and prototypes, the way JavaScript did inheritance before
//  the `class` keyword. Compare the two files when you are done.
//
//  What you will use:
//
//    var Parent = function() {          a constructor: a plain function
//      this.name = "x";                 called with `new`, `this` is the
//    };                                 new object
//    Parent.prototype.greet = function() { ... };   a shared method
//
//    var Child = function() {
//      Parent.call(this);               run the parent constructor on
//      this.extra = true;               this new object
//    };
//    Child.prototype = Object.create(Parent.prototype);   inherit methods
//    Child.prototype.constructor = Child;                 repair the link
//    Child.prototype.greet = function() { ... };          override
//
//  The family tree:
//
//    Grub                 age 0,  color "pink",  food "jelly",  eat()
//     └─ Bee              age 5,  color "yellow", job "keep on growing"
//         ├─ HoneyMakerBee  age 10, job "make honey", honeyPot 0,
//         │                 makeHoney() adds 1, giveHoney() subtracts 1
//         └─ ForagerBee     age 10, job "find pollen", canFly true,
//             │             treasureChest [], forage(treasure) pushes it
//             └─ RetiredForagerBee  age 40, job "gamble", color "grey",
//                           canFly false, forage() returns
//                           "I am too old, let me play cards instead",
//                           gamble(treasure) pushes it into treasureChest
//
//  Rules: constructor functions and prototypes only. No `class`.
//  Every test group also checks the class "follows the pseudoclassical
//  pattern": it uses `this`, puts methods on the prototype, instances
//  share those methods, and prototype.constructor points back.
//  Run the tests:   open index.html in a browser
//
// ============================================================

// Leave this line alone. It keeps these names separate from exercise 11.
(function() {

/**
 * Grub
 *
 * age 0, color "pink", food "jelly"
 * eat() returns "Mmmmmmmmm jelly"
 */
var Grub = function() {
  // your code here
};

/**
 * Bee inherits from Grub
 *
 * age 5, color "yellow", job "keep on growing"
 * inherits food and eat() from Grub
 */
var Bee = function() {
  // your code here
};

/**
 * HoneyMakerBee inherits from Bee
 *
 * age 10, job "make honey", honeyPot 0
 * makeHoney() adds 1 to honeyPot, giveHoney() subtracts 1
 */
var HoneyMakerBee = function() {
  // your code here
};

/**
 * ForagerBee inherits from Bee
 *
 * age 10, job "find pollen", canFly true, treasureChest []
 * forage(treasure) pushes the treasure into treasureChest
 */
var ForagerBee = function() {
  // your code here
};

/**
 * RetiredForagerBee inherits from ForagerBee
 *
 * age 40, job "gamble", color "grey", canFly false
 * forage() returns "I am too old, let me play cards instead"
 * gamble(treasure) pushes the treasure into treasureChest (it always wins)
 */
var RetiredForagerBee = function() {
  // your code here
};

// Leave these lines alone. They make the classes visible to the tests.
module.exports = { Grub, Bee, HoneyMakerBee, ForagerBee, RetiredForagerBee };
})();
