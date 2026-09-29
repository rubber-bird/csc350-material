// ============================================================
//  14 - DOM (the web page)
// ============================================================
//
//  The DOM is how JavaScript sees the HTML page: a tree of elements.
//  These functions only work in the browser. Run the tests with
//  index.html.
//
//  Creating and changing elements:
//
//    const el = document.createElement("p");   make a new <p>
//    el.textContent = "hi";                     set the text inside
//    el.textContent                             read the text
//    el.classList.add("big");                   add a CSS class
//    el.classList.remove("big");
//    el.classList.toggle("big");                add if missing, remove if present
//    el.classList.contains("big");              true / false
//    el.style.display = "none";                 inline CSS
//    el.setAttribute("id", "main");
//    parent.appendChild(el);                    put el inside parent
//    parent.children                            the child elements
//    parent.innerHTML = "";                     remove everything inside
//
//  Finding elements:
//
//    parent.querySelector(".item")              first match (CSS selector)
//    parent.querySelectorAll(".item")           all matches (a NodeList; use
//                                               Array.from(...) to get an array)
//
//  Reacting to the user:
//
//    button.addEventListener("click", () => { ... });
//    input.addEventListener("input", () => { ... input.value ... });
//
//  Try your widgets for real in playground.html.
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * createHeading(text)
 *
 * Create a new <h1> element containing the given text.
 *
 * Input:   text - a string
 * Output:  an <h1> element (NOT a string)
 *
 * Example:
 *   const h = createHeading("Welcome");
 *   h.tagName       ->  "H1"
 *   h.textContent   ->  "Welcome"
 */
function createHeading(text) {
  // your code here
}

/**
 * setText(el, text)
 *
 * Replace the text inside an existing element.
 *
 * Input:   el   - an element
 *          text - a string
 * Output:  nothing (the element is changed in place)
 *
 * Example:
 *   const p = document.createElement("p");
 *   setText(p, "Hello");
 *   p.textContent  ->  "Hello"
 */
function setText(el, text) {
  // your code here
}

/**
 * addClass(el, className)
 *
 * Add a CSS class to an element.
 *
 * Input:   el        - an element
 *          className - a string
 * Output:  nothing
 *
 * Example:
 *   addClass(p, "highlight");
 *   p.classList.contains("highlight")  ->  true
 */
function addClass(el, className) {
  // your code here
}

/**
 * hide(el)
 *
 * Hide an element by setting its inline style display to "none".
 *
 * Input:   el - an element
 * Output:  nothing
 *
 * Example:
 *   hide(p);
 *   p.style.display  ->  "none"
 */
function hide(el) {
  // your code here
}

/**
 * appendTo(parent, child)
 *
 * Put the child element inside the parent, after any existing children.
 *
 * Input:   parent - an element
 *          child  - an element
 * Output:  nothing
 *
 * Example:
 *   const box = document.createElement("div");
 *   appendTo(box, createHeading("Hi"));
 *   box.children.length  ->  1
 */
