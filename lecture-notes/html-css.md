# HTML & CSS - Lecture notes

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

HTML (HyperText Markup Language) is the language a browser reads to build a web page. You write plain text and wrap pieces of it in **tags** that say what each piece is: a heading, a paragraph, a link, an image, a table cell, a form field. The browser turns that into what you see on screen.

HTML has two eras. In the older style, which you'll still find in many tutorials and textbooks, the *look* of the page is set with attributes like `<FONT COLOR="red">` and `<BODY BGCOLOR="black">`. Those still work in browsers, but they've been replaced by **CSS** (Cascading Style Sheets). The modern split is simple: HTML says *what* something is, CSS says *how it looks*. These notes cover both, because you'll read a lot of old-style HTML, and for each old-style attribute they show the CSS you'd write today. The CSS is what you'll use for the term project.

---

## Core Concepts

### Tags, elements, and attributes

A **tag** is a keyword in angle brackets, like `<b>`. Most tags come in pairs: an opening tag and a closing tag with a forward slash, and the text between them is what the tag affects.

```html
<b>Warning</b>        <!-- the word "Warning" is shown in bold -->
```

The opening tag plus its content plus the closing tag is called an **element**. Opening tags can also carry **attributes**, which are `name="value"` settings that change how the element behaves:

```html
<a href="https://www.mit.edu">MIT</a>
<img src="logo.png" width="120" alt="Club logo">
```

A few tags have no content and no closing tag: `<br>` (line break), `<img>` (image), and `<input>` are the ones you'll use most. They are called **void elements**.

Older code writes tags in UPPERCASE. HTML doesn't care about case, but the convention today is lowercase, and that's what these notes use. See [HTML elements reference](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements) for the full list.

### Nesting: close the nearest tag first

When tags sit inside other tags, the one you opened last must be closed first. Think of them as boxes inside boxes.

```html
<h1><i>The Nation</i></h1>     <!-- correct -->
<h1><i>The Nation</h1></i>     <!-- wrong: the tags overlap -->
```

Browsers try to guess what you meant when tags overlap, and different browsers guess differently. Keep the nesting clean so the page looks the same everywhere.

### Every page has the same skeleton

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Example</title>
</head>
<body>
  This is where the text and images of the page go.
</body>
</html>
```

- **`<!DOCTYPE html>`** tells the browser to use modern HTML rules. Older pages leave it out, but always include it.
- **`<head>`** holds information *about* the page: the title, the character encoding, and later, links to CSS files and scripts.
- **`<title>`** is what shows in the browser tab, in bookmarks and history, and in search-engine results. Pick it carefully.
- **`<body>`** holds everything the visitor actually sees.

### What you type is not what you get

Browsers collapse whitespace. Pressing Enter five times in your HTML file produces no blank lines on the page. Extra spaces become one space. To control layout you have to say what you mean with tags:

- `<p>` starts a new **paragraph**, with space before and after.
- `<br>` forces a **line break** with no extra space.
- `<h1>` to `<h6>` are **headings**, biggest to smallest. Only `h1` through `h6` exist; there is no `h7` ([Heading elements](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/Heading_Elements)).

Comments are written `<!-- like this -->`. They don't show on the page, but anyone can see them by viewing the page source, so don't put anything private in them.

### Links have a destination and a label

```html
<a href="https://www.cnn.com">CNN</a>
```

`href` is the **destination**, the address the browser goes to when the link is clicked. The text between the tags is the **label**, the clickable part the visitor sees. A destination can be:

| Destination | Example | Goes to |
|---|---|---|
| Full URL | `href="https://www.mit.edu"` | another website |
| Relative path | `href="about.html"` | a file next to the current page |
| Relative path with folder | `href="pages/contact.html"` | a file in a sub-folder |
| Email | `href="mailto:club@college.edu"` | opens the visitor's mail program |

The `<a>` element reference is at [`<a>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/a).

### Images are pulled in from files

