<div align="center">

# JavaScript & DOM manipulation - Lecture notes

## Contents

- [Overview](#overview)
- [Core Concepts](#core-concepts)
- [Important Facts & Definitions](#important-facts--definitions)
- [Practical Examples](#practical-examples)
- [Going further](#going-further)
- [Nice to know](#nice-to-know)
- [Practice exercises](#practice-exercises)
- [Further Reading](#further-reading)

---

## Overview

HTML on its own produces **static** pages: they look and behave the same every time they load. JavaScript makes pages **dynamic**. A page with JavaScript can change over time (a different image on every visit) or react to the user (typing, clicking, moving the mouse).

Almost everything in these notes follows one pattern:

1. Something happens on the page (an **event**: a click, a mouse-over, the page finishing loading).
2. A piece of JavaScript runs in response.
3. That code finds an element on the page and changes it: its image, its size, its text, or the contents of a text box.

Step 3 is **DOM manipulation**. The DOM (Document Object Model) is the browser's in-memory model of your page, where every element is an object you can read and change. `document.getElementById(...)` is the door into it.

These notes start by writing JavaScript directly inside HTML attributes like `onclick="..."`, because that's the simplest place to start and the quickest way to see results. The [Going further](#going-further) and [Nice to know](#nice-to-know) sections show how the same things are written in current practice.

---

## Core Concepts

### Events and event handlers

An **event handler** is an HTML attribute whose value is JavaScript code to run when a particular event happens on that element.

```html
<img src="mystery.gif" alt="Mystery image"
     onmouseover="this.src='happy.gif';"
     onmouseout="this.src='mystery.gif';">
```

- `onmouseover` runs when the mouse moves onto the element.
- `onmouseout` runs when it moves off.
- `onclick` runs when the element is clicked.
- `onload` (on `<body>`) runs once the whole page has finished loading.

Inside the handler, **`this`** means "the element the event happened on". `this.src = 'happy.gif'` changes the image's own `src` attribute. You can put several statements in one handler, separated by semicolons, and they run in order.

The full list of handler attributes is on MDN under [global attributes](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Global_attributes).

### Assignment changes an attribute

The simplest action is to give an attribute a new value with `=`:

```javascript
this.src = 'happy.gif';
this.width = 200;
this.height = 200;
```

Read `=` as **"gets"**, not "equals". `x = x + 1` means "x gets x plus one". It's an instruction to store a value, not a statement that two things are equal. Every attribute you can write in HTML (`src`, `width`, `height`, `value`, ...) can be assigned from JavaScript this way.

### Finding other elements: `document.getElementById`

`this` only reaches the element the event happened on. Usually a *button* is clicked, but an *image* or a *paragraph* should change. To reach any element, give it an `id` and ask the document for it:

```html
<img id="mysteryImg" src="mystery.gif" alt="Mystery image">

<input type="button" value="Show happy"
       onclick="document.getElementById('mysteryImg').src='happy.gif';">
```

`document.getElementById('mysteryImg')` returns the element with that `id`, and then `.src=` changes it. IDs must match **exactly**, including capitalization, and each `id` must be unique on the page. If the ID doesn't match anything, you get `null` and the next `.src` produces an error ([`getElementById`](https://developer.mozilla.org/en-US/docs/Web/API/Document/getElementById)).

### Showing text: `alert` and `innerHTML`

Two ways to show a message:

| | `alert('...')` | `element.innerHTML = '...'` |
|---|---|---|
| Where it appears | a separate pop-up window | inside the page |
| Length | one short line | as long as you want |
| Formatting | plain text only | can include HTML tags (`<p>`, `<b>`, ...) |
| User has to dismiss it | yes, by clicking OK | no |

`innerHTML` is a property of text-holding elements like `<div>`, `<p>` and `<span>`. Assigning a string to it replaces everything inside the element. That makes a `<div id="outputDiv">` the standard place to print results ([`innerHTML`](https://developer.mozilla.org/en-US/docs/Web/API/Element/innerHTML), [`alert`](https://developer.mozilla.org/en-US/docs/Web/API/Window/alert)).

### Reading input: text boxes and `.value`

```html
<input type="text" id="nameBox" size="20" value="">
```

A text box's **`value`** property is whatever is currently typed in it. Read it to get the user's input; assign to it to change what's shown.

```javascript
document.getElementById('nameBox').value           // read
document.getElementById('nameBox').value = 'Dave'; // write
```

The `value="..."` attribute in the HTML sets the initial contents. `value=""` means the box starts empty. Every text box needs its own unique `id`.

### Strings, quotes, and escaping

A **string** is a sequence of characters in quotes. HTML strings and JavaScript strings end up nested inside each other, so stick to a convention: **double quotes for HTML attributes, single quotes for JavaScript strings**.

```html
onclick="document.getElementById('outputDiv').innerHTML = 'Hello there';"
```

The whole handler is a double-quoted HTML string; inside it, `'outputDiv'` and `'Hello there'` are single-quoted JavaScript strings.

The catch is apostrophes. `'I'm glad you're here'` ends the string at the `I`. Put a backslash before an apostrophe that's part of the text: `'I\'m glad you\'re here'`. The backslash is an **escape character** that says "the next character is just a character".

Strings are joined with `+`, called **concatenation**:

```javascript
'Hello ' + userName + ', welcome.'
```

Anything in quotes is literal text, even if it looks like a variable name. Anything outside quotes is evaluated. `'Hello userName'` prints the word userName; `'Hello ' + userName` prints the name stored in the variable.

### Variables

A **variable** is a name for a value that can change. Assigning stores the value; using the name later gets it back.

```javascript
userName = 'Dave';
document.getElementById('outputDiv').innerHTML = 'Hi ' + userName;
```

Names can contain letters, digits and underscores, must start with a letter, and are case-sensitive. Pick descriptive ones. Two reasons to use variables:

- **Reuse.** If a text box's contents appear five times in a message, read it once into a variable instead of writing `document.getElementById(...)` five times. Shorter and fewer chances for typos.
- **Temporary storage.** To swap two images you need a third place to hold one of them while you overwrite it (see the [swap example](#swap-two-pictures)).

The common pattern for an interactive page:

```javascript
VAR1 = document.getElementById('BOX_ID1').value;
VAR2 = document.getElementById('BOX_ID2').value;
document.getElementById('outputDiv').innerHTML = MESSAGE_BUILT_FROM_VARIABLES;
```

### Data types: strings, numbers, booleans

Every value has a **type**, and each type has its own operators. Strings have `+` for concatenation. Numbers have `+ - * /` for arithmetic. Booleans are `true` or `false`.

Facts about JavaScript numbers:

- Very large or small numbers display in scientific notation: `1e24` is 1 × 10²⁴.
- All numbers are stored in 64 bits, so there's a limit. `1e308` works; `1e309` becomes `Infinity`. `1e-323` works; `1e-324` becomes `0`.
- About 17 significant digits are kept. `0.9999999999999999` is stored exactly; add one more 9 and it rounds up to `1`.

### Text boxes always give you a string

This is the single most common bug in beginner JavaScript. Whatever the user types, `.value` returns a **string**. `'12'` is not the number 12.

```javascript
myNumber = document.getElementById('numBox').value;   // user typed 12
alert('One more is ' + (myNumber + 1));               // shows: One more is 121
```

`myNumber + 1` is string `'12'` plus number `1`. When `+` sees a string on either side, it converts the other side to a string and concatenates, so you get `'121'`. Convert first with **`parseFloat`**:

```javascript
myNumber = parseFloat(document.getElementById('numBox').value);
alert('One more is ' + (myNumber + 1));               // shows: One more is 13
```

`parseFloat('500')` gives `500`, `parseFloat('1.314')` gives `1.314`. If the box doesn't start with a number, it gives `NaN` ("Not a Number") ([`parseFloat`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/parseFloat)).

The pattern for a page that computes something:

```javascript
VAR1 = parseFloat(document.getElementById('BOX_ID1').value);
VAR2 = parseFloat(document.getElementById('BOX_ID2').value);
RESULT = EXPRESSION_USING_VAR1_AND_VAR2;
document.getElementById('outputDiv').innerHTML = MESSAGE_USING_RESULT;
```

### Functions: calling them and writing them

A **function** maps inputs to one output, like absolute value in math. **Calling** a function means writing its name followed by the inputs in parentheses. What comes back is the **return value**.

```javascript
Math.sqrt(9)          // 3
Math.max(12, 8.5)     // 12
Math.pow(2, 10)       // 1024
Math.round(3.7)       // 4
```

The point of a function is that you don't have to know *how* it works, only what it does. That's true of built-in functions like `Math.sqrt`, and it's the reason to write your own.

**User-defined functions** go in a `<script>` block in the `<head>`:

```html
<head>
  <script>
    // GenerateNumber: picks a random number in the range given by the text boxes
    // and displays it in outputDiv.
    function GenerateNumber() {
      low = parseFloat(document.getElementById('lowBox').value);
      high = parseFloat(document.getElementById('highBox').value);
      number = Math.floor(Math.random() * (high - low + 1)) + low;
      document.getElementById('outputDiv').innerHTML = 'Your lucky number is ' + number;
    }
  </script>
</head>
<body>
  <input type="button" value="Generate" onclick="GenerateNumber();">
```

A definition is the word `function`, a name, `()`, and the statements between `{` and `}`. Lines starting with `//` are comments. A good rule: **if a button needs more than one statement, put the code in a function** and have the button call it. The button stays readable and you stop fighting nested quotes ([Functions](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Functions)).

### Randomness: `Math.random`

`Math.random()` takes no inputs and returns a different **pseudo-random** number each call, in the range `[0, 1)`: zero is possible, one is not. It's called pseudo-random because it's computed by an algorithm, but the numbers are spread out well enough to use as random.

You shape the range with arithmetic, then `Math.floor` chops off the decimals to get whole numbers:

| Expression | Range |
|---|---|
| `Math.random()` | 0 up to but not including 1 |
| `2 * Math.random()` | 0 up to 2 |
| `Math.random() + 1` | 1 up to 2 |
| `9 * Math.random() + 1` | 1 up to 10 |
| `Math.floor(9 * Math.random() + 1)` | whole numbers 1, 2, ..., 9 |
| `Math.floor(Math.random() * 6) + 1` | whole numbers 1 to 6 (a die roll) |

General recipe for a whole number from `low` to `high` inclusive: `Math.floor(Math.random() * (high - low + 1)) + low` ([`Math.random`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random)).

### Doing things automatically: `onload` and `setInterval`

`<body onload="SelectImage();">` calls a function once, as soon as the page has loaded. Use it to start a page in a random state instead of waiting for a click.

`setInterval` sets a timer that calls a function over and over:

```html
<body onload="setInterval(SelectAd, 5000);">
```

The second argument is the gap in **milliseconds**. `5000` is 5 seconds; `500` would be half a second. You may see the call passed as a string, `setInterval('SelectAd()', 5000)`. Passing the function name without quotes or parentheses, as above, does the same thing and is the recommended form ([`setInterval`](https://developer.mozilla.org/en-US/docs/Web/API/Window/setInterval)).

### Three kinds of errors

| Kind | What it is | Example | Browser catches it? |
|---|---|---|---|
| **Syntax error** | a typo in the code | missing quote, misspelled function name, string broken across lines | Yes, with an error message |
| **Run-time error** | a legal operation applied to an illegal value | multiplying strings gives `NaN`, dividing by zero gives `Infinity` | Sometimes. Often it just produces `NaN` silently. |
| **Logic error** | the code runs but does the wrong thing | forgot `parseFloat`, so `12 + 1` gives `121` | No. You have to notice the wrong result. |

The classic debugging technique: put `alert(variableName)` at various points to see what values you actually have, then narrow down where things go wrong. Today you'd use `console.log(variableName)` and read it in the browser's developer tools (F12), which doesn't interrupt the page.

Two syntax errors that catch almost everyone:

```javascript
alert('This is illegal because the string is broken
       across lines');                                 // unterminated string literal

alert('This is illegal because '
      'there is no + joining the pieces');             // missing ) after argument list
```

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Static page** | looks and behaves the same every time it loads |
| **Dynamic page** | changes over time or in response to the user |
| **DOM** | Document Object Model: the browser's object tree of the page, which JavaScript reads and changes |
| **Event** | something that happens: click, mouse-over, page load |
| **Event handler** | an attribute (`onclick`, ...) holding code that runs when its event happens |
| **`this`** | inside a handler, the element the event happened on |
| **Statement** | one instruction, ending in `;` |
| **Assignment** | `x = value;` stores a value. Read `=` as "gets". |
| **Variable** | a named, changeable value |
| **String** | text in quotes |
| **Concatenation** | joining strings with `+` |
| **Escape character** | backslash. `\'` is an apostrophe inside a single-quoted string. |
| **Function call** | `name(inputs)`; what comes back is the **return value** |
| **Pseudo-random** | generated by an algorithm, but distributed like random numbers |
| **`NaN`** | "Not a Number": the result of arithmetic on something that isn't numeric |
| **Bug / debugging** | an error in a program / finding and fixing it |

### Event handler attributes

| Attribute | Fires when | Typical element |
|---|---|---|
| `onclick` | element is clicked | button, image |
| `onmouseover` | mouse moves onto the element | image |
| `onmouseout` | mouse moves off the element | image |
| `onload` | the page has fully loaded | `<body>` |

### DOM properties you'll change

| Property | On | Does |
|---|---|---|
| `.src` | `<img>` | which image file is shown |
| `.width`, `.height` | `<img>` | displayed size in pixels |
| `.value` | `<input type="text">` | the text currently in the box (read or write) |
| `.innerHTML` | `<div>`, `<p>`, `<span>`, ... | the HTML content inside the element |

### Operators

| Operator | On strings | On numbers |
|---|---|---|
| `+` | concatenation | addition |
| `-`, `*`, `/` | not defined (gives `NaN`) | subtraction, multiplication, division |
| `=` | assignment | assignment |

If `+` has a string on either side, it does concatenation and converts the other side to a string. `'12' + 1` is `'121'`; `12 + 1` is `13`.

### Math functions

| Function | Returns | Example |
|---|---|---|
| `Math.sqrt(x)` | square root | `Math.sqrt(12.25)` is `3.5` |
| `Math.pow(x, y)` | x to the power y | `Math.pow(2, 10)` is `1024` |
| `Math.max(a, b)` / `Math.min(a, b)` | larger / smaller of two | `Math.max(-3, -8)` is `-3` |
| `Math.abs(x)` | absolute value | `Math.abs(-5)` is `5` |
| `Math.round(x)` | nearest whole number | `Math.round(3.5)` is `4` |
| `Math.floor(x)` | round down | `Math.floor(3.9)` is `3` |
| `Math.ceil(x)` | round up | `Math.ceil(3.1)` is `4` |
| `Math.random()` | pseudo-random number in `[0, 1)` | different each call |
| `parseFloat(s)` | the number at the start of string `s` | `parseFloat('3.14')` is `3.14` |

Rounding to one decimal place: `Math.round(3.14159 * 10) / 10` → `Math.round(31.4159) / 10` → `31 / 10` → `3.1`. Multiply, round, divide back.

### Number limits

| Value | Result |
|---|---|
| `1e308` | representable |
| `1e309` | `Infinity` |
| `1e-323` | representable |
| `1e-324` | `0` |
| `0.9999999999999999` (16 nines) | stored exactly |
| `0.99999999999999999` (17 nines) | rounds to `1` |

---

## Practical Examples

### Mystery image: react to the mouse

```html
<img src="mystery.gif" alt="Mystery image" height="85" width="85"
     onmouseover="this.src='happy.gif'; this.height=200; this.width=200;"
     onmouseout="this.src='mystery.gif'; this.height=85; this.width=85;">
```

Moving the mouse over the image swaps the picture and enlarges it. Moving off restores both. Note the three statements in one handler, run in order.

### Buttons that change a different element

```html
<img id="mysteryImg" src="mystery.gif" alt="Mystery image">
<br>
<input type="button" value="Happy" onclick="document.getElementById('mysteryImg').src='happy.gif';">
<input type="button" value="Sad"   onclick="document.getElementById('mysteryImg').src='sad.gif';">
<input type="button" value="Reset" onclick="document.getElementById('mysteryImg').src='mystery.gif';">
```

### Help page: replacing text in a div

```html
<div id="outputDiv">Welcome! Click the button if you need help.</div>

<input type="button" value="Help"
       onclick="document.getElementById('outputDiv').innerHTML =
                '<p><b>Help:</b> move the mouse over the image to reveal it. ' +
                'Click a button to pick a picture. That\'s all there is to it.</p>';">
```

The new content includes HTML tags and an escaped apostrophe in `That\'s`.

### Greetings page: read a text box, use it twice

```html
Enter your name: <input type="text" id="nameBox" size="20" value="">
<input type="button" value="Click for Greeting"
       onclick="userName = document.getElementById('nameBox').value;
                document.getElementById('outputDiv').innerHTML =
                  'Hello ' + userName + ', welcome to my page.<br>' +
                  'Do you mind if I call you ' + userName + '?';">
<div id="outputDiv"></div>
```

The box contents are read once into `userName`, then used twice.

### Tip calculator: numbers from text boxes

```html
<head>
  <script>
    // CalculateTip: reads the bill and tip percent, displays the tip amount.
    function CalculateTip() {
      amount = parseFloat(document.getElementById('amountBox').value);
      percent = parseFloat(document.getElementById('percentBox').value);
      tip = amount * (percent / 100);
      tip = Math.round(tip * 100) / 100;        // round to cents
      document.getElementById('outputDiv').innerHTML =
        'A ' + percent + '% tip on $' + amount + ' is $' + tip + '.';
    }
  </script>
</head>
<body>
  Bill amount: $<input type="text" id="amountBox" size="8" value=""><br>
  Tip percent: <input type="text" id="percentBox" size="4" value="15">%<br>
  <input type="button" value="Calculate" onclick="CalculateTip();">
  <div id="outputDiv"></div>
</body>
```

Without `parseFloat`, `amount * (percent / 100)` would still work by accident (`*` and `/` convert strings to numbers), but `amount + tip` would concatenate. Convert at the start and you never have to think about it.

### Swap two pictures

Two images trade places when the button is clicked. You need a temporary variable, because as soon as you overwrite the left image you've lost its old value.

```html
<head>
  <script>
    // SwapImages: exchanges the pictures shown in leftImg and rightImg.
    function SwapImages() {
      temp = document.getElementById('leftImg').src;                                 // 1. save left
      document.getElementById('leftImg').src = document.getElementById('rightImg').src;  // 2. left gets right
      document.getElementById('rightImg').src = temp;                                // 3. right gets saved left
    }
  </script>
</head>
<body>
  <img id="leftImg"  src="cat.jpg" alt="Left picture">
  <img id="rightImg" src="dog.jpg" alt="Right picture">
  <br>
  <input type="button" value="Swap" onclick="SwapImages();">
</body>
```

### Swap text instead of pictures

Same idea, with the `value` of two text boxes. Type your first name in one and your family name in the other, then swap them.

```html
<head>
  <script>
    // SwapNames: exchanges the contents of the two text boxes.
    function SwapNames() {
      temp = document.getElementById('firstBox').value;
      document.getElementById('firstBox').value = document.getElementById('lastBox').value;
      document.getElementById('lastBox').value = temp;
    }
  </script>
</head>
<body>
  First name: <input type="text" id="firstBox" size="15" value="">
  Family name: <input type="text" id="lastBox" size="15" value="">
  <input type="button" value="Swap" onclick="SwapNames();">
</body>
```

### Dice: random number picks an image

Die images are named `die1.gif` through `die6.gif`, so the roll number can be glued straight into the file name.

```html
<head>
  <script>
    // RollDie: shows a random die face from 1 to 6.
    function RollDie() {
      roll = Math.floor(Math.random() * 6) + 1;
      document.getElementById('dieImg').src = 'http://balance3e.com/Images/die' + roll + '.gif';
      document.getElementById('outputDiv').innerHTML = 'You rolled a ' + roll + '.';
    }
  </script>
</head>
<body>
  <img id="dieImg" src="http://balance3e.com/Images/die1.gif" alt="Die">
  <br>
  <input type="button" value="Roll" onclick="RollDie();">
  <div id="outputDiv"></div>
</body>
```

### Photo rotation and banner ads: `onload` and `setInterval`

```html
<head>
  <script>
    // SelectImage: shows a random photo from photo1.jpg ... photo5.jpg.
    function SelectImage() {
      pick = Math.floor(Math.random() * 5) + 1;
      document.getElementById('photoImg').src = 'photo' + pick + '.jpg';
    }
  </script>
</head>

<!-- start with a random photo when the page loads -->
<body onload="SelectImage();">
  <img id="photoImg" src="photo1.jpg" alt="Photo">
  <input type="button" value="Next" onclick="SelectImage();">
</body>
```

To rotate automatically every 5 seconds instead of on a click, change the body tag:

```html
<body onload="setInterval(SelectImage, 5000);">
```

---

## Going further

The material above reacts to events and does arithmetic. Real pages also have to *decide* things and *repeat* things, and for the term project they have to check form input before it reaches PHP. That's what this section adds.

### Declaring variables: `let` and `const`

Writing `userName = 'Dave';` with no keyword creates a global variable by accident, and a typo in the name silently creates another one. Declare variables instead:

```javascript
let count = 0;            // a variable that will change
const taxRate = 0.05;     // a value that won't
count = count + 1;        // fine
taxRate = 0.06;           // error: assignment to constant
```

Use `const` by default and `let` when you know the value will change. Avoid `var`, the older keyword with confusing scoping rules ([`let`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/let), [`const`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/const)).

### Making decisions: `if`, `else`, and comparisons

```javascript
const amount = parseFloat(document.getElementById('amountBox').value);

if (isNaN(amount)) {
  document.getElementById('outputDiv').textContent = 'Please enter a number.';
} else if (amount < 0) {
  document.getElementById('outputDiv').textContent = 'The amount can\'t be negative.';
} else {
  document.getElementById('outputDiv').textContent = 'Your tip is $' + (amount * 0.15).toFixed(2);
}
```

| Operator | Meaning | Note |
|---|---|---|
| `===` / `!==` | equal / not equal, **same type** | use these |
| `==` / `!=` | equal / not equal after converting types | `'12' == 12` is true. Avoid. |
| `<`, `>`, `<=`, `>=` | comparisons | |
| `&&` / `\|\|` / `!` | and / or / not | |

`isNaN(x)` tells you whether `parseFloat` failed. `.toFixed(2)` formats a number with two decimals and returns a string, so use it only for display ([Strict equality](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Strict_equality), [`toFixed`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Number/toFixed)).

### Arrays and loops

An **array** is a list of values in square brackets. Items are numbered from 0, and `.length` is how many there are.

```javascript
const quotes = [
  'Talk is cheap. Show me the code.',
  'First, solve the problem. Then, write the code.',
  'Simplicity is the soul of efficiency.'
];

quotes[0]            // the first quote
quotes.length        // 3

// A random item from any array: this replaces the "die1.gif ... die6.gif" trick
const pick = quotes[Math.floor(Math.random() * quotes.length)];

// Visit every item
for (let i = 0; i < quotes.length; i++) {
  console.log(i + ': ' + quotes[i]);
}

// The same, without the counter
for (const q of quotes) {
  console.log(q);
}
```

Arrays come with useful methods: `push(item)` adds to the end, `indexOf(item)` finds a position, `join(', ')` makes a string ([Arrays](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array)).

### Template literals: strings without the plus signs

Backticks let you put expressions straight into a string with `${...}`. They also allow line breaks, which single and double quotes don't.

```javascript
const name = document.getElementById('nameBox').value;
const total = 12.5;

// Old way
document.getElementById('outputDiv').innerHTML = 'Hello ' + name + ', your total is $' + total + '.';

// Template literal
document.getElementById('outputDiv').innerHTML = `Hello ${name}, your total is $${total}.`;
```

([Template literals](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals))

### Finding elements without an ID: `querySelector`

`document.querySelector('...')` takes any CSS selector and returns the first match. `querySelectorAll` returns all of them.

```javascript
document.querySelector('#outputDiv')          // same as getElementById('outputDiv')
document.querySelector('.error')              // first element with class="error"
document.querySelectorAll('nav a')            // every link inside the nav

for (const link of document.querySelectorAll('nav a')) {
  link.style.color = 'green';
}
```

### Changing how things look

Beyond `src` and `innerHTML`, you can change any CSS property through `.style`, or, better, switch a class on and off and keep the actual styling in the stylesheet:

```javascript
const box = document.getElementById('outputDiv');

box.style.color = 'red';                 // one property, written in camelCase (background-color → backgroundColor)
box.classList.add('error');              // apply the .error rule from style.css
box.classList.remove('error');
box.classList.toggle('hidden');          // add if missing, remove if present
```

### Checking a form before it's sent

The browser can validate a form before it ever reaches your PHP script. Two layers:

**1. Built-in HTML validation.** Attributes on the fields do a lot with no JavaScript at all:

```html
<input type="text"   name="first_name" required maxlength="30">
<input type="email"  name="email" required>
<input type="number" name="year" min="1" max="4" required>
```

The browser refuses to submit and shows a message if a `required` field is empty or an `email` field isn't an email.

**2. A JavaScript check on submit.** For rules the attributes can't express, run a function when the form is submitted. Returning `false` stops the submission.

```html
<form action="signup.php" method="post" onsubmit="return checkForm();">
  <p><label>First name: <input type="text" id="firstName" name="first_name"></label></p>
  <p><label>Password: <input type="password" id="password" name="password"></label></p>
  <p><label>Repeat it: <input type="password" id="password2" name="password2"></label></p>
  <p id="formError" class="error"></p>
  <input type="submit" value="Join">
</form>

<script>
  function checkForm() {
    const first = document.getElementById('firstName').value.trim();
    const pw1 = document.getElementById('password').value;
    const pw2 = document.getElementById('password2').value;
    const errorBox = document.getElementById('formError');

    if (first === '') {
      errorBox.textContent = 'Please enter your first name.';
      return false;
    }
    if (pw1.length < 8) {
      errorBox.textContent = 'The password needs at least 8 characters.';
      return false;
    }
    if (pw1 !== pw2) {
      errorBox.textContent = 'The passwords don\'t match.';
      return false;
    }
    return true;    // all good: let the form submit
  }
</script>
```

`.trim()` strips spaces from both ends, so a name made of spaces counts as empty.

> [!IMPORTANT]
> Browser-side validation is a convenience for the user, not a security measure. Anyone can turn JavaScript off or send a request by hand. The PHP script must check everything again. Do both: JavaScript for instant feedback, PHP for the real decision.

### Where to put the script

Inline handlers are fine for a page with one or two buttons. As soon as you have a function or two, put them in a `<script>` block at the **end of `<body>`**, or in a separate file loaded with `defer`, so the elements exist before the code looks for them:

```html
<script src="js/site.js" defer></script>   <!-- in <head>; runs after the page is parsed -->
```

And attach handlers from the script instead of from the HTML:

```javascript
document.getElementById('rollBtn').addEventListener('click', rollDie);
document.getElementById('signupForm').addEventListener('submit', function (event) {
  if (!checkForm()) {
    event.preventDefault();    // the modern equivalent of "return false"
  }
});
```

This keeps HTML and JavaScript separate, the same way CSS keeps styling separate ([`addEventListener`](https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener), [`preventDefault`](https://developer.mozilla.org/en-US/docs/Web/API/Event/preventDefault)).

---

## Nice to know

> [!CAUTION]
> **`'12' + 1` is `'121'`.** Anything read from a text box is a string. Wrap it in `parseFloat()` before doing math with it. This is the logic error you'll hit most, and the browser won't warn you.

> [!WARNING]
> **IDs are case-sensitive and must be unique.** `getElementById('OutputDiv')` won't find `id="outputDiv"`. It returns `null`, and the next `.innerHTML` throws `Cannot set properties of null`. When you see that error, check the ID spelling first.

> [!WARNING]
> **`innerHTML` with user input is a security hole.** If you assign text the user typed into `innerHTML`, any `<script>` or `<img onerror=...>` they typed runs on your page. That's cross-site scripting (XSS). For plain text use `textContent` instead, which never interprets tags ([`textContent`](https://developer.mozilla.org/en-US/docs/Web/API/Node/textContent), OWASP [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)). This matters for the term project.

> [!IMPORTANT]
> **`Math.random()` never returns 1.** So `Math.floor(Math.random() * 6)` gives 0 to 5, never 6. Add 1 to get 1 to 6. Off-by-one errors here are common: check both ends of your range.

> [!TIP]
> **The inline `onclick=""` style is a starting point, not the finish line.** Once you have more than a button or two, move the code into a `<script>` at the end of `<body>` (or a `.js` file with `defer`), declare variables with `const` and `let`, attach handlers with `addEventListener`, and use `textContent` for plain text. The [Going further](#going-further) section shows all of this. Learn the inline way first; use the other way for the project.

> [!TIP]
> **Use the browser console.** Press F12 (or right-click, Inspect) and open the Console tab. Syntax and run-time errors show up there with a line number, and `console.log(x)` prints values without a pop-up. This replaces the diagnostic `alert` technique.

> [!NOTE]
> **Uppercase names in older material.** Some books write `ONCLICK`, `SRC`, `INNERHTML` and so on in caps for emphasis. In HTML, attribute names are case-insensitive so `onclick` works. In JavaScript, property names are case-sensitive: it must be `.innerHTML`, `.src`, `.value`, exactly.

## Practice exercises

1. **Counter.** A number in a `<span>`, plus "+1", "-1", and "Reset" buttons. Keep the count in a variable; don't read it back out of the page.
2. **Unit converter.** A text box, a "to Fahrenheit" button and a "to Celsius" button. Show "Please enter a number" if `parseFloat` gives `NaN`.
3. **Guess the number.** On load, pick a random whole number from 1 to 100. The user types a guess and clicks Check; respond "higher", "lower", or "correct in N tries".
4. **Random quote.** Put five quotes in an array and show a random one on load and on a button click. Then make it rotate every 10 seconds with `setInterval`.
5. **Validate the sign-up form** from the HTML notes before it submits: name not empty, email contains `@`, a year picked, passwords match and at least 8 characters. Show the first problem in a `<p>` under the form, and stop the submission.
6. **Swap without a temporary variable.** Look up array destructuring (`[a, b] = [b, a]`) and rewrite the picture-swap function with it.

## Further Reading

| Topic | MDN Web Docs |
|---|---|
| JavaScript from scratch | [Scripting](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Scripting) |
| The DOM | [Introduction to the DOM](https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model/Introduction) |
| Finding elements | [`getElementById`](https://developer.mozilla.org/en-US/docs/Web/API/Document/getElementById), [`querySelector`](https://developer.mozilla.org/en-US/docs/Web/API/Document/querySelector) |
| Changing content | [`innerHTML`](https://developer.mozilla.org/en-US/docs/Web/API/Element/innerHTML), [`textContent`](https://developer.mozilla.org/en-US/docs/Web/API/Node/textContent) |
| Text boxes | [`HTMLInputElement.value`](https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/value) |
| Events | [Introduction to events](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Scripting/Events) |
| Strings | [String](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String), [Template literals](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals) |
| Numbers and `NaN` | [Number](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Number), [`parseFloat`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/parseFloat) |
| Math | [Math](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math), [`Math.random`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random) |
| Functions | [Functions guide](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Functions) |
| Timers | [`setInterval`](https://developer.mozilla.org/en-US/docs/Web/API/Window/setInterval), [`setTimeout`](https://developer.mozilla.org/en-US/docs/Web/API/Window/setTimeout) |
| Debugging | [What went wrong?](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Scripting/What_went_wrong), [`console`](https://developer.mozilla.org/en-US/docs/Web/API/console) |

| Topic | OWASP |
|---|---|
| Why `innerHTML` with user text is dangerous | [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html) |

<div align="center">

</div>
