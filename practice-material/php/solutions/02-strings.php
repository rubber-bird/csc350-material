<?php
// Reference solutions - 02 Strings

function shout($text) { return strtoupper($text) . '!'; }
function whisper($text) { return strtolower($text) . '...'; }
function string_length($text) { return strlen($text); }
function repeat_string($text, $times) { return str_repeat($text, $times); }

function first_and_last($text) { return $text[0] . $text[-1]; }
function count_vowels($text)
{
    $count = 0;
    for ($i = 0; $i < strlen($text); $i++) {
        if (str_contains('aeiou', strtolower($text[$i]))) $count++;
    }
    return $count;
}
function initials($full_name)
{
    $result = '';
    foreach (explode(' ', $full_name) as $word) {
        $result .= strtoupper($word[0]);
    }
    return $result;
}

function is_palindrome($text)
{
    $clean = strtolower(str_replace(' ', '', $text));
    return $clean === strrev($clean);
}
function title_case($sentence)
{
    $words = explode(' ', strtolower($sentence));
    return implode(' ', array_map('ucfirst', $words));
}
function censor($sentence, $word)
{
    return str_ireplace($word, str_repeat('*', strlen($word)), $sentence);
}
