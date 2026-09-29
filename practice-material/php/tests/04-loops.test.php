<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/04-loops.php';

describe('04 - Loops', function () {
    describe('easy', function () {
        it('sum_to(4) -> 10', function () {
            expect(sum_to(4))->toEqual(10);
            expect(sum_to(1))->toEqual(1);
            expect(sum_to(0))->toEqual(0);
        });
        it('count_down(5) -> [5, 4, 3, 2, 1]', function () {
            expect(count_down(5))->toEqual([5, 4, 3, 2, 1]);
            expect(count_down(1))->toEqual([1]);
        });
        it('factorial(5) -> 120', function () {
            expect(factorial(5))->toEqual(120);
            expect(factorial(0))->toEqual(1);
        });
        it('multiples_of(3, 4) -> [3, 6, 9, 12]', function () {
            expect(multiples_of(3, 4))->toEqual([3, 6, 9, 12]);
            expect(multiples_of(5, 0))->toEqual([]);
        });
    });

    describe('medium', function () {
        it('count_evens([1, 2, 3, 4, 6]) -> 3', function () {
            expect(count_evens([1, 2, 3, 4, 6]))->toEqual(3);
            expect(count_evens([1, 3, 5]))->toEqual(0);
            expect(count_evens([]))->toEqual(0);
        });
        it('largest_in([3, 9, 2]) -> 9', function () {
            expect(largest_in([3, 9, 2]))->toEqual(9);
            expect(largest_in([-5, -1, -8]))->toEqual(-1);
        });
        it('sum_of_squares([1, 2, 3]) -> 14', function () {
            expect(sum_of_squares([1, 2, 3]))->toEqual(14);
            expect(sum_of_squares([]))->toEqual(0);
        });
    });

    describe('hard', function () {
        it('fizz_buzz_list(5) -> ["1", "2", "Fizz", "4", "Buzz"]', function () {
            expect(fizz_buzz_list(5))->toEqual(['1', '2', 'Fizz', '4', 'Buzz']);
            $fifteen = fizz_buzz_list(15);
            expect(count($fifteen))->toEqual(15);
            expect($fifteen[14])->toEqual('FizzBuzz');
            expect($fifteen[9])->toEqual('Buzz');
        }, [
            'Start with $result = []; and loop $i from 1 up to $n with a for loop.',
            'Inside, pick the word with if / elseif / else and add it: $result[] = \'Fizz\';',
            'Test 15 (divisible by both) before 3 and before 5. For plain numbers add (string) $i so the array holds strings.',
        ]);
        it('multiplication_table(3) -> [[1,2,3],[2,4,6],[3,6,9]]', function () {
            expect(multiplication_table(3))->toEqual([[1, 2, 3], [2, 4, 6], [3, 6, 9]]);
            expect(multiplication_table(1))->toEqual([[1]]);
        }, [
            'Outer loop: $row from 1 to $n. At the start of each pass make a fresh $cells = [];',
            'Inner loop: $col from 1 to $n, adding $row * $col to $cells.',
            'After the inner loop finishes, add the finished row: $table[] = $cells;',
        ]);
        it('collatz_steps(6) -> 8', function () {
            expect(collatz_steps(1))->toEqual(0);
            expect(collatz_steps(6))->toEqual(8);
            expect(collatz_steps(27))->toEqual(111);
        }, [
            'while ($n !== 1) keeps looping until you reach 1. Count each pass with $steps++.',
            'Even: $n = intdiv($n, 2). Odd: $n = $n * 3 + 1. Use intdiv, not /, so $n stays a whole number.',
            'collatz_steps(1) must be 0. With a while loop the body never runs when $n starts at 1, so that comes free.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
