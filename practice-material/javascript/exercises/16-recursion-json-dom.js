// ============================================================
//  16 - Recursion on JSON and the DOM
// ============================================================
//
//  Three recursive problems that show where recursion is used for real:
//  walking a tree of HTML elements, and turning data into text and back.
//
//    1. getElementsByClassName   walk the DOM tree
//    2. stringifyJSON            rewrite JSON.stringify
//    3. parseJSON                rewrite JSON.parse (the hardest)
//
//  These functions only work in the browser. Run the tests with index.html.
//  Do them in order.
//
// ============================================================

// ---------------------------------------------- 1 -----------

/**
 * getElementsByClassName(className)
 *
 * Return every element on the page that has the given class, in document
 * order. The easy way would be document.getElementsByClassName(className),
 * but you are going to write it from scratch: start at document.body,
 * check the element, then recurse into each of its children.
 *
 * You may use:   element.classList.contains(name)
 *                element.children   (the child elements)
 *                document.body
 * You may not:   document.getElementsByClassName, querySelector,
 *                querySelectorAll
 *
 * Input:   className - a string
 * Output:  an array of elements (a real array, not a NodeList)
 *
 * Example:  <body class="x"><div class="x"><p class="x"></p></div></body>
 *   getElementsByClassName("x")  ->  [body, div, p]
 */
function getElementsByClassName(className) {
  // your code here
}

// ---------------------------------------------- 2 -----------

/**
 * stringifyJSON(value)
 *
 * Turn a value into its JSON text, exactly like JSON.stringify does.
 * Handle numbers, strings, booleans, null, arrays and objects. Nested
 * arrays and objects need recursion. JSON has no spaces between items.
 *
 * Rules that JSON.stringify follows:
 *   - strings are wrapped in double quotes
 *   - inside arrays, undefined and functions become null
 *   - inside objects, keys with undefined or function values are skipped
 *   - undefined on its own returns undefined (not a string)
 *
 * Input:   value - anything
 * Output:  a string
 *
 * Examples:
 *   stringifyJSON(9)                  ->  "9"
 *   stringifyJSON("hi")               ->  "\"hi\""
 *   stringifyJSON([1, "a", null])     ->  "[1,\"a\",null]"
 *   stringifyJSON({ a: [true] })      ->  "{\"a\":[true]}"
 */
function stringifyJSON(value) {
  // your code here
}

// ---------------------------------------------- 3 -----------

/**
 * parseJSON(json)
 *
 * Turn JSON text back into a value, exactly like JSON.parse does.
 * Invalid JSON must throw a SyntaxError.
 *
 * This is the hardest one. Sketch the grammar before coding: a value is
 * one of string, number, object, array, true, false, null. Write a small
 * helper for each kind that reads characters from a shared position index
 * and moves it forward. Skip whitespace (spaces, tabs, newlines) between
 * tokens. Strings can contain escapes: \" \\ \/ \b \f \n \r \t \uXXXX
 *
 * Input:   json - a string
 * Output:  the value it describes
 *
 * Examples:
 *   parseJSON("[]")                    ->  []
 *   parseJSON('{"a": [1, "b"]}')       ->  { a: [1, "b"] }
 *   parseJSON('[1, 2')                 ->  throws SyntaxError
 */
function parseJSON(json) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = { getElementsByClassName, stringifyJSON, parseJSON };
