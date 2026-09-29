<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/14-generators-trees.php';

describe('14 - Generators, trees and caches', function () {
    $rows = [
        ['id' => 1, 'parent_id' => null, 'name' => 'Electronics'],
        ['id' => 2, 'parent_id' => 1,    'name' => 'Phones'],
        ['id' => 3, 'parent_id' => null, 'name' => 'Books'],
        ['id' => 4, 'parent_id' => 2,    'name' => 'Android'],
        ['id' => 5, 'parent_id' => 1,    'name' => 'Laptops'],
    ];
    $tree = [
        ['id' => 1, 'parent_id' => null, 'name' => 'Electronics', 'children' => [
            ['id' => 2, 'parent_id' => 1, 'name' => 'Phones', 'children' => [
                ['id' => 4, 'parent_id' => 2, 'name' => 'Android', 'children' => []],
            ]],
            ['id' => 5, 'parent_id' => 1, 'name' => 'Laptops', 'children' => []],
        ]],
        ['id' => 3, 'parent_id' => null, 'name' => 'Books', 'children' => []],
    ];

    describe('easy', function () {
        it('naturals() is an infinite generator 1, 2, 3, ...', function () {
            $g = naturals();
            expect($g instanceof Generator)->toEqual(true);
            $seen = [];
            foreach ($g as $n) { if ($n > 5) break; $seen[] = $n; }
            expect($seen)->toEqual([1, 2, 3, 4, 5]);
        });
        it('take() stops after n values, even on an infinite generator', function () {
            expect(iterator_to_array(take(naturals(), 3)))->toEqual([1, 2, 3]);
            expect(iterator_to_array(take(['a', 'b', 'c'], 2)))->toEqual(['a', 'b']);
            expect(iterator_to_array(take([], 5)))->toEqual([]);
            expect(iterator_to_array(take(['x'], 0)))->toEqual([]);
            expect(take([1], 1) instanceof Generator)->toEqual(true);
        });
        it('read_lines() yields the lines of a file without newlines', function () {
            $path = tempnam(sys_get_temp_dir(), 'lines');
            file_put_contents($path, "a\nb\n\nc\n");
            expect(read_lines($path) instanceof Generator)->toEqual(true);
            expect(iterator_to_array(read_lines($path)))->toEqual(['a', 'b', '', 'c']);
            file_put_contents($path, '');
            expect(iterator_to_array(read_lines($path)))->toEqual([]);
            file_put_contents($path, "no newline at end");
            expect(iterator_to_array(read_lines($path)))->toEqual(['no newline at end']);
            unlink($path);
        });
    });

    describe('medium', function () use ($rows, $tree) {
        it('chunked() groups values, last chunk may be short', function () {
            expect(iterator_to_array(chunked([1, 2, 3, 4, 5], 2)))->toEqual([[1, 2], [3, 4], [5]]);
            expect(iterator_to_array(chunked([1, 2, 3, 4], 2)))->toEqual([[1, 2], [3, 4]]);
            expect(iterator_to_array(chunked([], 3)))->toEqual([]);
            expect(iterator_to_array(take(chunked(naturals(), 3), 2)))->toEqual([[1, 2, 3], [4, 5, 6]]);
        });
        it('lazy_map() runs the function only when values are consumed', function () {
            expect(iterator_to_array(take(lazy_map(naturals(), fn($n) => $n * $n), 4)))->toEqual([1, 4, 9, 16]);
            $calls = 0;
            $g = lazy_map([1, 2, 3], function ($n) use (&$calls) { $calls++; return $n * 10; });
            expect($calls)->toEqual(0);
            $first = null;
            foreach ($g as $v) { $first = $v; break; }
            expect($first)->toEqual(10);
            expect($calls)->toEqual(1);
        });
        it('build_tree() nests rows by parent_id, in any input order', function () use ($rows, $tree) {
            expect(build_tree($rows))->toEqual($tree);
            expect(build_tree(array_reverse($rows)))->toEqual([
                ['id' => 3, 'parent_id' => null, 'name' => 'Books', 'children' => []],
                ['id' => 1, 'parent_id' => null, 'name' => 'Electronics', 'children' => [
                    ['id' => 5, 'parent_id' => 1, 'name' => 'Laptops', 'children' => []],
                    ['id' => 2, 'parent_id' => 1, 'name' => 'Phones', 'children' => [
                        ['id' => 4, 'parent_id' => 2, 'name' => 'Android', 'children' => []],
                    ]],
                ]],
            ]);
            expect(build_tree([]))->toEqual([]);
        });
    });

    describe('hard', function () use ($rows, $tree) {
        it('render_tree() produces nested <ul> lists with escaping', function () use ($tree) {
            expect(render_tree($tree))->toEqual(
                '<ul><li>Electronics<ul><li>Phones<ul><li>Android</li></ul></li><li>Laptops</li></ul></li><li>Books</li></ul>'
            );
            expect(render_tree([['name' => 'a<b', 'children' => []]]))->toEqual('<ul><li>a&lt;b</li></ul>');
            expect(render_tree([]))->toEqual('');
        }, [
            'Recursion: render_tree() calls itself for each node\'s children, and returns "" for an empty list, which is exactly what a leaf needs.',
        ]);
        it('breadcrumb() walks parent_id up to the root and survives a cycle', function () use ($rows) {
            expect(breadcrumb($rows, 4))->toEqual(['Electronics', 'Phones', 'Android']);
            expect(breadcrumb($rows, 1))->toEqual(['Electronics']);
            expect(breadcrumb($rows, 99))->toEqual([]);
            $cycle = [['id' => 1, 'parent_id' => 2, 'name' => 'a'], ['id' => 2, 'parent_id' => 1, 'name' => 'b']];
            $result = breadcrumb($cycle, 1);
            expect(count($result) <= 2)->toEqual(true);
        }, [
            'Index rows by id, then loop: prepend the name, move to parent_id, stop at null or at an id already visited.',
        ]);
        it('LruCache evicts the least recently used entry', function () {
            $c = new LruCache(2);
            $c->put('a', 1);
            $c->put('b', 2);
            expect($c->get('a'))->toEqual(1);
            $c->put('c', 3);
            expect($c->get('b'))->toEqual(null);
            expect($c->keys())->toEqual(['a', 'c']);
            expect($c->has('a'))->toEqual(true);
            expect($c->has('b'))->toEqual(false);
            $c->put('a', 10);
            expect($c->keys())->toEqual(['c', 'a']);
            $c->put('d', 4);
            expect($c->keys())->toEqual(['a', 'd']);
            expect($c->get('a'))->toEqual(10);
            $z = new LruCache(1);
            $z->put('x', null);
            expect($z->has('x'))->toEqual(true);
        }, [
            'Keep entries in one array. To mark a key as recently used, unset it and set it again so it moves to the end.',
            'After put(), if count() exceeds the capacity, unset(array_key_first($this->items)).',
        ]);
        it('memoize_ttl() caches per arguments until the ttl passes', function () {
            $now = 1000;
            $runs = 0;
            $slow = function ($n) use (&$runs) { $runs++; return $n * 2; };
            $fast = memoize_ttl($slow, 60, function () use (&$now) { return $now; });
            expect($fast(2))->toEqual(4);
            expect($fast(2))->toEqual(4);
            expect($runs)->toEqual(1);
            expect($fast(3))->toEqual(6);
            expect($runs)->toEqual(2);
            $now = 1059;
            expect($fast(2))->toEqual(4);
            expect($runs)->toEqual(2);
            $now = 1061;
            expect($fast(2))->toEqual(4);
            expect($runs)->toEqual(3);
            $two = memoize_ttl(fn($a, $b) => $a + $b, 10, fn() => 0);
            expect($two(1, 2))->toEqual(3);
            expect($two(2, 1))->toEqual(3);
        }, [
            'Return a closure with ...$args that keeps a $cache array by reference: use ($fn, $ttl, $clock, &$cache).',
            'Store both the value and the time it was computed; it is fresh while $clock() - $at < $ttl.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
