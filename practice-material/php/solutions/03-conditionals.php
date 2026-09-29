<?php
// Reference solutions - 03 Conditionals

function is_even($n) { return $n % 2 === 0; }
function is_positive($n) { return $n > 0; }
function max_of_two($a, $b)
{
    if ($a > $b) return $a;
    return $b;
}
function is_adult($age) { return $age >= 18; }

function sign_of($n)
{
    return match (true) {
        $n > 0 => 'positive',
        $n < 0 => 'negative',
        default => 'zero',
    };
}
function grade_letter($score)
{
    if ($score >= 90) return 'A';
    if ($score >= 80) return 'B';
    if ($score >= 70) return 'C';
    if ($score >= 60) return 'D';
    return 'F';
}
function fizz_buzz_one($n)
{
    if ($n % 15 === 0) return 'FizzBuzz';
    if ($n % 3 === 0) return 'Fizz';
    if ($n % 5 === 0) return 'Buzz';
    return (string) $n;
}

function is_leap_year($year)
{
    if ($year % 400 === 0) return true;
    if ($year % 100 === 0) return false;
    return $year % 4 === 0;
}
function ticket_price($age, $is_student)
{
    if ($age < 12) return 5;
    if ($age >= 65) return 7;
    return $is_student ? 10 : 12;
}
function triangle_type($a, $b, $c)
{
    $longest = max($a, $b, $c);
    if ($a + $b + $c - $longest <= $longest) return 'invalid';
    if ($a === $b && $b === $c) return 'equilateral';
    if ($a === $b || $b === $c || $a === $c) return 'isosceles';
    return 'scalene';
}
