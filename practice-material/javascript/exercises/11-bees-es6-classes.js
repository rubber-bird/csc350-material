// ============================================================
//  11 - Bees: ES6 classes
// ============================================================
//
//  Build a family of bee classes with the modern `class` syntax.
//  Exercise 12 rebuilds the same family the old way, with constructor
//  functions and prototypes, so you can see what `class` does underneath.
//
//  What you will use:
//    class Name { constructor() { ... }  method() { ... } }
//    class Child extends Parent          inherit from Parent
//    super()                             run the parent constructor
//                                        (must be called before using `this`)
//    super.method()                      call the parent's version of a method
//
//  The family tree:
//
//    Grub                 age 0,  color "pink",  food "jelly",  eat()
//     └─ Bee              age 5,  color "yellow", job "Keep on growing"
//         ├─ HoneyMakerBee  age 10, job "make honey", honeyPot 0,
//         │                 makeHoney() adds 1, giveHoney() subtracts 1
//         └─ ForagerBee     age 10, job "find pollen", canFly true,
//             │             treasureChest [], forage(treasure) pushes it
//             └─ RetiredForagerBee  age 40, job "gamble", color "grey",
//                           canFly false, forage() returns
//                           "I am too old, let me play cards instead",
//                           gamble(treasure) pushes it into treasureChest
//
//  Rules: `class` syntax only. No manual prototype wiring.
//  Do them in order: Grub, Bee, HoneyMakerBee, ForagerBee, RetiredForagerBee.
//  Run the tests:   open index.html in a browser
//
// ============================================================

// Leave this line alone. It keeps these names separate from exercise 12.
(function() {

/**
 * Grub
 *
 * age 0, color "pink", food "jelly"
 * eat() returns "Mmmmmmmmm jelly"
 */
class Grub {
  // your code here
}

/**
 * Bee extends Grub
 *
 * age 5, color "yellow", job "Keep on growing"
 * inherits food and eat() from Grub
 */
class Bee {
  // your code here
}

/**
 * HoneyMakerBee extends Bee
 *
 * age 10, job "make honey", honeyPot 0
 * makeHoney() adds 1 to honeyPot, giveHoney() subtracts 1
 */
class HoneyMakerBee {
  // your code here
}

/**
 * ForagerBee extends Bee
 *
 * age 10, job "find pollen", canFly true, treasureChest []
 * forage(treasure) pushes the treasure into treasureChest
 */
class ForagerBee {
  // your code here
}

/**
 * RetiredForagerBee extends ForagerBee
 *
 * age 40, job "gamble", color "grey", canFly false
 * forage() returns "I am too old, let me play cards instead"
 * gamble(treasure) pushes the treasure into treasureChest (it always wins)
 */
class RetiredForagerBee {
  // your code here
}

// Leave these lines alone. They make the classes visible to the tests.
module.exports = { Grub, Bee, HoneyMakerBee, ForagerBee, RetiredForagerBee };
})();
