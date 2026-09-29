<?php
// ============================================================
//  08 - Rendering HTML
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   See it live:  http://localhost/php-fundamentals/playground.php
//                   (or in a terminal:  php run.php 08)
//
//  This is what PHP was made for: turning data into a web page.
//  Every function here returns a string of HTML. playground.php calls
//  them with real data and shows the result in the browser, so open it
//  next to the tests and watch the page fill in as you go.
//
//  Building strings:
//    "<h1>$text</h1>"                       double quotes fill in variables
//    '<a href="' . $url . '">' . $text     or join pieces with a dot
//    $html .= "<li>$item</li>";             .= adds to the end of a string
//
//  Whitespace between tags does not matter to the tests.
//
//  Functions you will need (click for the manual):
//    htmlspecialchars($s)     make text safe to put in HTML     https://www.php.net/htmlspecialchars
//    number_format($n, 2)     12.5 -> "12.50"                   https://www.php.net/number_format
//    implode("", $parts)      join an array of strings          https://www.php.net/implode
//    array_keys($row)         the keys of an array              https://www.php.net/array_keys
//    ucfirst($s)              "name" -> "Name"                  https://www.php.net/ucfirst
//    $arr[$key] ?? "default"  value, or default if missing      https://www.php.net/manual/en/language.operators.null.php
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * render_heading($text, $level = 1)
 *
 * Wrap the text in a heading tag. The level is optional and
 * defaults to 1.
 *
 * Input:   $text  - a string
 *          $level - a whole number from 1 to 6 (optional)
 * Output:  an HTML string
 *
 * Examples:
 *   render_heading("Welcome")     ->  "<h1>Welcome</h1>"
 *   render_heading("About", 2)    ->  "<h2>About</h2>"
 */
function render_heading($text, $level = 1)
{
    // your code here
}

/**
 * render_link($url, $text)
 *
 * Build an anchor tag.
 *
 * Input:   $url  - a string
 *          $text - a string
 * Output:  an HTML string
 *
 * Examples:
 *   render_link("/about", "About us")  ->  '<a href="/about">About us</a>'
 */
function render_link($url, $text)
{
    // your code here
}

/**
 * render_list($items)
 *
 * Build an unordered list with one <li> per item.
 *
 * Input:   $items - an array of strings
 * Output:  an HTML string
 *
 * Examples:
 *   render_list(["Milk", "Eggs"])  ->  "<ul><li>Milk</li><li>Eggs</li></ul>"
 *   render_list([])                ->  "<ul></ul>"
 */
function render_list($items)
{
    // your code here
}

/**
 * render_greeting($name)
 *
 * A paragraph that greets someone by name. If the name is empty,
 * greet "stranger" instead.
 *
 * Input:   $name - a string, possibly ""
 * Output:  an HTML string
 *
 * Examples:
 *   render_greeting("Ada")  ->  "<p>Hello, Ada!</p>"
 *   render_greeting("")     ->  "<p>Hello, stranger!</p>"
 */
function render_greeting($name)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * render_nav($links, $current)
 *
 * A navigation bar. $links maps a URL to its label. The link whose
 * URL equals $current gets class="active".
 *
 * Input:   $links   - an array of url => label
 *          $current - the URL of the current page
 * Output:  an HTML string
 *
 * Examples:
 *   render_nav(["/" => "Home", "/about" => "About"], "/about")
 *     ->  '<nav><a href="/">Home</a><a href="/about" class="active">About</a></nav>'
 *
 * Docs: https://www.php.net/manual/en/control-structures.foreach.php
 */
function render_nav($links, $current)
{
    // your code here
}

/**
 * render_table($rows)
 *
 * A table from a list of rows that all have the same keys. The header
 * row uses the keys of the first row with the first letter capitalized.
 *
 * Input:   $rows - an array of arrays with named keys, at least one row
 * Output:  an HTML string
 *
 * Examples:
 *   render_table([
 *       ["name" => "Ada", "age" => 36],
 *       ["name" => "Alan", "age" => 41],
 *   ])
 *     ->  "<table>
 *            <tr><th>Name</th><th>Age</th></tr>
 *            <tr><td>Ada</td><td>36</td></tr>
 *            <tr><td>Alan</td><td>41</td></tr>
 *          </table>"
 *
 * Docs: https://www.php.net/array_keys   https://www.php.net/ucfirst
 */
