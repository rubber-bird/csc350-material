<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/09-records.php';

describe('09 - Working with records', function () {
    $people = [
        ['id' => 1, 'name' => 'Ada',   'city' => 'London', 'age' => 36],
        ['id' => 2, 'name' => 'Alan',  'city' => 'Leeds',  'age' => 41],
        ['id' => 3, 'name' => 'Grace', 'city' => 'London', 'age' => 36],
        ['id' => 4, 'name' => 'Linus', 'city' => 'York',   'age' => 28],
    ];
    $sales = [
        ['city' => 'Leeds', 'q' => 'Q1', 'amt' => 10],
        ['city' => 'York',  'q' => 'Q2', 'amt' => 5],
        ['city' => 'Leeds', 'q' => 'Q1', 'amt' => 3],
        ['city' => 'Leeds', 'q' => 'Q2', 'amt' => 1],
    ];

    describe('easy', function () use ($people) {
        it('index_by keys records by a field', function () use ($people) {
            $by_id = index_by($people, 'id');
            expect(array_keys($by_id))->toEqual([1, 2, 3, 4]);
            expect($by_id[3]['name'])->toEqual('Grace');
            expect(index_by([], 'id'))->toEqual([]);
        });
        it('pluck lists one field', function () use ($people) {
            expect(pluck($people, 'name'))->toEqual(['Ada', 'Alan', 'Grace', 'Linus']);
            expect(pluck([], 'name'))->toEqual([]);
        });
        it('where filters by strict equality and renumbers the keys', function () use ($people) {
            expect(pluck(where($people, 'city', 'London'), 'name'))->toEqual(['Ada', 'Grace']);
            expect(array_keys(where($people, 'city', 'London')))->toEqual([0, 1]);
            expect(where($people, 'age', '36'))->toEqual([]);
        });
    });

    describe('medium', function () use ($people) {
        it('sort_by sorts by several keys, "-" means descending, and leaves the input alone', function () use ($people) {
            $copy = $people;
            expect(pluck(sort_by($people, 'age', 'name'), 'name'))->toEqual(['Linus', 'Ada', 'Grace', 'Alan']);
            expect(pluck(sort_by($people, '-age', 'name'), 'name'))->toEqual(['Alan', 'Ada', 'Grace', 'Linus']);
            expect(pluck(sort_by($people, 'city', '-name'), 'name'))->toEqual(['Alan', 'Grace', 'Ada', 'Linus']);
            expect($people)->toEqual($copy);
        });
        it('sum_by totals one field per group, in first-seen order', function () {
            $orders = [['city' => 'Leeds', 'total' => 10], ['city' => 'York', 'total' => 5], ['city' => 'Leeds', 'total' => 2.5]];
            expect(sum_by($orders, 'city', 'total'))->toEqual(['Leeds' => 12.5, 'York' => 5]);
            expect(sum_by([], 'city', 'total'))->toEqual([]);
        });
        it('top_n returns the n highest, highest first', function () use ($people) {
            expect(pluck(top_n($people, 'age', 2), 'name'))->toEqual(['Alan', 'Ada']);
            expect(count(top_n($people, 'age', 10)))->toEqual(4);
            expect(top_n([], 'age', 3))->toEqual([]);
        });
    });

    describe('hard', function () use ($sales) {
        it('pivot builds a full two-way table with 0 for missing cells', function () use ($sales) {
            expect(pivot($sales, 'city', 'q', 'amt'))->toEqual([
                'Leeds' => ['Q1' => 13, 'Q2' => 1],
                'York'  => ['Q1' => 0,  'Q2' => 5],
            ]);
            expect(pivot($sales, 'q', 'city', 'amt'))->toEqual([
                'Q1' => ['Leeds' => 13, 'York' => 0],
                'Q2' => ['Leeds' => 1,  'York' => 5],
            ]);
            expect(pivot([], 'a', 'b', 'c'))->toEqual([]);
        }, [
            'First collect the distinct row values and the distinct column values, in order of appearance.',
            'Then build every row as array_fill_keys($columns, 0), and finally loop over the records once more adding each amount into its cell.',
        ]);
        it('paginate slices one page and reports page, pages and total', function () {
            $list = ['a', 'b', 'c', 'd', 'e'];
            expect(paginate($list, 1, 2))->toEqual(['items' => ['a', 'b'], 'page' => 1, 'pages' => 3, 'total' => 5]);
            expect(paginate($list, 3, 2)['items'])->toEqual(['e']);
            expect(paginate($list, 9, 2)['items'])->toEqual([]);
            expect(paginate($list, 1, 5)['pages'])->toEqual(1);
            expect(paginate([], 1, 10))->toEqual(['items' => [], 'page' => 1, 'pages' => 0, 'total' => 0]);
        }, [
            'The offset of page p is ($p - 1) * $per_page; array_slice does the rest.',
            'pages is ceil(total / per_page), cast to int.',
        ]);
        it('deep_merge merges nested settings, replaces lists', function () {
            $defaults = ['debug' => false, 'db' => ['host' => 'localhost', 'port' => 3306, 'opts' => ['a' => 1]], 'tags' => ['x', 'y']];
            $user = ['debug' => true, 'db' => ['port' => 3307, 'opts' => ['b' => 2]], 'tags' => ['z']];
            expect(deep_merge($defaults, $user))->toEqual([
                'debug' => true,
                'db' => ['host' => 'localhost', 'port' => 3307, 'opts' => ['a' => 1, 'b' => 2]],
                'tags' => ['z'],
            ]);
            expect(deep_merge(['a' => ['b' => 1]], ['a' => 5]))->toEqual(['a' => 5]);
            expect(deep_merge(['a' => 5], ['a' => ['b' => 1]]))->toEqual(['a' => ['b' => 1]]);
            expect(deep_merge([], ['a' => 1]))->toEqual(['a' => 1]);
        }, [
            'Loop over $override. For each key decide: recurse, or overwrite.',
            'Recurse only when BOTH sides hold an associative array (is_array and not array_is_list).',
        ]);
        it('flatten_keys joins nested keys with dots', function () {
            expect(flatten_keys(['db' => ['host' => 'x', 'port' => 5], 'debug' => true]))
                ->toEqual(['db.host' => 'x', 'db.port' => 5, 'debug' => true]);
            expect(flatten_keys(['a' => ['b' => ['c' => 1, 'd' => [2, 3]]]]))
                ->toEqual(['a.b.c' => 1, 'a.b.d.0' => 2, 'a.b.d.1' => 3]);
            expect(flatten_keys([]))->toEqual([]);
        }, [
            'For each key, build the path: $prefix === "" ? $key : "$prefix.$key".',
            'If the value is a non-empty array, merge in flatten_keys($value, $path); otherwise store it under $path.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
