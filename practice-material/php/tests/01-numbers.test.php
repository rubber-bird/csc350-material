<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/01-numbers.php';

describe('01 - Numbers and Arithmetic', function () {
    describe('easy', function () {
        it('add(2, 3) -> 5', function () {
            expect(add(2, 3))->toEqual(5);
            expect(add(-1, 1))->toEqual(0);
        });
        it('subtract(10, 4) -> 6', function () {
            expect(subtract(10, 4))->toEqual(6);
            expect(subtract(0, 5))->toEqual(-5);
        });
        it('multiply(3, 4) -> 12', function () {
            expect(multiply(3, 4))->toEqual(12);
            expect(multiply(7, 0))->toEqual(0);
        });
        it('remainder(10, 3) -> 1', function () {
            expect(remainder(10, 3))->toEqual(1);
            expect(remainder(8, 4))->toEqual(0);
        });
    });

    describe('medium', function () {
        it('square(5) -> 25', function () {
            expect(square(5))->toEqual(25);
            expect(square(-3))->toEqual(9);
        });
        it('average_of_three(1, 2, 3) -> 2', function () {
            expect(average_of_three(1, 2, 3))->toEqual(2);
            expect(average_of_three(10, 20, 60))->toEqual(30);
        });
        it('is_divisible(10, 5) -> true', function () {
            expect(is_divisible(10, 5))->toEqual(true);
            expect(is_divisible(10, 3))->toEqual(false);
        });
        it('celsius_to_fahrenheit(100) -> 212', function () {
            expect(celsius_to_fahrenheit(0))->toEqual(32);
            expect(celsius_to_fahrenheit(100))->toEqual(212);
            expect(celsius_to_fahrenheit(-40))->toEqual(-40);
        });
    });

    describe('hard', function () {
        it('clamp(50, 1, 10) -> 10', function () {
            expect(clamp(5, 1, 10))->toEqual(5);
            expect(clamp(-3, 1, 10))->toEqual(1);
            expect(clamp(50, 1, 10))->toEqual(10);
        }, [
            'Three cases: too small, too big, just right. Write one if for each of the first two and return early.',
            'if ($n < $min) return $min;  handles the first case. Copy the idea for $max with >.',
            'After both ifs, the only thing left is: return $n;',
        ]);
        it('percent_of(1, 3) -> 33.3', function () {
            expect(percent_of(50, 200))->toEqual(25);
            expect(percent_of(1, 3))->toEqual(33.3);
            expect(percent_of(2, 3))->toEqual(66.7);
        }, [
            'A percentage is part divided by whole, times 100.',
            'round($x, 1) rounds to one decimal place. See https://www.php.net/round',
            'return round($part / $whole * 100, 1);',
        ]);
        it('seconds_to_clock(90) -> "1:30"', function () {
            expect(seconds_to_clock(90))->toEqual('1:30');
            expect(seconds_to_clock(5))->toEqual('0:05');
            expect(seconds_to_clock(600))->toEqual('10:00');
        }, [
            'Minutes are the whole-number part of seconds / 60: intdiv($total_seconds, 60). Leftover seconds are the remainder: $total_seconds % 60.',
            'str_pad($seconds, 2, \'0\', STR_PAD_LEFT) turns 5 into "05" and leaves 30 alone.',
            'Join the parts with a dot: return $minutes . \':\' . $padded;',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
