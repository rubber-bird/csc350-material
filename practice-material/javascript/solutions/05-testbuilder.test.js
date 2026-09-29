// ============================================================
//  05 - Testbuilder: the tests (solution)
// ============================================================

describe("05 - Testbuilder", function() {
  const detectNetwork = require("../exercises/05-testbuilder").detectNetwork;
  const expect = chai.expect;

  // Pads a prefix with zeros up to the wanted length.
  const card = (prefix, length) => String(prefix) + "0".repeat(length - String(prefix).length);

  // Generates one test per prefix and length combination.
  const cover = (network, prefixes, lengths) => {
    prefixes.forEach((prefix) => {
      lengths.forEach((length) => {
        it("has a prefix of " + prefix + " and a length of " + length, function() {
          expect(detectNetwork(card(prefix, length))).to.equal(network);
        });
      });
    });
  };

  const range = (low, high) => Array.from({ length: high - low + 1 }, (v, i) => low + i);

  describe("Introduction to Mocha tests - READ ME FIRST", function() {
    it("doesn't throw an error, so it doesn't fail", function() {
      var even = function(num) {
        return num % 2 === 0;
      };
      return even(10) === true;
    });

    it("throws an error when expected behaviour does not match actual behaviour", function() {
      var even = function(num) {
        return num % 2 === 0;
      };
      if (even(10) !== true) {
        throw new Error("10 should be even!");
      }
    });
  });

  describe("Diner's Club", function() {
    cover("Diner's Club", [38, 39], [14]);
  });

  describe("American Express", function() {
    cover("American Express", [34, 37], [15]);
  });

  describe("Visa", function() {
    cover("Visa", [4], [13, 16, 19]);
  });

  describe("MasterCard", function() {
    cover("MasterCard", [51, 52, 53, 54, 55], [16]);
  });

  describe("Discover", function() {
    cover("Discover", [6011, 644, 645, 646, 647, 648, 649, 65], [16, 19]);
  });

  describe("Maestro", function() {
    cover("Maestro", [5018, 5020, 5038, 6304], range(12, 19));
  });

  describe("China UnionPay", function() {
    cover("China UnionPay", [622126, 622500, 622925, 624, 625, 626, 6282, 6285, 6288], range(16, 19));
  });

  describe("Switch", function() {
    cover("Switch", [4903, 4905, 4911, 4936, 564182, 633110, 6333, 6759], [16, 18, 19]);

    it("prefers Switch over Visa when both match", function() {
      expect(detectNetwork(card(4903, 16))).to.equal("Switch");
    });

    it("does not detect Switch for a length of 17", function() {
      expect(detectNetwork(card(4903, 17))).to.not.equal("Switch");
    });
  });
});