```html
<img src="team.jpg" width="300" height="200" alt="The 2026 team photo">
```

`src` is the location of the image file, using the same absolute or relative addressing as links. `width` and `height` set the displayed size in pixels. Always add `alt`: it's the text shown if the image can't load, and what screen readers read aloud ([`<img>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/img)).

### Lists come in two kinds

- **Ordered** `<ol>`: numbered steps, in sequence.
- **Unordered** `<ul>`: bullet points.

Both hold **list items**, `<li>`, one per entry. Old-style HTML uses the `type` attribute to switch between numbers, letters, roman numerals, and bullet shapes. In CSS that's the `list-style-type` property (see the [Practical Examples](#practical-examples)).

### Tables are rows of cells

```html
<table>
  <tr>              <!-- tr = table row -->
    <td>One</td>    <!-- td = table data (one cell) -->
    <td>Two</td>
  </tr>
</table>
```

A table is built row by row. Each `<tr>` holds cells. Use `<th>` instead of `<td>` for header cells, which the browser shows bold and centered by default. Beyond the basic cells:

- **`colspan="n"`** stretches a cell across `n` columns, like a headline across a newspaper article. **`rowspan="n"`** stretches it down `n` rows.
- **`<thead>`, `<tbody>`, `<tfoot>`** group rows into sections so you can style them together.
- **`<colgroup>` and `<col>`** group columns for the same purpose.
- Borders, cell padding, cell spacing, widths, alignment, and background colors were once set with attributes on the table tags. Every one of those is a CSS property today, listed in the [table further down](#old-attributes-and-their-css-replacements).

Older tutorials also use tables for page layout, such as wrapping text around a table or nesting tables to build columns. Don't. Tables are for data with rows and columns. Page layout is done in CSS ([`<table>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/table)).

### Forms collect input and send it to a script

A form has two halves: the **shell** (the HTML the user fills in) and the **script** that processes what they typed. In this course the script is PHP.

```html
<form action="signup.php" method="post">
  <label>First name: <input type="text" name="first_name" size="20"></label>
  <input type="submit" value="Join">
</form>
```

- **`action`** is the address of the script that receives the data.
- **`method`** is how the data is sent. `post` hides it from the URL and allows more data. `get` puts it in the URL, which is fine for searches but not for passwords.
- **`name`** on each field is the key the script uses to find the value. `name="first_name"` here becomes `$_POST['first_name']` in PHP. No `name`, no data.
- **`value`** is the initial text in a text box, or the label on a button.
- **The submit button** is what triggers sending.

Wrap each field in a `<label>` (or use `for="id"`). Clicking the label focuses the field, and screen readers announce it. The form reference is at [`<form>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/form) and [`<input>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/input).

### Appearance belongs in CSS, not in attributes

Old-style HTML sets colors, fonts, sizes, and alignment with attributes: `<FONT SIZE=4 FACE="Courier" COLOR="red">`, `<BODY BGCOLOR="black" TEXT="white" LINK="blue">`, `<H1 ALIGN=CENTER>`, `<TABLE BORDER=10 CELLPADDING=5>`. They're marked **deprecated** in the HTML standard, and `<font>`, `<basefont>`, `<center>`, and `<blink>` have been removed entirely (browsers still render most of them out of habit, except `<blink>`).

The problem isn't that they don't work. It's that they lock the look into every single tag. To change the heading color on a 40-page site you'd edit 40 files. With CSS you write the rule once:

```css
h1 { color: red; text-align: center; }
```

### A CSS rule is a selector plus declarations

```css
selector {
  property: value;
  property: value;
}
```

The **selector** picks which elements the rule applies to. Each **declaration** sets one property. The three selectors you'll use most:

| Selector | Matches | Example |
|---|---|---|
| `p` | every `<p>` element | `p { line-height: 1.5; }` |
| `.note` | every element with `class="note"` | `.note { background: yellow; }` |
| `#roster` | the one element with `id="roster"` | `#roster { width: 100%; }` |

