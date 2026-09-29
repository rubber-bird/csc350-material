// ============================================================
//  05 - Testbuilder
// ============================================================
//
//  This exercise has two jobs:
//    1. Write detectNetwork below.
//    2. Write the tests for it in tests/05-testbuilder.test.js.
//       Unlike the other test files, you edit that one. Some tests are
//       written for you, some have bugs, and some are missing.
//
//  Given a credit card number (always a string), return the name of the
//  network it belongs to. Two things identify a network:
//    1. the first few digits (the prefix)
//    2. how many digits there are (the length)
//
//  Warning: regular expressions are NOT allowed here.
//
//  The networks, in the order you should add them:
//
//    Diner's Club      prefix 38 or 39                        length 14
//    American Express  prefix 34 or 37                        length 15
//    Visa              prefix 4                               length 13, 16 or 19
//    MasterCard        prefix 51, 52, 53, 54 or 55            length 16
//    Discover          prefix 6011, 644-649 or 65             length 16 or 19
//    Maestro           prefix 5018, 5020, 5038 or 6304        length 12 to 19
//    China UnionPay    prefix 622126-622925, 624-626, 6282-6288
//                                                             length 16 to 19
//    Switch            prefix 4903, 4905, 4911, 4936, 564182,
//                             633110, 6333 or 6759            length 16, 18 or 19
//
//  Switch and Visa overlap: 4903... also starts with 4. When two networks
//  match, choose the one with the longer prefix.
//
//  Add a network, write its tests, make them pass, then add the next.
//  Run the tests:   open index.html in a browser
//
// ============================================================

/**
 * detectNetwork(cardNumber)
 *
 * Input:   cardNumber - a string of digits
 * Output:  the network name as a string, for example "MasterCard"
 *
 * Examples:
 *   detectNetwork("38345678901234")   ->  "Diner's Club"
 *   detectNetwork("343456789012345")  ->  "American Express"
 */
function detectNetwork(cardNumber) {
  // your code here
}

// Leave this line alone. It makes the function visible to the tests.
module.exports = { detectNetwork };
