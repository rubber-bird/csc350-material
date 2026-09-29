// ============================================================
//  05 - Testbuilder (solution)
// ============================================================

function detectNetwork(cardNumber) {
  const length = cardNumber.length;
  const startsWith = (...prefixes) =>
    prefixes.some((p) => cardNumber.slice(0, String(p).length) === String(p));
  const inRange = (digits, low, high) => {
    const n = Number(cardNumber.slice(0, digits));
    return cardNumber.length >= digits && n >= low && n <= high;
  };
  const lengthIn = (...lengths) => lengths.includes(length);

  // Longer prefixes first, so Switch beats Visa.
  if (startsWith(4903, 4905, 4911, 4936, 564182, 633110, 6333, 6759) && lengthIn(16, 18, 19)) {
    return "Switch";
  }
  if ((inRange(6, 622126, 622925) || inRange(3, 624, 626) || inRange(4, 6282, 6288)) && lengthIn(16, 17, 18, 19)) {
    return "China UnionPay";
  }
  if (startsWith(5018, 5020, 5038, 6304) && length >= 12 && length <= 19) {
    return "Maestro";
  }
  if ((startsWith(6011, 65) || inRange(3, 644, 649)) && lengthIn(16, 19)) {
    return "Discover";
  }
  if (startsWith(51, 52, 53, 54, 55) && length === 16) {
    return "MasterCard";
  }
  if (startsWith(4) && lengthIn(13, 16, 19)) {
    return "Visa";
  }
  if (startsWith(34, 37) && length === 15) {
    return "American Express";
  }
  if (startsWith(38, 39) && length === 14) {
    return "Diner's Club";
  }
}

module.exports = { detectNetwork };
