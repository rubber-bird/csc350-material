describe("12 - Bees: pseudoclassical", () => {
  const ex = require("../exercises/12-bees-pseudoclassical");

  // Checks that a constructor is written in the pseudoclassical style:
  // a plain function that uses `this`, with methods on its prototype.
  const followsPseudoclassicalPattern = (name) => {
    describe("follows the pseudoclassical pattern", () => {
      const Ctor = () => ex[name];
      const methodNames = (instance) => {
        const names = [];
        for (const key in instance) {
          if (typeof instance[key] === "function") names.push(key);
        }
        return names;
      };

      it("is a constructor function, not a class", () => {
        expect(Ctor()).to.be.a("function");
        expect(/^class\b/.test(Ctor().toString())).to.equal(false);
      });
      it("makes instances that delegate to its .prototype", () => {
        expect(Ctor().prototype.isPrototypeOf(new (Ctor())())).to.equal(true);
      });
      it("has a .prototype.constructor that points back to itself", () => {
        expect(Ctor().prototype.constructor).to.equal(Ctor());
      });
      it("references the keyword this", () => {
        expect(/this/.test(Ctor().toString())).to.equal(true);
      });
      it("does not put properties on the constructor function itself", () => {
        expect(Object.keys(Ctor())).to.have.length(0);
      });
      it("extends its .prototype", () => {
        expect(Object.keys(Ctor().prototype).length).to.be.above(0);
      });
      it("does not store methods on the instance itself", () => {
        const instance = new (Ctor())();
        methodNames(instance).forEach((m) => {
          expect(instance.hasOwnProperty(m), m + " should live on a prototype").to.equal(false);
        });
      });
      it("reuses methods across instances", () => {
        const a = new (Ctor())();
        const b = new (Ctor())();
        methodNames(a).forEach((m) => {
          expect(a[m]).to.equal(b[m]);
        });
      });
      it("does not share non-function objects across instances", () => {
        const a = new (Ctor())();
        const b = new (Ctor())();
        for (const key in a) {
          if (a[key] && typeof a[key] === "object") {
            expect(a[key], key + " should be a new object per instance").to.not.equal(b[key]);
          }
        }
      });
    });
  };

  describe("Grub", () => {
    followsPseudoclassicalPattern("Grub");
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
    it("has an `eat` method on the prototype", () => {
      expect(ex.Grub.prototype.eat).to.be.a("function");
    });
    it("eats jelly", () => {
      expect(grub.eat()).to.equal("Mmmmmmmmm jelly");
    });
  });

  describe("Bee", () => {
    followsPseudoclassicalPattern("Bee");
    let bee;
    beforeEach(() => { bee = new ex.Bee(); });

    it("inherits from Grub", () => {
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
      expect(bee.eat).to.equal(ex.Grub.prototype.eat);
    });
    it("has a `job` property set to 'keep on growing'", () => {
      expect(bee.job).to.equal("keep on growing");
    });
  });

  describe("HoneyMakerBee", () => {
    followsPseudoclassicalPattern("HoneyMakerBee");
    let honeyBee;
    beforeEach(() => { honeyBee = new ex.HoneyMakerBee(); });

    it("inherits from Bee", () => {
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
  });

  describe("ForagerBee", () => {
    followsPseudoclassicalPattern("ForagerBee");
    let foragerBee;
    beforeEach(() => { foragerBee = new ex.ForagerBee(); });

    it("inherits from Bee", () => {
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
  });

  describe("RetiredForagerBee", () => {
    followsPseudoclassicalPattern("RetiredForagerBee");
    let retired;
    beforeEach(() => { retired = new ex.RetiredForagerBee(); });

    it("inherits from ForagerBee", () => {
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