An element can have several classes, separated by spaces: `class="note important"`. An `id` must be unique on the page. More selectors are described in [CSS selectors](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors).

### Three ways to attach CSS to a page

| Method | Where | Use it for |
|---|---|---|
| **External file** | `<link rel="stylesheet" href="style.css">` in `<head>` | almost everything. One file styles every page of the site. |
| **Internal** | a `<style>` block in `<head>` | a single page with its own look, or quick experiments |
| **Inline** | `style="..."` attribute on one element | one-off tweaks. Avoid: it's the old `<FONT>` problem again. |

When rules conflict, the more specific selector wins (`#id` beats `.class` beats `element`), and among equals the one written last wins. That's the "cascading" in Cascading Style Sheets ([Handling conflicts](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Styling_basics/Handling_conflicts)).

### Every element is a box

CSS treats each element as a rectangle with four layers, from the inside out:

```
┌─ margin (space outside, transparent) ─────────────┐
│  ┌─ border ────────────────────────────────────┐  │
│  │  ┌─ padding (space inside the border) ───┐  │  │
│  │  │            content                    │  │  │
│  │  └───────────────────────────────────────┘  │  │
│  └─────────────────────────────────────────────┘  │
└───────────────────────────────────────────────────┘
```

This is the **box model**. A table's old `CELLPADDING` attribute is padding on each cell; `CELLSPACING` is the gap between cells; `HSPACE`/`VSPACE` are margins. Once you see the box, most layout questions become "which of these four do I change?" ([The box model](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Styling_basics/Box_model)).

---

## Important Facts & Definitions

### Key terms

| Term | Meaning |
|---|---|
| **Tag** | a keyword in angle brackets, `<p>` or `</p>` |
| **Element** | an opening tag, its content, and its closing tag |
| **Attribute** | a `name="value"` setting inside an opening tag |
| **Void element** | an element with no content and no closing tag (`<br>`, `<img>`, `<input>`) |
| **Nesting** | putting elements inside other elements. Close the inner one first. |
| **Deprecated** | still works, but has been replaced and may stop working |
| **CSS rule** | a selector plus a block of `property: value` declarations |
| **Selector** | the part of a rule that chooses which elements it applies to |
| **Class** | a reusable label you attach to any number of elements (`class="x"`, selected with `.x`) |
| **ID** | a unique label for exactly one element (`id="x"`, selected with `#x`) |
| **Box model** | content, padding, border, margin: the four layers of every element |

### Tags you'll use

| Tag | Does |
|---|---|
| `<html>`, `<head>`, `<title>`, `<body>` | the page skeleton |
| `<h1>` to `<h6>` | headings, largest to smallest |
| `<p>` | paragraph |
| `<br>` | line break |
| `<b>`, `<i>`, `<u>` | bold, italic, underline. Prefer `<strong>` and `<em>` when the text is actually important or stressed. |
| `<!-- -->` | comment |
| `<a href="...">` | link |
| `<img src="..." alt="...">` | image |
| `<ol>`, `<ul>`, `<li>` | ordered list, unordered list, list item |
| `<table>`, `<tr>`, `<td>`, `<th>` | table, row, cell, header cell |
| `<thead>`, `<tbody>`, `<tfoot>` | row groups in a table |
| `<colgroup>`, `<col>` | column groups in a table |
| `<form>` | a form; `action` says where it goes, `method` says how |
| `<input>` | a form field; `type` says what kind |
| `<select>`, `<option>` | drop-down menu and its choices |
| `<textarea>` | multi-line text box |
| `<font>`, `<basefont>`, `<center>`, `<blink>` | removed from HTML. Use CSS. |

### Form fields

`<input>` changes shape depending on its `type` attribute.

| `type` | Shows |
|---|---|
| `text` | one-line text box. `size` sets its visible width in characters, `maxlength` caps how much can be typed. |
| `password` | text box that hides what's typed |
| `radio` | round button. Give every button in a group the same `name`; only one can be selected. |
| `checkbox` | square box. Any number can be checked. |
| `submit` | the button that sends the form. `value` is its label. |
| `reset` | clears the form back to its initial values |
| `email`, `number`, `date` | newer types with built-in browser validation |

