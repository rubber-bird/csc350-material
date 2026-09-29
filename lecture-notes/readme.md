# CSC 350 - Lecture notes

Notes for each topic in the order they're taught. Every file follows the same layout: Overview, Core Concepts, Important Facts & Definitions, Practical Examples, Nice to know, Further Reading. The first three also have a Going further section and Optional practice exercises.

| # | Topic | Notes | What's inside |
|:---:|---|---|---|
| 1 | HTML & CSS | [html-css.md](html-css.md) | Page structure, text, links, images, lists, tables, forms. Simple CSS rules, selectors, the box model. |
| 2 | JavaScript & DOM manipulation | [javascript-dom.md](javascript-dom.md) | Event handlers, elements, input fields, and JS fundamentals |
| 3 | PHP | [php.md](php.md) | PHP fundamentals, handling forms, functions, and building pages |
| 4 | SQL | [2026-09-24.md](2026-09-24.md) | Creating tables, reading and changing data, and how MySQL runs a query |

## How the topics fit together

```
Browser                                   Server
┌──────────────────────────┐              ┌──────────────────────────┐
│  HTML & CSS  (1)         │   request    │  PHP  (3)                │
│  what the page is and    │ ───────────► │  reads the form, checks  │
│  how it looks            │              │  it, builds the HTML     │
│                          │   response   │           │              │
│  JavaScript  (2)         │ ◄─────────── │           ▼              │
│  reacts to the user,     │              │  MySQL / SQL  (4)        │
│  checks input early      │              │  stores and returns data │
└──────────────────────────┘              └──────────────────────────┘
```

The term project uses all four: HTML and CSS for the pages, JavaScript for instant feedback on forms, PHP to handle registration, login and every database action, and SQL to store it all.
