<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/08-html.php';

describe('08 - Rendering HTML', function () {
    describe('easy', function () {
        it('render_heading("Welcome") -> <h1>Welcome</h1>', function () {
            expect_html(render_heading('Welcome'))->toMatchHtml('<h1>Welcome</h1>');
            expect_html(render_heading('About', 2))->toMatchHtml('<h2>About</h2>');
        });
        it('render_link("/about", "About us") -> <a href="/about">About us</a>', function () {
            expect_html(render_link('/about', 'About us'))->toMatchHtml('<a href="/about">About us</a>');
        });
        it('render_list(["Milk", "Eggs"]) -> <ul><li>Milk</li><li>Eggs</li></ul>', function () {
            expect_html(render_list(['Milk', 'Eggs']))->toMatchHtml('<ul><li>Milk</li><li>Eggs</li></ul>');
            expect_html(render_list([]))->toMatchHtml('<ul></ul>');
        });
        it('render_greeting("") -> <p>Hello, stranger!</p>', function () {
            expect_html(render_greeting('Ada'))->toMatchHtml('<p>Hello, Ada!</p>');
            expect_html(render_greeting(''))->toMatchHtml('<p>Hello, stranger!</p>');
        });
    });

    describe('medium', function () {
        it('render_nav(links, "/about") marks the active link', function () {
            expect_html(render_nav(['/' => 'Home', '/about' => 'About'], '/about'))
                ->toMatchHtml('<nav><a href="/">Home</a><a href="/about" class="active">About</a></nav>');
            expect_html(render_nav(['/' => 'Home'], '/'))
                ->toMatchHtml('<nav><a href="/" class="active">Home</a></nav>');
        });
        it('render_table(rows) builds header from keys', function () {
            expect_html(render_table([['name' => 'Ada', 'age' => 36], ['name' => 'Alan', 'age' => 41]]))
                ->toMatchHtml('<table><tr><th>Name</th><th>Age</th></tr><tr><td>Ada</td><td>36</td></tr><tr><td>Alan</td><td>41</td></tr></table>');
            expect_html(render_table([['city' => 'Oslo']]))
                ->toMatchHtml('<table><tr><th>City</th></tr><tr><td>Oslo</td></tr></table>');
        });
        it('render_product_card(product) shows Sold out when stock is 0', function () {
            expect_html(render_product_card(['name' => 'Mug', 'price' => 8.5, 'stock' => 3]))
                ->toMatchHtml('<div class="card"><h3>Mug</h3><p class="price">$8.50</p></div>');
            expect_html(render_product_card(['name' => 'Hat', 'price' => 20, 'stock' => 0]))
                ->toMatchHtml('<div class="card sold-out"><h3>Hat</h3><p class="price">$20.00</p><span class="badge">Sold out</span></div>');
        });
    });

    describe('hard', function () {
        it('escape_html("<b>hi</b>") -> &lt;b&gt;hi&lt;/b&gt;', function () {
            expect(escape_html('<b>hi</b>'))->toEqual('&lt;b&gt;hi&lt;/b&gt;');
            expect(escape_html('Tom & Jerry'))->toEqual('Tom &amp; Jerry');
            expect(escape_html('say "hi"'))->toEqual('say &quot;hi&quot;');
        }, [
            'PHP has a built-in function for exactly this job. Its name starts with html.',
            'htmlspecialchars($text) turns < > & " into &lt; &gt; &amp; &quot; which the browser shows as plain characters.',
            'return htmlspecialchars($text);',
        ]);
        it('render_form(fields, old) refills and escapes values', function () {
            expect_html(render_form(['email' => 'Email']))
                ->toMatchHtml('<form method="post"><label>Email <input type="text" name="email" value=""></label><button>Send</button></form>');
            expect_html(render_form(['name' => 'Name', 'city' => 'City'], ['name' => 'Ada <3']))
                ->toMatchHtml('<form method="post"><label>Name <input type="text" name="name" value="Ada &lt;3"></label><label>City <input type="text" name="city" value=""></label><button>Send</button></form>');
        }, [
            'Start with $html = \'<form method="post">\'; then foreach ($fields as $name => $label).',
            'The old value may be missing, so: $value = escape_html($old[$name] ?? \'\');',
            'Each field: $html .= "<label>$label <input type=\\"text\\" name=\\"$name\\" value=\\"$value\\"></label>";  After the loop add the button and close the form.',
        ]);
        it('render_receipt(order) totals the lines', function () {
            expect_html(render_receipt([
                ['name' => 'Coffee', 'price' => 3.5, 'qty' => 2],
                ['name' => 'Bagel', 'price' => 2, 'qty' => 1],
            ]))->toMatchHtml('<table class="receipt"><tr><td>2 x Coffee</td><td>$7.00</td></tr><tr><td>1 x Bagel</td><td>$2.00</td></tr><tr class="total"><td>Total</td><td>$9.00</td></tr></table>');
            expect_html(render_receipt([]))
                ->toMatchHtml('<table class="receipt"><tr class="total"><td>Total</td><td>$0.00</td></tr></table>');
        }, [
            'Keep $total = 0; and add each line\'s price * qty to it inside the loop.',
            'number_format($amount, 2) gives two decimals. The dollar sign is just a character: \'$\' . number_format($amount, 2). Inside double quotes write \\$ so PHP does not read it as a variable.',
            'After the loop add the row with class="total" using the same number_format, then close the table.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