### Old attributes and their CSS replacements

| Old attribute | CSS today |
|---|---|
| `<font face="Courier">` | `font-family: Courier, monospace;` |
| `<font size="4">` | `font-size: 18px;` (or `1.2em`, `1.2rem`) |
| `<font color="red">` | `color: red;` |
| `<body bgcolor="black">` | `body { background-color: black; }` |
| `<body text="white">` | `body { color: white; }` |
| `<body link="..." vlink="..." alink="...">` | `a:link`, `a:visited`, `a:active` selectors with `color` |
| `<h1 align="center">` | `text-align: center;` |
| `<basefont size="7">` | `body { font-size: ...; }` |
| `<img border="2">` | `border: 2px solid black;` |
| `<ol type="A">` / `<ul type="square">` | `list-style-type: upper-alpha;` / `list-style-type: square;` |
| `<table border="1">` | `table, td, th { border: 1px solid gray; }` |
| `<table cellpadding="5">` | `td, th { padding: 5px; }` |
| `<table cellspacing="0">` | `table { border-spacing: 0; }` or `border-collapse: collapse;` |
| `<table width="600">` | `width: 600px;` |
| `<table hspace="20" vspace="20">` | `margin: 20px;` |
| `<table align="left">` (text wraps around) | `float: left;` |
| `<td bgcolor="blue">` | `background-color: blue;` |
| `<td align="right" valign="top">` | `text-align: right; vertical-align: top;` |
| `<td nowrap>` | `white-space: nowrap;` |
| `<center>` | `text-align: center;` on text, or `margin: 0 auto;` on a block with a width |

### CSS properties you'll use most

| Property | Sets | Example values |
|---|---|---|
| `color` | text color | `red`, `#ff0000`, `rgb(255 0 0)` |
| `background-color` | background | same as above |
| `font-family` | typeface, with fallbacks | `Arial, sans-serif` |
| `font-size` | text size | `16px`, `1.2rem` |
| `font-weight` | boldness | `bold`, `400`, `700` |
| `text-align` | horizontal alignment | `left`, `center`, `right` |
| `line-height` | space between lines | `1.5` |
| `width`, `height` | box size | `300px`, `50%` |
| `padding` | space inside the border | `10px`, `5px 10px` |
| `margin` | space outside the border | `10px`, `0 auto` |
| `border` | line around the box | `1px solid #ccc` |
| `list-style-type` | bullet or number style | `disc`, `square`, `decimal`, `lower-roman` |
| `border-collapse` | merges table cell borders | `collapse` |

Colors can be names (`red`), hex (`#ff0000`, 2 hex digits each for red, green, blue), or functions (`rgb(255 0 0)`). The full list of properties is in the [CSS reference](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference).

---

## Practical Examples

All of these build one page for a student club, so you can see how the parts fit together.

### The page skeleton with headings, text, a link, and an image

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Campus Coding Club</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Campus Coding Club</h1>
  <img src="images/logo.png" width="120" height="120" alt="Coding Club logo">

  <p>We meet every <strong>Thursday at 5pm</strong> in room 214.<br>
  Bring a laptop. Beginners welcome.</p>

  <p>Questions? <a href="mailto:codingclub@college.edu">Email us</a> or
  see the <a href="schedule.html">full schedule</a>.</p>

  <!-- TODO: add the photo gallery -->
</body>
</html>
```

### Lists

```html
<h2>How to join</h2>
<ol>
  <li>Fill in the form below.</li>
  <li>Show up on Thursday.</li>
  <li>Say hi.</li>
</ol>

<h2>What we work on</h2>
<ul>
  <li>Web apps (PHP and MySQL)</li>
  <li>Small games in JavaScript</li>
  <li>Whatever you bring</li>
