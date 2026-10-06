# JavaScript Fundamentals

Sixteen `.js` files of small functions to fill in, from arithmetic to a JSON parser. Nothing to install: open `index.html` in a browser and it runs every test (it loads Mocha and Chai from a CDN, so it needs internet).

## How it works

- Each file in `exercises/` is a list of functions with `// your code here` inside. The comment above each one gives the task, its **Input**, **Output** and **Examples**. Edit, save, refresh.
- Results are at the bottom of `index.html`, one collapsible block per module.
- **05 Testbuilder** is different: you write the function *and* its tests, in `exercises/05-testbuilder.js` and `tests/05-testbuilder.test.js`.
- **08 Koans** has no functions to write. Replace every `FILL_ME_IN` with the value you think the code produces.
- **14 DOM** has `playground.html`, linked from `index.html`, which shows your DOM functions on a real page.

## The modules

| # | File | Covers |
|---|------|-------|
| 01 | `01-numbers.js` | numbers and arithmetic |
| 02 | `02-strings.js` | strings |
| 03 | `03-conditionals.js` | `if` / `else`, comparison |
| 04 | `04-loops.js` | `for` / `while` loops |
| 05 | `05-testbuilder.js` | detect a credit card network, and write the Mocha tests yourself |
| 06 | `06-arrays.js` | arrays |
| 07 | `07-objects.js` | objects |
| 08 | `08-koans.js` | fill-in-the-blank language koans |
| 09 | `09-real-data.js` | users, products, orders, students |
| 10 | `10-functions.js` | functions as values, closures |
| 11 | `11-bees-es6-classes.js` | inheritance with `class` |
| 12 | `12-bees-pseudoclassical.js` | inheritance with constructor functions and prototypes |
| 13 | `13-challenges.js` | mixed problems, algorithms |
| 14 | `14-dom.js` | the DOM: elements, classes, events, widgets |
| 15 | `15-recursion.js` | 40 recursion prompts, loops forbidden |
| 16 | `16-recursion-json-dom.js` | walk the DOM tree, rewrite `JSON.stringify` and `JSON.parse` |

## Reading a failing test

```
✗ add(2, 3) -> 5
    Expected: 5
    Received: undefined
```

"Received: undefined" almost always means the function has no `return` yet.

## Under the hood

- `index.html` loads the [Mocha](https://mochajs.org) test runner and the [Chai](https://www.chaijs.com) assertion library, then the exercise files and their tests.
- `solutions/` mirrors `exercises/` with reference answers (plus the completed test file for 05). Remove it before sharing with students.
