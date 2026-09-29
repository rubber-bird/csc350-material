<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/05-arrays.php';

describe('05 - Arrays', function () {
    $people = [['name' => 'Ada', 'age' => 36], ['name' => 'Alan', 'age' => 41]];

    describe('easy', function () {
        it('first_item([7, 8, 9]) -> 7', function () {
            expect(first_item([7, 8, 9]))->toEqual(7);
            expect(first_item(['pear', 'fig']))->toEqual('pear');
        });
        it('last_item([7, 8, 9]) -> 9', function () {
            expect(last_item([7, 8, 9]))->toEqual(9);
            expect(last_item(['pear', 'fig']))->toEqual('fig');
        });
        it('double_all([1, 2, 3]) -> [2, 4, 6]', function () {
            expect(double_all([1, 2, 3]))->toEqual([2, 4, 6]);
            expect(double_all([]))->toEqual([]);
        });
        it('has_key(["name" => "Ada"], "name") -> true', function () {
            expect(has_key(['name' => 'Ada'], 'name'))->toEqual(true);
            expect(has_key(['name' => 'Ada'], 'age'))->toEqual(false);
        });
    });

    describe('medium', function () use ($people) {
        it('only_evens([1, 2, 3, 4]) -> [2, 4]', function () {
            expect(only_evens([1, 2, 3, 4]))->toEqual([2, 4]);
            expect(only_evens([1, 3]))->toEqual([]);
        });
        it('names_of(people) -> ["Ada", "Alan"]', function () use ($people) {
            expect(names_of($people))->toEqual(['Ada', 'Alan']);
            expect(names_of([]))->toEqual([]);
        });
        it('total_price(cart) -> 15', function () {
            expect(total_price([['price' => 2.5, 'qty' => 2], ['price' => 10, 'qty' => 1]]))->toEqual(15);
            expect(total_price([]))->toEqual(0);
        });
    });

    describe('hard', function () use ($people) {
        it('word_frequency("the cat and the hat") -> counts', function () {
            expect(word_frequency('the cat and the hat'))->toEqual(['the' => 2, 'cat' => 1, 'and' => 1, 'hat' => 1]);
            expect(word_frequency('Go go GO'))->toEqual(['go' => 3]);
        }, [
            'strtolower() the sentence, then explode(\' \', ...) it into words.',
            'Loop the words. If the word is already a key in $counts, add 1; otherwise set it to 1. array_key_exists($word, $counts) tells you which.',
            'The two branches are $counts[$word]++; and $counts[$word] = 1;',
        ]);
        it('oldest_person(people) -> "Alan"', function () use ($people) {
            expect(oldest_person($people))->toEqual('Alan');
            expect(oldest_person([['name' => 'Solo', 'age' => 5]]))->toEqual('Solo');
        }, [
            'Keep the oldest person seen so far, starting with $oldest = $people[0];',
            'foreach person: if $person[\'age\'] > $oldest[\'age\'], replace $oldest with $person.',
            'Return $oldest[\'name\'], not the whole array.',
        ]);
        it('sort_by_key(rows, "age") sorts a copy', function () {
            $rows = [['n' => 'b', 'age' => 30], ['n' => 'a', 'age' => 20]];
            expect(sort_by_key($rows, 'age'))->toEqual([['n' => 'a', 'age' => 20], ['n' => 'b', 'age' => 30]]);
            expect(sort_by_key([['n' => 'b'], ['n' => 'a']], 'n'))->toEqual([['n' => 'a'], ['n' => 'b']]);
            expect($rows[0]['n'])->toEqual('b');
        }, [
            'usort() sorts in place, so work on a copy: $copy = $rows; usort($copy, ...); return $copy;',
            'The compare function gets two rows and returns negative, zero or positive: fn($a, $b) => $a[$key] <=> $b[$key]',
            'An arrow function sees $key on its own. A function () { } closure would need use ($key).',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