</ul>
```

The old way to change the numbering to letters is `<ol type="A">`. In CSS:

```css
ol { list-style-type: upper-alpha; }   /* A, B, C */
ul { list-style-type: square; }
```

### A table with a header row, a spanning cell, and a footer

```html
<table id="roster">
  <thead>
    <tr>
      <th>Name</th>
      <th>Major</th>
      <th>Year</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Maria Lopez</td>
      <td>Biology</td>
      <td>2</td>
    </tr>
    <tr>
      <td>Jon Park</td>
      <td>History</td>
      <td>1</td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">2 members and counting</td>   <!-- one cell across all 3 columns -->
    </tr>
  </tfoot>
</table>
```

The old way is `<table border="1" cellpadding="6">`. In CSS:

```css
#roster { border-collapse: collapse; width: 100%; }
#roster th, #roster td { border: 1px solid #999; padding: 6px; text-align: left; }
#roster thead { background-color: #eee; }
```

### A sign-up form

```html
<form action="signup.php" method="post">
  <p><label>First name: <input type="text" name="first_name" size="20" maxlength="30"></label></p>
  <p><label>Email: <input type="email" name="email" size="40"></label></p>

  <p>Year:
    <label><input type="radio" name="year" value="1"> First</label>
    <label><input type="radio" name="year" value="2"> Second</label>
    <label><input type="radio" name="year" value="3"> Third</label>
  </p>

  <p><label>Major:
    <select name="major">
      <option value="">-- pick one --</option>
      <option value="cs">Computer Science</option>
      <option value="math">Mathematics</option>
      <option value="other">Other</option>
    </select></label></p>

  <p><label><input type="checkbox" name="newsletter" value="yes"> Send me the weekly email</label></p>

  <p><label>Anything else?<br>
    <textarea name="comments" rows="4" cols="40"></textarea></label></p>

  <input type="submit" value="Join the club">
  <input type="reset" value="Clear">
</form>
```

When submitted, PHP will see `$_POST['first_name']`, `$_POST['email']`, `$_POST['year']`, and so on. The names in the HTML and the keys in PHP must match exactly.

### The stylesheet for the whole page

`style.css`, linked from the `<head>`:

```css
/* Page-wide defaults. This replaces <body bgcolor="..." text="..."> and <basefont>. */
body {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 16px;
  line-height: 1.5;
  color: #222;
  background-color: #fafafa;
  margin: 0 auto;          /* center the page */
  max-width: 800px;        /* but don't let lines get too long */
  padding: 20px;
}

/* Replaces <h1 align="center"> and <font color="..."> */
h1 {
  color: #1a4d8f;
  text-align: center;
}

