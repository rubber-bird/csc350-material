// Reference solutions - 15 DOM
function createHeading(text) {
  const h = document.createElement("h1");
  h.textContent = text;
  return h;
}
function setText(el, text) { el.textContent = text; }
function addClass(el, className) { el.classList.add(className); }
function hide(el) { el.style.display = "none"; }
function appendTo(parent, child) { parent.appendChild(child); }

function makeList(items) {
  const ul = document.createElement("ul");
  for (const item of items) {
    const li = document.createElement("li");
    li.textContent = item;
    ul.appendChild(li);
  }
  return ul;
}
function countChildren(el) { return el.children.length; }
function findByClass(container, className) {
  return Array.from(container.querySelectorAll("." + className));
}
function clearChildren(el) { el.innerHTML = ""; }
function renderPerson(person) {
  const div = document.createElement("div");
  div.classList.add("person");
  const h2 = document.createElement("h2");
  h2.textContent = person.name;
  const p = document.createElement("p");
  p.textContent = person.age + " years old";
  div.appendChild(h2);
  div.appendChild(p);
  return div;
}

function onClickSetText(button, target, text) {
  button.addEventListener("click", () => { target.textContent = text; });
}
function bindInput(input, output) {
  input.addEventListener("input", () => { output.textContent = input.value; });
}
function makeCounterWidget(container) {
  let count = 0;
  const dec = document.createElement("button");
  dec.className = "dec";
  dec.textContent = "-";
  const value = document.createElement("span");
  value.className = "value";
  value.textContent = "0";
  const inc = document.createElement("button");
  inc.className = "inc";
  inc.textContent = "+";
  inc.addEventListener("click", () => { count++; value.textContent = String(count); });
  dec.addEventListener("click", () => { count--; value.textContent = String(count); });
  container.appendChild(dec);
  container.appendChild(value);
  container.appendChild(inc);
}
function renderTodos(container, todos) {
  container.innerHTML = "";
  const ul = document.createElement("ul");
  for (const todo of todos) {
    const li = document.createElement("li");
    li.textContent = todo.text;
    if (todo.done) li.classList.add("done");
    ul.appendChild(li);
  }
  container.appendChild(ul);
}
function filterList(ul, query) {
  const q = query.toLowerCase();
  for (const li of ul.children) {
    li.style.display = li.textContent.toLowerCase().includes(q) ? "" : "none";
  }
}

module.exports = {
  createHeading, setText, addClass, hide, appendTo,
  makeList, countChildren, findByClass, clearChildren, renderPerson,
  onClickSetText, bindInput, makeCounterWidget, renderTodos, filterList,
};
