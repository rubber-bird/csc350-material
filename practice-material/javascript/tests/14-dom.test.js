// These tests need a real web page. Open index.html.
describe("14 - DOM", () => {
  const ex = require("../exercises/14-dom");
  const el = (tag) => document.createElement(tag);

  describe("easy", () => {
    it("createHeading('Welcome') makes an <h1> with that text", () => {
      const h = ex.createHeading("Welcome");
      expect(h.tagName).to.equal("H1");
      expect(h.textContent).to.equal("Welcome");
    });
    it("setText changes the text of an element", () => {
      const p = el("p");
      ex.setText(p, "Hello");
      expect(p.textContent).to.equal("Hello");
    });
    it("addClass adds a class", () => {
      const p = el("p");
      ex.addClass(p, "highlight");
      expect(p.classList.contains("highlight")).to.equal(true);
    });
    it("hide sets display to none", () => {
      const p = el("p");
      ex.hide(p);
      expect(p.style.display).to.equal("none");
    });
    it("appendTo puts the child inside the parent", () => {
      const box = el("div");
      const child = el("span");
      ex.appendTo(box, child);
      expect(box.children.length).to.equal(1);
      expect(box.children[0]).to.equal(child);
    });
  });

  describe("medium", () => {
    it("makeList(['milk', 'eggs']) builds a <ul> with two <li>", () => {
      const ul = ex.makeList(["milk", "eggs"]);
      expect(ul.tagName).to.equal("UL");
      expect(ul.children.length).to.equal(2);
      expect(ul.children[0].tagName).to.equal("LI");
      expect(ul.children[0].textContent).to.equal("milk");
      expect(ul.children[1].textContent).to.equal("eggs");
    });
    it("countChildren counts child elements", () => {
      const ul = el("ul");
      ul.appendChild(el("li"));
      ul.appendChild(el("li"));
      ul.appendChild(el("li"));
      expect(ex.countChildren(ul)).to.equal(3);
      expect(ex.countChildren(el("div"))).to.equal(0);
    });
    it("findByClass returns an array of matching elements", () => {
      const div = el("div");
      div.innerHTML = '<p class="note">a</p><p>b</p><p class="note">c</p>';
      const found = ex.findByClass(div, "note");
      expect(Array.isArray(found)).to.equal(true);
      expect(found.length).to.equal(2);
      expect(found[1].textContent).to.equal("c");
      expect(ex.findByClass(div, "missing").length).to.equal(0);
    });
    it("clearChildren empties an element", () => {
      const ul = el("ul");
      ul.innerHTML = "<li>a</li><li>b</li>";
      ex.clearChildren(ul);
      expect(ul.children.length).to.equal(0);
    });
    it("renderPerson builds a card with an h2 and a p", () => {
      const card = ex.renderPerson({ name: "Sam", age: 30 });
      expect(card.tagName).to.equal("DIV");
      expect(card.classList.contains("person")).to.equal(true);
      expect(card.querySelector("h2").textContent).to.equal("Sam");
      expect(card.querySelector("p").textContent).to.equal("30 years old");
    });
  });

  describe("hard", () => {
    it("onClickSetText only changes the text after a click", () => {
      const btn = el("button");
      const p = el("p");
      ex.onClickSetText(btn, p, "Clicked!");
      expect(p.textContent).to.equal("");
      btn.click();
      expect(p.textContent).to.equal("Clicked!");
    });
    it("bindInput copies the input value on every input event", () => {
      const inp = el("input");
      const p = el("p");
      ex.bindInput(inp, p);
      inp.value = "abc";
      inp.dispatchEvent(new Event("input"));
      expect(p.textContent).to.equal("abc");
      inp.value = "abcd";
      inp.dispatchEvent(new Event("input"));
      expect(p.textContent).to.equal("abcd");
    });
    it("makeCounterWidget builds a working + / - counter", () => {
      const box = el("div");
      ex.makeCounterWidget(box);
      const value = box.querySelector(".value");
      const inc = box.querySelector(".inc");
      const dec = box.querySelector(".dec");
      expect(value.textContent).to.equal("0");
      inc.click();
      inc.click();
      expect(value.textContent).to.equal("2");
      dec.click();
      expect(value.textContent).to.equal("1");
      dec.click();
      dec.click();
      expect(value.textContent).to.equal("-1");
    });
    it("renderTodos renders a list and marks done items", () => {
      const box = el("div");
      ex.renderTodos(box, [{ text: "Buy milk", done: false }, { text: "Study", done: true }]);
      let items = box.querySelectorAll("li");
      expect(items.length).to.equal(2);
      expect(items[0].textContent).to.equal("Buy milk");
      expect(items[0].classList.contains("done")).to.equal(false);
      expect(items[1].textContent).to.equal("Study");
      expect(items[1].classList.contains("done")).to.equal(true);

      ex.renderTodos(box, [{ text: "Only one", done: false }]);
      items = box.querySelectorAll("li");
      expect(items.length).to.equal(1);
      expect(box.querySelectorAll("ul").length).to.equal(1);
    });
    it("filterList hides items that don't match", () => {
      const ul = el("ul");
      ul.innerHTML = "<li>Apple</li><li>Banana</li><li>Cherry</li>";
      ex.filterList(ul, "an");
      expect(ul.children[0].style.display).to.equal("none");
      expect(ul.children[1].style.display).to.equal("");
      expect(ul.children[2].style.display).to.equal("none");
      ex.filterList(ul, "");
      expect(ul.children[0].style.display).to.equal("");
      expect(ul.children[2].style.display).to.equal("");
      ex.filterList(ul, "APPLE");
      expect(ul.children[0].style.display).to.equal("");
      expect(ul.children[1].style.display).to.equal("none");
    });
  });
});
