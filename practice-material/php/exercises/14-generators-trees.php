<?php
// ============================================================
//  14 - Generators, trees and caches
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 14)
//
//  Three things that show up once a program has real amounts of data.
//
//  Generators produce values one at a time, on demand, with `yield`.
//  A generator that reads a file gives you line after line without
//  ever holding the whole file in memory, and it can even be infinite.
//
//    function evens() {            // a generator function
//        $n = 0;
//        while (true) {
//            yield $n;             // hand out one value, then pause here
//            $n += 2;
//        }
//    }
//    foreach (evens() as $e) { if ($e > 6) break; echo $e; }   // 0 2 4 6
//
//  Trees: categories, menus, comment threads and file systems are all
//  rows with a parent_id that need to become nested arrays, and back.
//
//  Caches remember results so the same work is not done twice.
//
//  Docs:
//    generators           https://www.php.net/manual/en/language.generators.overview.php
//    yield                https://www.php.net/manual/en/language.generators.syntax.php
//    iterator_to_array    https://www.php.net/iterator_to_array
//    fopen / fgets        https://www.php.net/fgets
//    htmlspecialchars     https://www.php.net/htmlspecialchars
//    references (&)       https://www.php.net/manual/en/language.references.php
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * naturals()
 *
 * An infinite generator: 1, 2, 3, 4, ...
 * (It is only useful together with take() below.)
 *
 * Examples:
 *   foreach (naturals() as $n) { if ($n > 3) break; echo $n; }   // 123
 */
function naturals()
{
    // your code here
}

/**
 * take($iterable, $n)
 *
 * A generator that yields the first $n values of anything iterable
 * (an array or another generator) and then stops, so it is safe on an
 * infinite generator. Keys are renumbered from 0.
 *
 * Examples:
 *   iterator_to_array(take(naturals(), 3))       ->  [1, 2, 3]
 *   iterator_to_array(take(['a', 'b', 'c'], 2))  ->  ['a', 'b']
 *   iterator_to_array(take([], 5))               ->  []
 */
function take($iterable, $n)
{
    // your code here
}

/**
 * read_lines($path)
 *
 * A generator that yields the lines of a file one at a time, without
 * the trailing newline, reading the file as it goes (fopen + fgets,
 * not file() or file_get_contents). An empty file yields nothing.
 *
 * Examples:
 *   (file contains "a\nb\nc\n")
 *   iterator_to_array(read_lines($path))  ->  ['a', 'b', 'c']
 */
function read_lines($path)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * chunked($iterable, $size)
 *
 * A generator that groups values into arrays of $size. The last chunk
 * may be smaller. Works on generators too.
 *
 * Examples:
 *   iterator_to_array(chunked([1, 2, 3, 4, 5], 2))  ->  [[1, 2], [3, 4], [5]]
 *   iterator_to_array(chunked([], 3))               ->  []
 *   iterator_to_array(take(chunked(naturals(), 3), 2))  ->  [[1, 2, 3], [4, 5, 6]]
 */
function chunked($iterable, $size)
{
    // your code here
}

/**
 * lazy_map($iterable, $fn)
 *
 * Like array_map, but a generator: $fn runs on each value only when
 * that value is asked for. Mapping an infinite generator must not hang.
 *
 * Examples:
 *   iterator_to_array(take(lazy_map(naturals(), fn($n) => $n * $n), 4))  ->  [1, 4, 9, 16]
 *   $calls = 0;
 *   $g = lazy_map([1, 2, 3], function ($n) use (&$calls) { $calls++; return $n; });
 *   $calls   ->  0          (nothing has run yet)
 *   iterator_to_array($g);   $calls  ->  3
 */
function lazy_map($iterable, $fn)
{
    // your code here
}

