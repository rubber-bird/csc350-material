describe("11 - Bees: ES6 classes", () => {
  const ex = require("../exercises/11-bees-es6-classes");

  describe("Grub", () => {
    let grub;
    beforeEach(() => { grub = new ex.Grub(); });

    it("has an `age` property set to 0", () => {
      expect(grub.age).to.equal(0);
    });
    it("has a `color` property set to 'pink'", () => {
      expect(grub.color).to.equal("pink");
    });
    it("has a `food` property set to 'jelly'", () => {
      expect(grub.food).to.equal("jelly");
    });
    it("has an `eat` method", () => {
      expect(grub.eat).to.be.a("function");
    });
    it("eats jelly", () => {
      expect(grub.eat()).to.equal("Mmmmmmmmm jelly");
    });
  });

  describe("Bee", () => {
    let bee;
    beforeEach(() => { bee = new ex.Bee(); });

    it("is a subclass of Grub", () => {
      expect(bee).to.be.an.instanceof(ex.Grub);
    });
    it("overrides `age` to 5", () => {
      expect(bee.age).to.equal(5);
    });
    it("overrides `color` to 'yellow'", () => {
      expect(bee.color).to.equal("yellow");
    });
    it("inherits `food` from Grub", () => {
      expect(bee.food).to.equal("jelly");
    });
    it("inherits `eat` from Grub", () => {
      expect(bee.eat).to.be.a("function");
      expect(bee.eat).to.equal(ex.Grub.prototype.eat);
    });
    it("has a `job` property set to 'Keep on growing'", () => {
      expect(bee.job).to.equal("Keep on growing");
    });
  });

  describe("HoneyMakerBee", () => {
    let honeyBee;
    beforeEach(() => { honeyBee = new ex.HoneyMakerBee(); });

    it("is a subclass of Bee", () => {
      expect(honeyBee).to.be.an.instanceof(ex.Bee);
    });
    it("overrides `age` to 10", () => {
      expect(honeyBee.age).to.equal(10);
    });
    it("overrides `job` to 'make honey'", () => {
      expect(honeyBee.job).to.equal("make honey");
    });
    it("inherits `color` from Bee", () => {
      expect(honeyBee.color).to.equal("yellow");
    });
    it("inherits `food` from Grub", () => {
      expect(honeyBee.food).to.equal("jelly");
    });
    it("inherits `eat` from Grub", () => {
      expect(honeyBee.eat).to.be.a("function");
    });
    it("has a `honeyPot` property set to 0", () => {
      expect(honeyBee.honeyPot).to.equal(0);
    });
    it("has a `makeHoney` method that adds 1 to the honeyPot", () => {
      expect(honeyBee.makeHoney).to.be.a("function");
      honeyBee.makeHoney();
      expect(honeyBee.honeyPot).to.equal(1);
      honeyBee.makeHoney();
      expect(honeyBee.honeyPot).to.equal(2);
    });
    it("has a `giveHoney` method that subtracts 1 from the honeyPot", () => {
      expect(honeyBee.giveHoney).to.be.a("function");
      honeyBee.makeHoney();
      honeyBee.makeHoney();
      honeyBee.makeHoney();
      honeyBee.giveHoney();
      expect(honeyBee.honeyPot).to.equal(2);
    });
    it("keeps a separate honeyPot per bee", () => {
      const other = new ex.HoneyMakerBee();
      honeyBee.makeHoney();
      expect(other.honeyPot).to.equal(0);
    });
  });

  describe("ForagerBee", () => {
    let foragerBee;
    beforeEach(() => { foragerBee = new ex.ForagerBee(); });

    it("is a subclass of Bee", () => {
      expect(foragerBee).to.be.an.instanceof(ex.Bee);
    });
    it("overrides `age` to 10", () => {
      expect(foragerBee.age).to.equal(10);
    });
    it("overrides `job` to 'find pollen'", () => {
      expect(foragerBee.job).to.equal("find pollen");
    });
    it("inherits `color` from Bee", () => {
      expect(foragerBee.color).to.equal("yellow");
    });
    it("inherits `food` from Grub", () => {
      expect(foragerBee.food).to.equal("jelly");
    });
    it("inherits `eat` from Grub", () => {
      expect(foragerBee.eat).to.be.a("function");
    });
    it("has a `canFly` property set to true", () => {
      expect(foragerBee.canFly).to.equal(true);
    });
    it("has a `treasureChest` property set to an empty array", () => {
      expect(foragerBee.treasureChest).to.deep.equal([]);
    });
    it("has a `forage` method that adds a treasure to the treasureChest", () => {
      foragerBee.forage("pollen");
      foragerBee.forage("flowers");
      foragerBee.forage("gold");
      expect(foragerBee.treasureChest).to.deep.equal(["pollen", "flowers", "gold"]);
    });
    it("keeps a separate treasureChest per bee", () => {
      const other = new ex.ForagerBee();
      foragerBee.forage("pollen");
      expect(other.treasureChest).to.deep.equal([]);
    });
  });

  describe("RetiredForagerBee", () => {
    let retired;
    beforeEach(() => { retired = new ex.RetiredForagerBee(); });

    it("is a subclass of ForagerBee", () => {
      expect(retired).to.be.an.instanceof(ex.ForagerBee);
    });
    it("overrides `age` to 40", () => {
      expect(retired.age).to.equal(40);
    });
    it("overrides `job` to 'gamble'", () => {
      expect(retired.job).to.equal("gamble");
    });
    it("overrides `canFly` to false", () => {
      expect(retired.canFly).to.equal(false);
    });
    it("overrides `color` to 'grey'", () => {
      expect(retired.color).to.equal("grey");
    });
    it("overrides `forage` to refuse", () => {
      expect(retired.forage()).to.equal("I am too old, let me play cards instead");
    });
    it("inherits `food` from Grub", () => {
      expect(retired.food).to.equal("jelly");
    });
    it("inherits `eat` from Grub", () => {
      expect(retired.eat).to.be.a("function");
    });
    it("inherits `treasureChest` from ForagerBee, set to an empty array", () => {
      expect(retired.treasureChest).to.deep.equal([]);
    });
    it("has an always-winning `gamble` method that adds a treasure to the treasureChest", () => {
      retired.gamble("chips");
      retired.gamble("more chips");
      expect(retired.treasureChest).to.have.length(2);
    });
  });
});
