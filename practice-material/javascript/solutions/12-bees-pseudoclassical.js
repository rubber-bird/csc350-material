// ============================================================
//  12 - Bees: pseudoclassical style (solution)
// ============================================================

(function() {

var Grub = function() {
  this.age = 0;
  this.color = "pink";
  this.food = "jelly";
};

Grub.prototype.eat = function() {
  return "Mmmmmmmmm " + this.food;
};

var Bee = function() {
  Grub.call(this);
  this.age = 5;
  this.color = "yellow";
  this.job = "keep on growing";
};

Bee.prototype = Object.create(Grub.prototype);
Bee.prototype.constructor = Bee;

var HoneyMakerBee = function() {
  Bee.call(this);
  this.age = 10;
  this.job = "make honey";
  this.honeyPot = 0;
};

HoneyMakerBee.prototype = Object.create(Bee.prototype);
HoneyMakerBee.prototype.constructor = HoneyMakerBee;

HoneyMakerBee.prototype.makeHoney = function() {
  this.honeyPot += 1;
};

HoneyMakerBee.prototype.giveHoney = function() {
  this.honeyPot -= 1;
};

var ForagerBee = function() {
  Bee.call(this);
  this.age = 10;
  this.job = "find pollen";
  this.canFly = true;
  this.treasureChest = [];
};

ForagerBee.prototype = Object.create(Bee.prototype);
ForagerBee.prototype.constructor = ForagerBee;

ForagerBee.prototype.forage = function(treasure) {
  this.treasureChest.push(treasure);
};

var RetiredForagerBee = function() {
  ForagerBee.call(this);
  this.age = 40;
  this.job = "gamble";
  this.color = "grey";
  this.canFly = false;
};

RetiredForagerBee.prototype = Object.create(ForagerBee.prototype);
RetiredForagerBee.prototype.constructor = RetiredForagerBee;

RetiredForagerBee.prototype.forage = function() {
  return "I am too old, let me play cards instead";
};

RetiredForagerBee.prototype.gamble = function(treasure) {
  // Retired bees always win. Call the parent's forage on this bee.
  ForagerBee.prototype.forage.call(this, treasure);
};

module.exports = { Grub, Bee, HoneyMakerBee, ForagerBee, RetiredForagerBee };
})();
