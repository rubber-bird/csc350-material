<?php
// Reference solutions - 04 Loops

function sum_to($n)
{
    $total = 0;
    for ($i = 1; $i <= $n; $i++) $total += $i;
    return $total;
}
function count_down($n)
{
    $result = [];
    for ($i = $n; $i >= 1; $i--) $result[] = $i;
    return $result;
}
function factorial($n)
{
    $product = 1;
    for ($i = 2; $i <= $n; $i++) $product *= $i;
    return $product;
}
function multiples_of($n, $count)
{
    $result = [];
    for ($i = 1; $i <= $count; $i++) $result[] = $n * $i;
    return $result;
}

function count_evens($numbers)
{
    $count = 0;
    foreach ($numbers as $number) {
        if ($number % 2 === 0) $count++;
    }
    return $count;
}
function largest_in($numbers)
{
    $largest = $numbers[0];
    foreach ($numbers as $number) {
        if ($number > $largest) $largest = $number;
    }
    return $largest;
}
function sum_of_squares($numbers)
{
    $total = 0;
    foreach ($numbers as $number) $total += $number * $number;
    return $total;
}

function fizz_buzz_list($n)
{
    $result = [];
    for ($i = 1; $i <= $n; $i++) {
        if ($i % 15 === 0) $result[] = 'FizzBuzz';
        elseif ($i % 3 === 0) $result[] = 'Fizz';
        elseif ($i % 5 === 0) $result[] = 'Buzz';
        else $result[] = (string) $i;
    }
    return $result;
}
function multiplication_table($n)
{
    $table = [];
    for ($row = 1; $row <= $n; $row++) {
        $cells = [];
        for ($col = 1; $col <= $n; $col++) $cells[] = $row * $col;
        $table[] = $cells;
    }
    return $table;
}
function collatz_steps($n)
{
    $steps = 0;
    while ($n !== 1) {
        $n = $n % 2 === 0 ? intdiv($n, 2) : $n * 3 + 1;
        $steps++;
    }
    return $steps;
}