function appendTo(parent, child) {
  // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * makeList(items)
 *
 * Build a <ul> with one <li> for each string.
 *
 * Input:   items - an array of strings
 * Output:  a <ul> element
 *
 * Example:
 *   const ul = makeList(["milk", "eggs"]);
 *   ul.tagName                    ->  "UL"
 *   ul.children.length            ->  2
 *   ul.children[0].textContent    ->  "milk"
 */
function makeList(items) {
  // your code here
}

/**
 * countChildren(el)
 *
 * Return how many child ELEMENTS an element has.
 *
 * Input:   el - an element
 * Output:  a number
 *
 * Example:
 *   countChildren(makeList(["a", "b", "c"]))  ->  3
 */
function countChildren(el) {
  // your code here
}

/**
 * findByClass(container, className)
 *
 * Return an ARRAY of every element inside container that has the class.
 *
 * Input:   container - an element
 *          className - a string
 * Output:  an array of elements (possibly empty)
 *
 * Example:
 *   // <div><p class="note">a</p><p>b</p><p class="note">c</p></div>
 *   findByClass(div, "note").length  ->  2
 */
function findByClass(container, className) {
  // your code here
}

/**
 * clearChildren(el)
 *
 * Remove everything inside the element.
 *
 * Input:   el - an element
 * Output:  nothing
 *
 * Example:
 *   const ul = makeList(["a", "b"]);
 *   clearChildren(ul);
 *   ul.children.length  ->  0
 */
function clearChildren(el) {
  // your code here
}

/**
 * renderPerson(person)
 *
 * Build a card for a person:
 *
 *   <div class="person">
 *     <h2>NAME</h2>
 *     <p>AGE years old</p>
 *   </div>
 *
 * Input:   person - an object { name, age }
 * Output:  a <div> element with class "person" and two children
 *
 * Example:
 *   const card = renderPerson({ name: "Sam", age: 30 });
 *   card.className                     ->  "person"
 *   card.querySelector("h2").textContent  ->  "Sam"
 *   card.querySelector("p").textContent   ->  "30 years old"
 */
function renderPerson(person) {
  // your code here
}

// ---------------------------------------------- HARD --------

/**
 * onClickSetText(button, target, text)
 *
 * When the button is clicked, put the text into the target element.
 * Nothing should happen until the click.
 *
 * Input:   button - a <button> element
 *          target - an element
 *          text   - a string
 * Output:  nothing
 *
 * Example:
 *   onClickSetText(btn, p, "Clicked!");
 *   p.textContent   ->  ""            (not yet)
 *   btn.click();
 *   p.textContent   ->  "Clicked!"
 */
function onClickSetText(button, target, text) {
  // your code here
}

/**
 * bindInput(input, output)
 *
 * Whenever the user types in the input, copy its current value into
 * the output element's text. Listen for the "input" event.
 *
 * Input:   input  - an <input> element
 *          output - an element
 * Output:  nothing
 *
 * Example:
 *   bindInput(inp, p);
 *   inp.value = "abc";
 *   inp.dispatchEvent(new Event("input"));   // what the browser does when you type
 *   p.textContent  ->  "abc"
 */
function bindInput(input, output) {
  // your code here
}

/**
 * makeCounterWidget(container)
 *
 * Build a tiny counter inside the container:
 *
 *   <button class="dec">-</button>
 *   <span class="value">0</span>
 *   <button class="inc">+</button>
 *
 * Clicking "+" adds 1 to the number in the span, "-" subtracts 1.
 *
 * Input:   container - an empty element
 * Output:  nothing (the container is filled in)
 *
 * Example:
 *   makeCounterWidget(box);
 *   box.querySelector(".value").textContent  ->  "0"
 *   box.querySelector(".inc").click();
 *   box.querySelector(".inc").click();
 *   box.querySelector(".dec").click();
 *   box.querySelector(".value").textContent  ->  "1"
 */
function makeCounterWidget(container) {
  // your code here
}

/**
 * renderTodos(container, todos)
 *
 * Replace the contents of the container with a <ul> of todos.
 * Each <li> shows the todo text, and has the class "done" when
 * todo.done is true.
 *
 * Input:   container - an element
 *          todos     - an array of { text: string, done: boolean }
 * Output:  nothing
 *
 * Example:
 *   renderTodos(box, [{ text: "Buy milk", done: false }, { text: "Study", done: true }]);
 *   box.querySelectorAll("li").length                 ->  2
 *   box.querySelectorAll("li")[1].textContent         ->  "Study"
 *   box.querySelectorAll("li")[1].classList.contains("done")  ->  true
 *   box.querySelectorAll("li")[0].classList.contains("done")  ->  false
 *   // calling it again replaces the old list instead of adding a second one
 */
function renderTodos(container, todos) {
  // your code here
}

/**
 * filterList(ul, query)
 *
 * Hide every <li> whose text does NOT contain the query (ignore case),
 * and show every <li> that does. Use style.display = "none" to hide
 * and style.display = "" to show.
 *
 * Input:   ul    - a <ul> element with <li> children
 *          query - a string ("" shows everything)
 * Output:  nothing
 *
 * Example:
 *   const ul = makeList(["Apple", "Banana", "Cherry"]);
 *   filterList(ul, "an");
 *   ul.children[0].style.display  ->  "none"     (Apple)
 *   ul.children[1].style.display  ->  ""         (Banana)
 *   ul.children[2].style.display  ->  "none"     (Cherry)
 */
function filterList(ul, query) {
  // your code here
}

// Leave this line alone. It makes the functions visible to the tests.
module.exports = {
  createHeading, setText, addClass, hide, appendTo,
  makeList, countChildren, findByClass, clearChildren, renderPerson,
  onClickSetText, bindInput, makeCounterWidget, renderTodos, filterList,
};
