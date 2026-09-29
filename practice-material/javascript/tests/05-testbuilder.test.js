// ============================================================
//  05 - Testbuilder: the tests
// ============================================================
//
//  You edit this file. Read it from top to bottom. Some tests are
//  written for you, some have bugs to fix, and some are blank for you
//  to write. Replace every FILL_ME_IN.
//
//  A Mocha test is just a function. If it throws an error, it fails.
//  If it does not throw, it passes. Read more at mochajs.org.
//
// ============================================================

describe("05 - Testbuilder", function() {
  const detectNetwork = require("../exercises/05-testbuilder").detectNetwork;

  // You don't fill in *this* value. It marks the spots below that you do.
  const FILL_ME_IN = "Fill this value in";

  describe("Introduction to Mocha tests - READ ME FIRST", function() {
    // Once you have read and understood this section, delete the failing
    // test. You will not want to leave failing tests behind.

    it("throws an error so it fails", function() {
      throw new Error("Delete me!");
    });

    it("doesn't throw an error, so it doesn't fail", function() {
      // This test doesn't really test anything at all! It passes no matter what.
      var even = function(num) {
        return num / 2 === 0;
      };
      return even(10) === true;
    });

    // In tests we compare the expected behaviour to the actual behaviour.
    // A test should only fail if the two do not match.
    // Be careful, tests can have bugs too...
    it("throws an error when expected behaviour does not match actual behaviour", function() {
      var even = function(num) {
        return num / 2 === 0;
      };

      if (even(10) !== true) {
        throw new Error("10 should be even!");
      }
    });
  });

  describe("Diner's Club", function() {
    // Be careful, tests can have bugs too...

    it("has a prefix of 38 and a length of 14", function() {
      throw new Error("Delete me!");
      if (detectNetwork("38345678901234") !== "Diner's Club") {
        throw new Error("Test failed");
      }
    });

    it("has a prefix of 39 and a length of 14", function() {
      if (detectNetwork("3934567890123") !== "Diner's Club") {
        throw new Error("Test failed");
      }
    });
  });

  describe("American Express", function() {
    // It gets annoying to keep typing the if/throw, so here is a helper
    // that throws an error if the statement it is given isn't true.
    var assert = function(isTrue) {
      if (isTrue) {
        throw new Error("Test failed");
      }
    };

    it("has a prefix of 34 and a length of 15", function() {
      assert(detectNetwork("343456789012345") === "American Express");
    });

    it("has a prefix of 37 and a length of 15", function() {
      assert(detectNetwork("373456789012345") === "American Express");
    });
  });

  describe("Visa", function() {
    // Chai is an entire library of helpers for tests!
    // Chai provides an assert that acts the same as our previous assert.
    // Search the documentation to find how to access it.
    //   http://chaijs.com/
    var assert = chai.FILL_ME_IN;

    it("has a prefix of 4 and a length of 13", function() {
      assert(detectNetwork("4123456789012") === "Visa");
    });

    it("has a prefix of 4 and a length of 16", function() {
      assert(detectNetwork("4123456789012345") === "Visa");
    });

    it("has a prefix of 4 and a length of 19", function() {
      assert(detectNetwork("4123456789012345678") === "Visa");
    });
  });

  describe("MasterCard", function() {
    // Chai lets you write more human-readable tests that throw helpful
    // errors. The expect syntax is one way to do this. This is the style
    // every other test file in this project uses.
    //   http://chaijs.com/api/bdd/
    var expect = chai.expect;

    it(FILL_ME_IN, function() {
      expect(detectNetwork("5112345678901234")).to.equal("MasterCard");
    });
    it(FILL_ME_IN, function() {
      expect(detectNetwork("5212345678901234")).to.equal("MasterCard");
    });
    it(FILL_ME_IN, function() {
      expect(detectNetwork("5312345678901234")).to.equal("MasterCard");
    });

    // You can also use should instead of expect, which changes the style
    // slightly. It doesn't matter which one you use, see
    // http://chaijs.com/guide/styles/, but it is important to be
    // consistent. Once these pass, rewrite them to use expect like the
    // ones above, so this file uses one style only.
    var should = chai.should();

    it("has a prefix of 54 and a length of 16", function() {
      detectNetwork("5412345678901234").should.equal(FILL_ME_IN);
    });
    it("has a prefix of 55 and a length of 16", function() {
      detectNetwork("5512345678901234").should.equal(FILL_ME_IN);
    });
  });

  describe("Discover", function() {
    // A test without a function is marked "pending" and not run.
    // Write these tests (and the rest for Discover) and make them pass.
    // Discover: prefix 6011, 644-649 or 65, length 16 or 19.
    it("has a prefix of 6011 and a length of 16");
    it("has a prefix of 6011 and a length of 19");
  });

  describe("Maestro", function() {
    // Write full test coverage for Maestro: every prefix with every length.
    // Maestro: prefix 5018, 5020, 5038 or 6304, length 12 to 19.
    //
    // Writing all of those by hand is repetitive. Loops can generate tests:
    //
    //   for (let length = 12; length <= 19; length++) {
    //     it("has a prefix of 5018 and a length of " + length, function() {
    //       ...
    //     });
    //   }
  });

  describe("China UnionPay", function() {
    // Write full test coverage for China UnionPay.
    // Prefix 622126-622925, 624-626 or 6282-6288, length 16 to 19.
  });

  describe("Switch", function() {
    // Write full test coverage for Switch.
    // Prefix 4903, 4905, 4911, 4936, 564182, 633110, 6333 or 6759,
    // length 16, 18 or 19.
    //
    // Heads up: Switch and Visa overlap. 4903... also starts with 4. When
    // two networks match, detectNetwork should pick the longer prefix.
    // Write a test that proves a 4903 card of length 16 is Switch, not Visa.
  });
});
