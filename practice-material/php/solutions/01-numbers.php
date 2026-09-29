<?php
// Reference solutions - 01 Numbers and Arithmetic

function add($a, $b) { return $a + $b; }
function subtract($a, $b) { return $a - $b; }
function multiply($a, $b) { return $a * $b; }
function remainder($a, $b) { return $a % $b; }

function square($n) { return $n * $n; }
function average_of_three($a, $b, $c) { return ($a + $b + $c) / 3; }
function is_divisible($a, $b) { return $a % $b === 0; }
function celsius_to_fahrenheit($c) { return $c * 9 / 5 + 32; }

function clamp($n, $min, $max)
{
    if ($n < $min) return $min;
    if ($n > $max) return $max;
    return $n;
}
function percent_of($part, $whole) { return round($part / $whole * 100, 1); }
function seconds_to_clock($total_seconds)
{
    $minutes = intdiv($total_seconds, 60);
    $seconds = $total_seconds % 60;
    return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
}
