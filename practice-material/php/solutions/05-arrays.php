<?php
// Reference solutions - 05 Arrays

function first_item($items) { return $items[0]; }
function last_item($items) { return $items[count($items) - 1]; }
function double_all($numbers) { return array_map(fn($n) => $n * 2, $numbers); }
function has_key($assoc, $key) { return array_key_exists($key, $assoc); }

function only_evens($numbers)
{
    return array_values(array_filter($numbers, fn($n) => $n % 2 === 0));
}
function names_of($people) { return array_column($people, 'name'); }
function total_price($cart)
{
    $total = 0;
    foreach ($cart as $line) $total += $line['price'] * $line['qty'];
    return $total;
}

function word_frequency($sentence)
{
    $counts = [];
    foreach (explode(' ', strtolower($sentence)) as $word) {
        if (array_key_exists($word, $counts)) $counts[$word]++;
        else $counts[$word] = 1;
    }
    return $counts;
}
function oldest_person($people)
{
    $oldest = $people[0];
    foreach ($people as $person) {
        if ($person['age'] > $oldest['age']) $oldest = $person;
    }
    return $oldest['name'];
}
function sort_by_key($rows, $key)
{
    $copy = $rows;
    usort($copy, fn($a, $b) => $a[$key] <=> $b[$key]);
    return $copy;
}
