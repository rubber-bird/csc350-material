// ============================================================
//  16 - Recursion on JSON and the DOM (solution)
// ============================================================

function getElementsByClassName(className) {
  const result = [];
  const walk = (element) => {
    if (element.classList.contains(className)) {
      result.push(element);
    }
    for (const child of element.children) {
      walk(child);
    }
  };
  walk(document.body);
  return result;
}

function stringifyJSON(value) {
  if (value === null) return "null";
  if (typeof value === "boolean" || typeof value === "number") return String(value);
  if (typeof value === "string") {
    return '"' + value.replace(/\\/g, "\\\\").replace(/"/g, '\\"') + '"';
  }
  if (Array.isArray(value)) {
    const items = value.map((item) => {
      const text = stringifyJSON(item);
      return text === undefined ? "null" : text;
    });
    return "[" + items.join(",") + "]";
  }
  if (typeof value === "object") {
    const pairs = [];
    for (const key in value) {
      const text = stringifyJSON(value[key]);
      if (text !== undefined) {
        pairs.push(stringifyJSON(key) + ":" + text);
      }
    }
    return "{" + pairs.join(",") + "}";
  }
  return undefined; // functions and undefined
}

function parseJSON(json) {
  let pos = 0;

  const fail = (what) => {
    throw new SyntaxError("Unexpected " + what + " at position " + pos);
  };
  const skipWhitespace = () => {
    while (json[pos] === " " || json[pos] === "\n" || json[pos] === "\r" || json[pos] === "\t") pos++;
  };
  const expectChar = (ch) => {
    if (json[pos] !== ch) fail(json[pos] === undefined ? "end of input" : "'" + json[pos] + "'");
    pos++;
  };

  const parseValue = () => {
    skipWhitespace();
    const ch = json[pos];
    if (ch === "{") return parseObject();
    if (ch === "[") return parseArray();
    if (ch === '"') return parseString();
    if (ch === "-" || (ch >= "0" && ch <= "9")) return parseNumber();
    if (json.startsWith("true", pos)) { pos += 4; return true; }
    if (json.startsWith("false", pos)) { pos += 5; return false; }
    if (json.startsWith("null", pos)) { pos += 4; return null; }
    fail(ch === undefined ? "end of input" : "'" + ch + "'");
  };

  const parseObject = () => {
    const result = {};
    expectChar("{");
    skipWhitespace();
    if (json[pos] === "}") { pos++; return result; }
    while (true) {
      skipWhitespace();
      const key = parseString();
      skipWhitespace();
      expectChar(":");
      result[key] = parseValue();
      skipWhitespace();
      if (json[pos] === ",") { pos++; continue; }
      expectChar("}");
      return result;
    }
  };

  const parseArray = () => {
    const result = [];
    expectChar("[");
    skipWhitespace();
    if (json[pos] === "]") { pos++; return result; }
    while (true) {
      result.push(parseValue());
      skipWhitespace();
      if (json[pos] === ",") { pos++; continue; }
      expectChar("]");
      return result;
    }
  };

  const escapes = { '"': '"', "\\": "\\", "/": "/", b: "\b", f: "\f", n: "\n", r: "\r", t: "\t" };
  const parseString = () => {
    expectChar('"');
    let result = "";
    while (json[pos] !== '"') {
      if (pos >= json.length) fail("end of input");
      if (json[pos] === "\\") {
        pos++;
        const esc = json[pos];
        if (esc === "u") {
          result += String.fromCharCode(parseInt(json.slice(pos + 1, pos + 5), 16));
          pos += 5;
        } else if (esc in escapes) {
          result += escapes[esc];
          pos++;
        } else {
          fail("escape '\\" + esc + "'");
        }
      } else {
        result += json[pos++];
      }
    }
    pos++; // closing quote
    return result;
  };

  const parseNumber = () => {
    const start = pos;
    if (json[pos] === "-") pos++;
    while (json[pos] >= "0" && json[pos] <= "9") pos++;
    if (json[pos] === ".") { pos++; while (json[pos] >= "0" && json[pos] <= "9") pos++; }
    if (json[pos] === "e" || json[pos] === "E") {
      pos++;
      if (json[pos] === "+" || json[pos] === "-") pos++;
      while (json[pos] >= "0" && json[pos] <= "9") pos++;
    }
    const text = json.slice(start, pos);
    if (text === "-" || text === "") fail("number");
    return Number(text);
  };

  const value = parseValue();
  skipWhitespace();
  if (pos < json.length) fail("'" + json[pos] + "'");
  return value;
}

module.exports = { getElementsByClassName, stringifyJSON, parseJSON };