/* Replaces <body link="..." vlink="...">  */
a:link    { color: #1a4d8f; }
a:visited { color: #6a3d8f; }
a:hover   { text-decoration: none; }

/* A reusable class for callout boxes */
.note {
  background-color: #fff8c5;
  border: 1px solid #e0c200;
  padding: 10px;
  margin: 16px 0;
}
```

Then anywhere on the page:

```html
<p class="note">Room changed to 220 this week only.</p>
```

### Same look, three ways

All three color the heading blue. The first is the one to use.

```html
<!-- 1. External file (style.css contains: h1 { color: blue; }) -->
<link rel="stylesheet" href="style.css">

<!-- 2. Internal, in <head> -->
<style>
  h1 { color: blue; }
</style>

<!-- 3. Inline, on the element itself -->
<h1 style="color: blue;">Campus Coding Club</h1>
```

---

## Going further

Material that goes beyond the basics above, but that you'll need as soon as you build a real page for the term project.

### Semantic layout elements

`<div>` is a generic box and `<span>` is a generic inline wrapper. They're fine for styling hooks, but HTML also has elements that *say what a region is*. Screen readers, search engines, and your future self all benefit.

```html
<body>
  <header>
    <h1>Campus Coding Club</h1>
    <nav>
      <a href="index.html">Home</a>
      <a href="schedule.html">Schedule</a>
      <a href="signup.php">Join</a>
    </nav>
  </header>

  <main>
    <section>
      <h2>This week</h2>
      <p>...</p>
    </section>
    <section>
      <h2>Members</h2>
      <table id="roster">...</table>
    </section>
  </main>

  <footer>
    <p>&copy; 2026 Campus Coding Club</p>
  </footer>
</body>
```

| Element | Use for |
|---|---|
| `<header>` | the top of the page (or of a section): title, logo, navigation |
| `<nav>` | a group of links for moving around the site |
| `<main>` | the main content. One per page. |
| `<section>` | a themed group of content, usually with its own heading |
| `<article>` | a self-contained piece: a blog post, a news item, a product card |
| `<aside>` | side content: related links, a sidebar |
| `<footer>` | the bottom: copyright, contact, small print |

### Special characters

Some characters mean something to HTML, so to *show* them you write an **entity** instead:

| To show | Write | Why |
|---|---|---|
| `<` | `&lt;` | otherwise it starts a tag |
| `>` | `&gt;` | |
| `&` | `&amp;` | otherwise it starts an entity |
| `"` inside an attribute | `&quot;` | |
| a space that won't break or collapse | `&nbsp;` | |
| © | `&copy;` | |

This matters for the project: any text a user typed has to be converted this way before you put it in a page, or a user who types `<script>` gets to run code on your site. In PHP, `htmlspecialchars()` does the conversion.

### The viewport meta tag and a responsive page

Phones pretend to be 980px wide unless you tell them not to. Add this line to every `<head>`, next to the charset:

```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```

Then a couple of CSS habits keep the page usable on a phone:

```css
img { max-width: 100%; height: auto; }   /* images shrink instead of overflowing */
body { max-width: 800px; margin: 0 auto; padding: 16px; }
```

### Laying things out with Flexbox

Flexbox turns a container into a row (or column) whose children share the space. It replaces tables-for-layout and the old `align` attributes. The two lines you'll write most:

```css
nav {
  display: flex;      /* children line up in a row */
  gap: 16px;          /* space between them */
}
```

A common pattern, a row of cards that wraps onto new lines when the screen is narrow:

```html
<div class="cards">
  <article class="card">...</article>
  <article class="card">...</article>
  <article class="card">...</article>
</div>
```

```css
.cards { display: flex; flex-wrap: wrap; gap: 16px; }
.card  { flex: 1 1 200px; border: 1px solid #ccc; padding: 12px; }
```

`flex: 1 1 200px` means "start at 200px wide, and grow or shrink to fill the row." See [Flexbox](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/CSS_layout/Flexbox) for the rest.

### More selectors you'll actually use

| Selector | Matches | Example |
|---|---|---|
| `nav a` | every `<a>` inside a `<nav>` (descendant) | `nav a { text-decoration: none; }` |
| `a:hover` | a link the mouse is over | `a:hover { color: red; }` |
| `input:focus` | the field being typed in | `input:focus { border-color: blue; }` |
| `tr:nth-child(even)` | every second table row | `tr:nth-child(even) { background: #f4f4f4; }` |
| `h1, h2, h3` | all three (a list) | `h1, h2, h3 { font-family: Georgia, serif; }` |
| `.card h2` | headings inside cards only | `.card h2 { margin-top: 0; }` |

### Organising the files of a site

```
project/
├── index.html
├── schedule.html
├── signup.php
├── css/
│   └── style.css
├── images/
│   └── logo.png
└── includes/          (PHP header/footer, later)
```

Link to the stylesheet with a relative path from each page, `href="css/style.css"`. Keep names lowercase with no spaces: `team-photo.jpg`, not `Team Photo.JPG`. Web servers are case-sensitive even when your laptop isn't.

### Use the browser's developer tools

Right-click any element on any web page and choose **Inspect** (or press F12). The Elements panel shows the HTML as the browser understood it, with the CSS rules that apply on the right. You can edit values live and watch the page change. It's the fastest way to learn why something looks the way it does, on your page or anyone else's.

---

## Nice to know

> [!CAUTION]
> **Overlapping tags.** `<b><i>text</b></i>` is wrong. Close tags in the reverse order you opened them. If a page looks broken in one browser and fine in another, this is the first thing to check.

> [!WARNING]
> **A form field without a `name` sends nothing.** The browser only submits fields that have a `name` attribute, and PHP reads the data by that name. Typos here are the number one reason a form "doesn't work". Also, `name` is case-sensitive: `FirstName` and `firstname` are different keys.

> [!IMPORTANT]
> **Always start with `<!DOCTYPE html>` and `<meta charset="utf-8">`.** Without the doctype, browsers switch to "quirks mode" and imitate 1990s bugs, so your CSS behaves unpredictably. Without the charset, accented characters and symbols can show up as garbage.

> [!IMPORTANT]
> **Tables are for data, not layout.** Old tutorials nest tables and wrap text around them to build page layouts. It was the only option then. Today it makes pages fragile, slow to edit, and hard for screen readers. Use CSS for layout ([Flexbox](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/CSS_layout/Flexbox) is the usual starting point).

> [!TIP]
> **Give every image an `alt`.** It's required by the HTML standard, it's what shows if the image is missing, and it's what a screen reader says. If the image is purely decorative, use `alt=""`.

> [!TIP]
> **Validate your pages.** The [W3C Markup Validator](https://validator.w3.org/) catches unclosed tags, missing attributes, and deprecated markup. It's the fastest way to find out why a page renders strangely.

> [!NOTE]
> **`<INPUT ... />` versus `<input ...>`.** Some code writes void elements with a trailing slash (`<input type="text" name="name" />`). That's XHTML style. In HTML5 the slash is allowed but does nothing. Either is fine; just be consistent.

## Practice exercises

1. **A page about you.** Skeleton, one `h1`, two `h2` sections, a paragraph each, a photo with `alt`, and a link to a site you like. Validate it at [validator.w3.org](https://validator.w3.org/).
2. **Lists and a table.** Add an ordered list of three goals for the semester and a table of your courses (course, day, room) with a header row and a footer row that spans all columns.
3. **A contact form.** Name, email, a radio group for "how did you hear about us", a drop-down, a textarea, and a submit button. Every field needs a `name` and a `<label>`. Set `action` to `contact.php` and `method` to `post`, then write the PHP later.
4. **Move the styling out.** Take a page that uses `<font>`, `bgcolor`, `align`, and table `border`/`cellpadding` attributes and rewrite it with a single external stylesheet and no presentational attributes. The page should look the same when you're done.
5. **Make it responsive.** Add the viewport tag, put the navigation links in a Flexbox row, and check the page on your phone (or narrow the browser window). Nothing should scroll sideways.

## Further Reading

| Topic | MDN Web Docs |
|---|---|
| HTML from scratch | [Structuring content](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Structuring_content) |
| All HTML elements | [HTML elements reference](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements) |
| Links | [`<a>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/a) |
| Images | [`<img>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/img) |
| Tables | [HTML table basics](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Structuring_content/HTML_table_basics) |
| Forms | [Your first form](https://developer.mozilla.org/en-US/docs/Learn_web_development/Extensions/Forms/Your_first_form) and [`<input>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/input) |
| CSS from scratch | [Styling basics](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Styling_basics) |
| Selectors | [CSS selectors](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors) |
| Box model | [The box model](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/Styling_basics/Box_model) |
| All CSS properties | [CSS reference](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference) |
| Layout | [CSS layout](https://developer.mozilla.org/en-US/docs/Learn_web_development/Core/CSS_layout) |

| Topic | Other |
|---|---|
| Check your HTML | [W3C Markup Validator](https://validator.w3.org/) |
| Check your CSS | [W3C CSS Validator](https://jigsaw.w3.org/css-validator/) |

