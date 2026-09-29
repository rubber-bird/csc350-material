# JavaScript Fundamentals

Sixteen exercises from arithmetic to a JSON parser, ordered from easiest to
hardest. Nothing to install and nothing to run: open `index.html` in a
browser. That is the only entry point, and it runs every test.

## How it works

Each exercise is one `.js` file in `exercises/`, full of small functions with
`// your code here` inside. Every function has a comment explaining the task:
description, **Input**, **Output**, and **Examples**. Its tests live in
`tests/` and run at the bottom of `index.html`. Edit, save, refresh.

One exercise is different. In **05 Testbuilder** you write the function
*and* its tests: edit both `exercises/05-testbuilder.js` and
`tests/05-testbuilder.test.js`.

In **08 Koans** you do not write functions. Each function runs some code
and returns your predictions; replace every `FILL_ME_IN` with the value
you think the code produces.

## The order

| # | File | Topic |
|---|------|-------|
| 01 | `01-numbers.js` | numbers and arithmetic |
| 02 | `02-strings.js` | strings |
| 03 | `03-conditionals.js` | if / else, comparison |
| 04 | `04-loops.js` | for / while loops |
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
| 16 | `16-recursion-json-dom.js` | walk the DOM tree, rewrite JSON.stringify and JSON.parse |

All paths are inside `exercises/`.

## Running the tests

Open `index.html`. The results are at the bottom of the page, one collapsed
block per module with its pass and fail counts in the header. Click a module
to expand it; the browser remembers which ones you left open. Inside, every
test appears block by block in the order it runs.

Exercise 14 (DOM) also has `playground.html`, linked from `index.html`, which
shows your DOM functions working on a real page.

## Reading a failing test

```
✗ add(2, 3) -> 5
    Expected: 5
    Received: undefined
```

"Received: undefined" almost always means the function has no `return` yet.

## After this

Server-side work (Node and Express) lives in the separate `node-express`
project next to this one.

## Under the hood

- `index.html` loads the [Mocha](https://mochajs.org) test runner and the
  [Chai](https://www.chaijs.com) assertion library from a CDN, then loads the
  exercise files and their tests. It needs internet for the CDN.
- `solutions/` mirrors `exercises/` with reference answers (plus the
  completed test file for 05). Remove it before sharing with students.
