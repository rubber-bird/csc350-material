<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/03-conditionals.php';

describe('03 - Conditionals', function () {
    describe('easy', function () {
        it('is_even(4) -> true', function () {
            expect(is_even(4))->toEqual(true);
            expect(is_even(7))->toEqual(false);
            expect(is_even(0))->toEqual(true);
        });
        it('is_positive(5) -> true', function () {
            expect(is_positive(5))->toEqual(true);
            expect(is_positive(-2))->toEqual(false);
            expect(is_positive(0))->toEqual(false);
        });
        it('max_of_two(3, 9) -> 9', function () {
            expect(max_of_two(3, 9))->toEqual(9);
            expect(max_of_two(10, 2))->toEqual(10);
            expect(max_of_two(4, 4))->toEqual(4);
        });
        it('is_adult(18) -> true', function () {
            expect(is_adult(18))->toEqual(true);
            expect(is_adult(30))->toEqual(true);
            expect(is_adult(17))->toEqual(false);
        });
    });

    describe('medium', function () {
        it('sign_of(-3) -> "negative"', function () {
            expect(sign_of(12))->toEqual('positive');
            expect(sign_of(-3))->toEqual('negative');
            expect(sign_of(0))->toEqual('zero');
        });
        it('grade_letter(95) -> "A"', function () {
            expect(grade_letter(95))->toEqual('A');
            expect(grade_letter(80))->toEqual('B');
            expect(grade_letter(75))->toEqual('C');
            expect(grade_letter(60))->toEqual('D');
            expect(grade_letter(59))->toEqual('F');
        });
        it('fizz_buzz_one(15) -> "FizzBuzz"', function () {
            expect(fizz_buzz_one(3))->toEqual('Fizz');
            expect(fizz_buzz_one(10))->toEqual('Buzz');
            expect(fizz_buzz_one(15))->toEqual('FizzBuzz');
            expect(fizz_buzz_one(7))->toEqual('7');
        });
    });

    describe('hard', function () {
        it('is_leap_year(2000) -> true', function () {
            expect(is_leap_year(2024))->toEqual(true);
            expect(is_leap_year(1900))->toEqual(false);
            expect(is_leap_year(2000))->toEqual(true);
            expect(is_leap_year(2023))->toEqual(false);
        }, [
            'Check the most specific rule first: divisible by 400 means true, and you can return right away.',
            'Next: divisible by 100 means false. After those two are handled, divisible by 4 means true.',
            'Two early returns, then the last line: return $year % 4 === 0;',
        ]);
        it('ticket_price(20, true) -> 10', function () {
            expect(ticket_price(8, false))->toEqual(5);
            expect(ticket_price(70, true))->toEqual(7);
            expect(ticket_price(30, false))->toEqual(12);
            expect(ticket_price(20, true))->toEqual(10);
        }, [
            'Age decides first: under 12 pays 5, 65 or over pays 7. Return early in those two cases.',
            'Only the people left after those ifs pay 12, and only they can get the student discount.',
            'Last line: return $is_student ? 10 : 12;',
        ]);
        it('triangle_type(3, 4, 5) -> "scalene"', function () {
            expect(triangle_type(3, 3, 3))->toEqual('equilateral');
            expect(triangle_type(3, 4, 4))->toEqual('isosceles');
            expect(triangle_type(3, 4, 5))->toEqual('scalene');
            expect(triangle_type(1, 2, 3))->toEqual('invalid');
        }, [
            'Find the longest side with max($a, $b, $c). The other two added together must be bigger than it, or the answer is \'invalid\'.',
            'The other two sides add up to $a + $b + $c - $longest.',
            'Then: all three equal is \'equilateral\'; any pair equal (join the three checks with ||) is \'isosceles\'; otherwise \'scalene\'.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