function render_table($rows)
{
    // your code here
}

/**
 * render_product_card($product)
 *
 * A card for one product. When stock is 0, add a "Sold out" span and
 * give the card the extra class "sold-out". Prices show two decimals.
 *
 * Input:   $product - ["name" => ..., "price" => ..., "stock" => ...]
 * Output:  an HTML string
 *
 * Examples:
 *   render_product_card(["name" => "Mug", "price" => 8.5, "stock" => 3])
 *     ->  '<div class="card"><h3>Mug</h3><p class="price">$8.50</p></div>'
 *
 *   render_product_card(["name" => "Hat", "price" => 20, "stock" => 0])
 *     ->  '<div class="card sold-out"><h3>Hat</h3><p class="price">$20.00</p><span class="badge">Sold out</span></div>'
 *
 * Docs: https://www.php.net/number_format
 */
function render_product_card($product)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * escape_html($text)
 *
 * Make user-typed text safe to put inside HTML, so that "<b>" shows
 * up on the page as the characters < b > instead of turning bold.
 * PHP has a function for exactly this.
 *
 * Input:   $text - a string
 * Output:  a string with < > & " replaced by their HTML codes
 *
 * Examples:
 *   escape_html("<b>hi</b>")      ->  "&lt;b&gt;hi&lt;/b&gt;"
 *   escape_html("Tom & Jerry")    ->  "Tom &amp; Jerry"
 *   escape_html('say "hi"')       ->  "say &quot;hi&quot;"
 *
 * Docs: https://www.php.net/htmlspecialchars
 */
function escape_html($text)
{
    // your code here
}

/**
 * render_form($fields, $old = [])
 *
 * A form with one labelled text input per field. $fields maps the
 * input's name to its label. $old holds values typed last time, so
 * the form can be refilled after a submit: use each one as the
 * input's value, escaped with escape_html(). Missing values are "".
 *
 * Input:   $fields - an array of name => label
 *          $old    - an array of name => previous value (optional)
 * Output:  an HTML string
 *
 * Examples:
 *   render_form(["email" => "Email"])
 *     ->  '<form method="post">
 *            <label>Email <input type="text" name="email" value=""></label>
 *            <button>Send</button>
 *          </form>'
 *
 *   render_form(["name" => "Name", "city" => "City"], ["name" => "Ada <3"])
 *     ->  '<form method="post">
 *            <label>Name <input type="text" name="name" value="Ada &lt;3"></label>
 *            <label>City <input type="text" name="city" value=""></label>
 *            <button>Send</button>
 *          </form>'
 *
 * Docs: https://www.php.net/manual/en/language.operators.null.php  (the ?? operator)
 */
function render_form($fields, $old = [])
{
    // your code here
}

/**
 * render_receipt($order)
 *
 * A receipt with one row per line and a total at the bottom.
 * Each line shows "qty x name" and the line price (price times qty).
 * All money shows two decimals.
 *
 * Input:   $order - an array of ["name" => ..., "price" => ..., "qty" => ...]
 * Output:  an HTML string
 *
 * Examples:
 *   render_receipt([
 *       ["name" => "Coffee", "price" => 3.5, "qty" => 2],
 *       ["name" => "Bagel", "price" => 2, "qty" => 1],
 *   ])
 *     ->  '<table class="receipt">
 *            <tr><td>2 x Coffee</td><td>$7.00</td></tr>
 *            <tr><td>1 x Bagel</td><td>$2.00</td></tr>
 *            <tr class="total"><td>Total</td><td>$9.00</td></tr>
 *          </table>'
 */
function render_receipt($order)
{
    // your code here
}
