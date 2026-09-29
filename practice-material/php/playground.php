<?php
// A real page built with your functions from exercises/08-html.php.
// Open it at  http://localhost/php-fundamentals/playground.php
//
// Try:   playground.php?name=Ada        the greeting changes
//        playground.php?page=/shop      a different nav link is active
//        type <b>hello</b> into the form and press Send: does it turn bold?
//        It should NOT, if escape_html() and render_form() do their job.

error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/exercises/08-html.php';

$name = $_GET['name'] ?? '';
$page = $_GET['page'] ?? '/';
$old = $_POST;

$products = [
    ['name' => 'Mug', 'price' => 8.5, 'stock' => 3],
    ['name' => 'Hat', 'price' => 20, 'stock' => 0],
    ['name' => 'Poster', 'price' => 12.25, 'stock' => 10],
];
$people = [
    ['name' => 'Ada', 'age' => 36, 'city' => 'London'],
    ['name' => 'Alan', 'age' => 41, 'city' => 'Manchester'],
    ['name' => 'Grace', 'age' => 85, 'city' => 'New York'],
];
$order = [
    ['name' => 'Coffee', 'price' => 3.5, 'qty' => 2],
    ['name' => 'Bagel', 'price' => 2, 'qty' => 1],
    ['name' => 'Juice', 'price' => 4.25, 'qty' => 3],
];

// Calls one of your functions and shows what came back, or a note if
// it returned nothing yet.
function show($title, $callback)
{
    echo "<section class=\"demo\"><h2>$title</h2>";
    try {
        $html = $callback();
        echo $html === null || $html === '' ? '<p class="empty">(nothing returned yet)</p>' : $html;
    } catch (Throwable $e) {
        echo '<p class="error">' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</section>';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Playground - 08 Rendering HTML</title>
<link rel="stylesheet" href="style.css">
<style>
.demo { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 12px 16px; margin: 16px 0; }
.demo h2 { font-size: 1em; color: #666; margin: 0 0 8px; text-transform: uppercase; letter-spacing: .05em; }
.empty { color: #999; font-style: italic; }
.error { color: #b3261e; }
nav a { display: inline-block; margin-right: 8px; padding: 4px 10px; background: #eee; border-radius: 4px; text-decoration: none; color: #222; }
nav a.active { background: #1a7f37; color: #fff; }
table { border-collapse: collapse; }
td, th { border: 1px solid #ccc; padding: 4px 10px; text-align: left; }
tr.total { font-weight: bold; }
.card { display: inline-block; width: 140px; border: 1px solid #ccc; border-radius: 6px; padding: 10px; margin-right: 10px; vertical-align: top; }
.card.sold-out { opacity: .55; }
.card h3 { margin: 0 0 4px; }
.price { margin: 0; color: #1a7f37; font-weight: bold; }
.badge { display: inline-block; margin-top: 6px; padding: 2px 8px; background: #b3261e; color: #fff; border-radius: 10px; font-size: 12px; }
label { display: block; margin: 6px 0; }
</style>
</head>
<body>
<h1>Playground</h1>
<p class="hint">Every box below is the output of one function in <code>exercises/08-html.php</code>.
Edit the file, save, refresh. Try adding <code>?name=Ada</code> to the address.</p>

<?php show('render_heading("Hello from PHP", 2)', fn() => render_heading('Hello from PHP', 2)); ?>
<?php show('render_greeting($_GET["name"])', fn() => render_greeting($name)); ?>
<?php show('render_nav($links, $_GET["page"])', fn() => render_nav(['/' => 'Home', '/shop' => 'Shop', '/about' => 'About'], $page)); ?>
<?php show('render_list($groceries)', fn() => render_list(['Milk', 'Eggs', 'Bread'])); ?>
<?php show('render_link("https://www.php.net", "The PHP manual")', fn() => render_link('https://www.php.net', 'The PHP manual')); ?>
<?php show('render_table($people)', fn() => render_table($people)); ?>
<?php show('render_product_card($product) for each product', function () use ($products) {
    $html = '';
    foreach ($products as $product) $html .= render_product_card($product);
    return $html;
}); ?>
<?php show('render_receipt($order)', fn() => render_receipt($order)); ?>
<?php show('render_form($fields, $_POST)  -  submit it and the values should stay', fn() => render_form(['name' => 'Name', 'comment' => 'Comment'], $old)); ?>
<?php if ($old !== []): ?>
<?php show('escape_html($_POST["comment"])  -  what you typed, made safe', fn() => '<p>' . escape_html($old['comment'] ?? '') . '</p>'); ?>
<?php endif; ?>
</body>
</html>
