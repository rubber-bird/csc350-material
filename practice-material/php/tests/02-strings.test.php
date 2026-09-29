<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/02-strings.php';

describe('02 - Strings', function () {
    describe('easy', function () {
        it('shout("hello") -> "HELLO!"', function () {
            expect(shout('hello'))->toEqual('HELLO!');
            expect(shout('Wow'))->toEqual('WOW!');
        });
        it('whisper("HELLO") -> "hello..."', function () {
            expect(whisper('HELLO'))->toEqual('hello...');
            expect(whisper('Psst'))->toEqual('psst...');
        });
        it('string_length("hello") -> 5', function () {
            expect(string_length('hello'))->toEqual(5);
            expect(string_length(''))->toEqual(0);
        });
        it('repeat_string("ab", 3) -> "ababab"', function () {
            expect(repeat_string('ab', 3))->toEqual('ababab');
            expect(repeat_string('x', 0))->toEqual('');
        });
    });

    describe('medium', function () {
        it('first_and_last("hello") -> "ho"', function () {
            expect(first_and_last('hello'))->toEqual('ho');
            expect(first_and_last('PHP'))->toEqual('PP');
        });
        it('count_vowels("hello") -> 2', function () {
            expect(count_vowels('hello'))->toEqual(2);
            expect(count_vowels('AEIOU'))->toEqual(5);
            expect(count_vowels('xyz'))->toEqual(0);
        });
        it('initials("Ada Lovelace") -> "AL"', function () {
            expect(initials('Ada Lovelace'))->toEqual('AL');
            expect(initials('grace brewster hopper'))->toEqual('GBH');
        });
    });

    describe('hard', function () {
        it('is_palindrome("Never odd or even") -> true', function () {
            expect(is_palindrome('racecar'))->toEqual(true);
            expect(is_palindrome('Never odd or even'))->toEqual(true);
            expect(is_palindrome('hello'))->toEqual(false);
        }, [
            'First clean the text: strtolower() it, then remove spaces with str_replace(\' \', \'\', $text).',
            'strrev() reverses a string. A palindrome is equal to its own reverse.',
            '$clean = strtolower(str_replace(\' \', \'\', $text));  return $clean === strrev($clean);',
        ]);
        it('title_case("hello world") -> "Hello World"', function () {
            expect(title_case('hello world'))->toEqual('Hello World');
            expect(title_case('tHE gREAT gATSBY'))->toEqual('The Great Gatsby');
        }, [
            'Lowercase the whole sentence first, then split it into words with explode(\' \', ...).',
            'ucfirst() capitalizes one word. Apply it to every word with array_map(\'ucfirst\', $words) or a foreach.',
            'implode(\' \', $words) joins the words back with spaces.',
        ]);
        it('censor("I hate homework", "hate") -> "I **** homework"', function () {
            expect(censor('I hate homework', 'hate'))->toEqual('I **** homework');
            expect(censor('Darn it, DARN it', 'darn'))->toEqual('**** it, **** it');
            expect(censor('all good here', 'bad'))->toEqual('all good here');
        }, [
            'str_ireplace($search, $replace, $text) replaces every match and ignores case.',
            'The replacement is asterisks, as many as the word has letters: str_repeat(\'*\', strlen($word)).',
            'return str_ireplace($word, str_repeat(\'*\', strlen($word)), $sentence);',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
