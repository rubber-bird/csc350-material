// These tests need a real web page. Open index.html.
describe("16 - Recursion on JSON and the DOM", () => {
  const ex = require("../exercises/16-recursion-json-dom");

  describe("getElementsByClassName", () => {
    const htmlStrings = [
      '<div class="targetClassName"></div>',
      '<div class="otherClassName targetClassName"></div>',
      '<div><div class="targetClassName"></div></div>',
      '<div><div class="targetClassName"><div class="targetClassName"></div></div></div>',
      '<div><div></div><div><div class="targetClassName"></div></div></div>',
      '<div><div class="targetClassName"></div><div class="targetClassName"></div></div>',
      '<div><div class="somediv"><div class="innerdiv"><span class="targetClassName">yay</span></div></div></div>',
    ];

    let root;
    beforeEach(() => {
      root = document.createElement("div");
      document.body.appendChild(root);
      document.body.classList.add("targetClassName");
    });
    afterEach(() => {
      root.remove();
      document.body.classList.remove("targetClassName");
    });

    it("returns an array", () => {
      expect(Array.isArray(ex.getElementsByClassName("targetClassName"))).to.equal(true);
    });

    it("does not call the built-in finders", () => {
      const src = ex.getElementsByClassName.toString();
      expect(/getElementsByClassName\s*\(|querySelector/.test(src.slice(src.indexOf("{")))).to.equal(false);
    });

    htmlStrings.forEach((html) => {
      it("matches the built-in result for " + html, () => {
        root.innerHTML = html;
        const expected = Array.from(document.getElementsByClassName("targetClassName"));
        const result = ex.getElementsByClassName("targetClassName");
        expect(result).to.have.length(expected.length);
        expected.forEach((el, i) => {
          expect(result[i]).to.equal(el); // same elements, in document order
        });
      });
    });
  });

  describe("stringifyJSON", () => {
    const stringifiableValues = [
      9,
      null,
      true,
      false,
      "Hello world",
      [],
      [8],
      ["hi"],
      [8, "hi"],
      [1, 0, -1, -0.3, 0.3, 1343.32, 3345, 0.00011999999999999999],
      [8, [[], 3, 4]],
      [[[["foo"]]]],
      {},
      { a: "apple" },
      { foo: true, bar: false, baz: null },
      { "boolean, true": true, "boolean, false": false, null: null },
      { a: { b: "c" } },
      { a: ["b", "c"] },
      [{ a: "b" }, { c: "d" }],
      { a: [], c: {}, b: true },
    ];

    stringifiableValues.forEach((value) => {
      it("matches JSON.stringify for " + JSON.stringify(value), () => {
        expect(ex.stringifyJSON(value)).to.equal(JSON.stringify(value));
      });
    });

    it("skips keys whose value is undefined or a function", () => {
      const value = { functions: function() {}, undefined: undefined, keep: 1 };
      expect(ex.stringifyJSON(value)).to.equal(JSON.stringify(value));
    });

    it("turns undefined and functions inside arrays into null", () => {
      const value = [undefined, function() {}, 1];
      expect(ex.stringifyJSON(value)).to.equal(JSON.stringify(value));
    });
  });

  describe("parseJSON", () => {
    const parseableStrings = [
      "[]",
      '{"foo": ""}',
      "{}",
      '{"foo": "bar"}',
      '["one", "two"]',
      '{"a": "b", "c": "d"}',
      "[null,false,true]",
      '{"foo": true, "bar": false, "baz": null}',
      "[1, 0, -1, -0.3, 0.3, 1343.32, 3345, 0.00011999999999999999]",
      '{"boolean, true": true, "boolean, false": false, "null": null }',
      '{"a":{"b":"c"}}',
      '{"a":["b", "c"]}',
      '[{"a":"b"}, {"c":"d"}]',
      '{"a":[],"c": {}, "b": true}',
      '[[[["foo"]]]]',
      // escaping
      '["\\\\\\"\\"a\\""]',
      '["and you can\'t escape this"]',
      '["tab\\tnewline\\nunicode\\u0041"]',
      // everything all at once
      '{ "firstName": "John", "lastName" : "Smith", "age" : 25, "address" : ' +
        '{ "streetAddress": "21 2nd Street", "city" : "New York", "state" : "NY", "postalCode" : "10021" }, ' +
        '"phoneNumber": [ { "type" : "home", "number": "212 555-1234" }, { "type" : "fax", "number": "646 555-4567" } ] }',
      '{\r\n  "glossary": {\n    "title": "example glossary",\n\r\t\t"GlossDiv": {\r\n' +
        '      "title": "S",\r\n\t\t\t"GlossList": {\r\n        "GlossEntry": {\r\n          "ID": "SGML",\r\n' +
        '\t\t\t\t\t"SortAs": "SGML",\r\n\t\t\t\t\t"GlossTerm": "Standard Generalized Markup Language",\r\n' +
        '\t\t\t\t\t"Acronym": "SGML",\r\n\t\t\t\t\t"Abbrev": "ISO 8879:1986",\r\n\t\t\t\t\t"GlossDef": {\r\n' +
        '            "para": "A meta-markup language, used to create markup languages such as DocBook.",\r\n' +
        '\t\t\t\t\t\t"GlossSeeAlso": ["GML", "XML"]\r\n          },\r\n\t\t\t\t\t"GlossSee": "markup"\r\n' +
        "        }\r\n      }\r\n    }\r\n  }\r\n}\r\n",
    ];

    const unparseableStrings = [
      '["foo", "bar"',
      '["foo", "bar\\"]',
      "{\"a\" 1}",
      "[1,]",
      "nope",
      "",
    ];

    parseableStrings.forEach((json) => {
      it("matches JSON.parse for " + JSON.stringify(json).slice(0, 60), () => {
        expect(ex.parseJSON(json)).to.deep.equal(JSON.parse(json));
      });
    });

    unparseableStrings.forEach((json) => {
      it("throws a SyntaxError for " + JSON.stringify(json), () => {
        expect(() => ex.parseJSON(json)).to.throw(SyntaxError);
      });
    });
  });
});