/**
 * build_tree($rows)
 *
 * Turn flat rows with a parent_id into a nested tree. Each node in the
 * result is the row plus a 'children' list. Roots have parent_id null.
 * Order among siblings is the order of the rows. Rows may arrive in
 * any order (a child before its parent).
 *
 * Input:   a list of ['id' => .., 'parent_id' => .. or null, 'name' => ..]
 * Output:  a list of root nodes
 *
 * Examples:
 *   build_tree([
 *     ['id' => 1, 'parent_id' => null, 'name' => 'Electronics'],
 *     ['id' => 2, 'parent_id' => 1,    'name' => 'Phones'],
 *     ['id' => 3, 'parent_id' => null, 'name' => 'Books'],
 *   ])
 *   ->  [
 *     ['id' => 1, 'parent_id' => null, 'name' => 'Electronics', 'children' => [
 *         ['id' => 2, 'parent_id' => 1, 'name' => 'Phones', 'children' => []],
 *     ]],
 *     ['id' => 3, 'parent_id' => null, 'name' => 'Books', 'children' => []],
 *   ]
 *
 * Hint: first index every node by id (with an empty children list),
 * then loop again and attach each node to its parent BY REFERENCE
 * (&$nodes[$parent]['children'][]), or build children lists per parent
 * id and assemble recursively.
 */
function build_tree($rows)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * render_tree($nodes)
 *
 * A nested tree as nested HTML lists. A node with children gets a
 * <ul> inside its <li>; one without does not. Names are escaped.
 * An empty list renders as "".
 *
 * Examples:
 *   render_tree(build_tree($rows_above))
 *     ->  "<ul><li>Electronics<ul><li>Phones</li></ul></li><li>Books</li></ul>"
 *   render_tree([['name' => 'a<b', 'children' => []]])  ->  "<ul><li>a&lt;b</li></ul>"
 */
function render_tree($nodes)
{
    // your code here
}

/**
 * breadcrumb($rows, $id)
 *
 * The names from the root down to the node with this id, using the
 * flat rows. Unknown id: []. A cycle in the data must not hang: stop
 * when an id repeats.
 *
 * Examples:
 *   $rows = [[1, null, 'Electronics'], [2, 1, 'Phones'], [3, 2, 'Android']]  (as assoc rows)
 *   breadcrumb($rows, 3)   ->  ['Electronics', 'Phones', 'Android']
 *   breadcrumb($rows, 1)   ->  ['Electronics']
 *   breadcrumb($rows, 99)  ->  []
 */
function breadcrumb($rows, $id)
{
    // your code here
}

/**
 * LruCache
 *
 * A cache that holds at most $capacity entries. When full, adding a
 * new key evicts the LEAST RECENTLY USED one: both get() and put()
 * count as use. keys() lists keys from least to most recently used.
 *
 * Examples:
 *   $c = new LruCache(2);
 *   $c->put('a', 1); $c->put('b', 2);
 *   $c->get('a')          ->  1          (a is now most recent)
 *   $c->put('c', 3);                     (evicts b)
 *   $c->get('b')          ->  null
 *   $c->keys()            ->  ['a', 'c']
 *   $c->has('a')          ->  true
 *
 * Hint: a PHP array keeps insertion order. unset + re-add moves a key
 * to the end; array_key_first is the oldest.
 */
class LruCache
{
    private array $items = [];

    public function __construct(private int $capacity) {}

    public function get(string $key): mixed
    {
        // your code here
    }

    public function put(string $key, mixed $value): void
    {
        // your code here
    }

    public function has(string $key): bool
    {
        // your code here
    }

    public function keys(): array
    {
        // your code here
    }
}

/**
 * memoize_ttl($fn, $ttl, $clock)
 *
 * Return a function that remembers $fn's result per set of arguments
 * for $ttl seconds. $clock is a function returning the current time
 * (so the tests can move time). After $ttl seconds a call runs $fn
 * again and starts a new period.
 *
 * Examples:
 *   $now = 1000;
 *   $slow = fn($n) => $n * 2;                     (pretend it is slow)
 *   $fast = memoize_ttl($slow, 60, function () use (&$now) { return $now; });
 *   $fast(2)   ->  4       (runs $slow)
 *   $fast(2)   ->  4       (cached: $slow not run)
 *   $fast(3)   ->  6       (different args: runs $slow)
 *   $now = 1061;
 *   $fast(2)   ->  4       (expired: runs $slow again)
 *
 * Hint: serialize($args) or json_encode($args) makes a cache key.
 */
function memoize_ttl($fn, $ttl, $clock)
{
    // your code here
}
