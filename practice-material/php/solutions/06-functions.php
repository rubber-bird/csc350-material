<?php
// Reference solutions - 06 Functions as Values

function greet($name, $greeting = 'Hello') { return "$greeting, $name!"; }
function apply_twice($fn, $value) { return $fn($fn($value)); }
function sum_all(...$numbers) { return array_sum($numbers); }

function make_multiplier($factor) { return fn($n) => $n * $factor; }
function count_where($items, $test) { return count(array_filter($items, $test)); }
function compose($f, $g) { return fn($x) => $f($g($x)); }

function make_counter()
{
    $count = 0;
    return function () use (&$count) {
        $count++;
        return $count;
    };
}
function pipeline(...$fns)
{
    return function ($value) use ($fns) {
        foreach ($fns as $fn) $value = $fn($value);
        return $value;
    };
}
function group_by($items, $key_fn)
{
    $groups = [];
    foreach ($items as $item) {
        $groups[$key_fn($item)][] = $item;
    }
    return $groups;
}
