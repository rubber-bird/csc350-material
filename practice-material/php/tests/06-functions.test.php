<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/06-functions.php';

describe('06 - Functions as Values', function () {
    describe('easy', function () {
        it('greet("Ada") -> "Hello, Ada!"', function () {
            expect(greet('Ada'))->toEqual('Hello, Ada!');
            expect(greet('Ada', 'Hi'))->toEqual('Hi, Ada!');
        });
        it('apply_twice(double, 5) -> 20', function () {
            expect(apply_twice(fn($n) => $n * 2, 5))->toEqual(20);
            expect(apply_twice(fn($s) => $s . '!', 'hey'))->toEqual('hey!!');
        });
        it('sum_all(1, 2, 3) -> 6', function () {
            expect(sum_all(1, 2, 3))->toEqual(6);
            expect(sum_all(10))->toEqual(10);
            expect(sum_all())->toEqual(0);
        });
    });

    describe('medium', function () {
        it('make_multiplier(3)(5) -> 15', function () {
            $triple = make_multiplier(3);
            expect($triple(5))->toEqual(15);
            expect($triple(0))->toEqual(0);
        });
        it('count_where([1, 2, 3, 4], n > 2) -> 2', function () {
            expect(count_where([1, 2, 3, 4], fn($n) => $n > 2))->toEqual(2);
            expect(count_where(['a', 'bb', 'cc'], fn($s) => strlen($s) === 2))->toEqual(2);
            expect(count_where([], fn($n) => true))->toEqual(0);
        });
        it('compose(add_one, double)(5) -> 11', function () {
            $h = compose(fn($n) => $n + 1, fn($n) => $n * 2);
            expect($h(5))->toEqual(11);
            $shout = compose('strtoupper', fn($s) => $s . '!');
            expect($shout('hi'))->toEqual('HI!');
        });
    });

    describe('hard', function () {
        it('make_counter() counts 1, 2, 3 and each counter is separate', function () {
            $next = make_counter();
            expect($next())->toEqual(1);
            expect($next())->toEqual(2);
            expect($next())->toEqual(3);
            $other = make_counter();
            expect($other())->toEqual(1);
        }, [
            'Make $count = 0; inside make_counter, then return a function that changes it.',
            'Closures copy outside variables. Write use (&$count) so the function shares the real one and the change sticks.',
            'Inside the returned function: $count++; return $count;',
        ]);
        it('pipeline(add_one, times_ten, strval)(4) -> "50"', function () {
            $run = pipeline(fn($n) => $n + 1, fn($n) => $n * 10, 'strval');
            expect($run(4))->toEqual('50');
            $none = pipeline();
            expect($none(7))->toEqual(7);
        }, [
            'Return a function that takes $value. Bring the list in with use ($fns).',
            'foreach ($fns as $fn) $value = $fn($value);  then return $value;',
            'With no functions the loop does nothing and $value comes back unchanged, which is what pipeline() with no arguments must do.',
        ]);
        it('group_by(words, strlen) groups by length', function () {
            expect(group_by(['ant', 'bee', 'cow', 'deer'], fn($w) => strlen($w)))
                ->toEqual([3 => ['ant', 'bee', 'cow'], 4 => ['deer']]);
            expect(group_by([1, 2, 3, 4], fn($n) => $n % 2 === 0 ? 'even' : 'odd'))
                ->toEqual(['odd' => [1, 3], 'even' => [2, 4]]);
        }, [
            'Start with $groups = []; and loop the items.',
            '$key = $key_fn($item); picks the group. Add the item with $groups[$key][] = $item;',
            'PHP creates the inner array the first time a key is used, so no array_key_exists check is needed.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
