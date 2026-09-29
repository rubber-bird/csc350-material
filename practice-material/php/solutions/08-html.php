<?php
// Reference solutions - 08 Rendering HTML

function render_heading($text, $level = 1) { return "<h$level>$text</h$level>"; }
function render_link($url, $text) { return "<a href=\"$url\">$text</a>"; }
function render_list($items)
{
    $html = '<ul>';
    foreach ($items as $item) $html .= "<li>$item</li>";
    return $html . '</ul>';
}
function render_greeting($name)
{
    if ($name === '') $name = 'stranger';
    return "<p>Hello, $name!</p>";
}

function render_nav($links, $current)
{
    $html = '<nav>';
    foreach ($links as $url => $label) {
        $class = $url === $current ? ' class="active"' : '';
        $html .= "<a href=\"$url\"$class>$label</a>";
    }
    return $html . '</nav>';
}
function render_table($rows)
{
    $html = '<table><tr>';
    foreach (array_keys($rows[0]) as $key) $html .= '<th>' . ucfirst($key) . '</th>';
    $html .= '</tr>';
    foreach ($rows as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) $html .= "<td>$cell</td>";
        $html .= '</tr>';
    }
    return $html . '</table>';
}
function render_product_card($product)
{
    $sold_out = $product['stock'] === 0;
    $class = $sold_out ? 'card sold-out' : 'card';
    $price = number_format($product['price'], 2);
    $html = "<div class=\"$class\"><h3>{$product['name']}</h3><p class=\"price\">\$$price</p>";
    if ($sold_out) $html .= '<span class="badge">Sold out</span>';
    return $html . '</div>';
}

function escape_html($text) { return htmlspecialchars($text); }
function render_form($fields, $old = [])
{
    $html = '<form method="post">';
    foreach ($fields as $name => $label) {
        $value = escape_html($old[$name] ?? '');
        $html .= "<label>$label <input type=\"text\" name=\"$name\" value=\"$value\"></label>";
    }
    return $html . '<button>Send</button></form>';
}
function render_receipt($order)
{
    $html = '<table class="receipt">';
    $total = 0;
    foreach ($order as $line) {
        $amount = $line['price'] * $line['qty'];
        $total += $amount;
        $html .= "<tr><td>{$line['qty']} x {$line['name']}</td><td>\$" . number_format($amount, 2) . '</td></tr>';
    }
    $html .= '<tr class="total"><td>Total</td><td>$' . number_format($total, 2) . '</td></tr>';
    return $html . '</table>';
}
