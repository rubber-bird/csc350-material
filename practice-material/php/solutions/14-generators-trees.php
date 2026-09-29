<?php
// 14 - Generators, trees and caches: reference solution

function naturals()
{
    $n = 1;
    while (true) {
        yield $n++;
    }
}

function take($iterable, $n)
{
    if ($n <= 0) return;
    $i = 0;
    foreach ($iterable as $value) {
        yield $value;
        if (++$i >= $n) return;
    }
}

function read_lines($path)
{
    $handle = fopen($path, 'r');
    try {
        while (($line = fgets($handle)) !== false) {
            yield rtrim($line, "\r\n");
        }
    } finally {
        fclose($handle);
    }
}

function chunked($iterable, $size)
{
    $chunk = [];
    foreach ($iterable as $value) {
        $chunk[] = $value;
        if (count($chunk) === $size) {
            yield $chunk;
            $chunk = [];
        }
    }
    if ($chunk) yield $chunk;
}

function lazy_map($iterable, $fn)
{
    foreach ($iterable as $value) {
        yield $fn($value);
    }
}

function build_tree($rows)
{
    $nodes = [];
    foreach ($rows as $row) {
        $nodes[$row['id']] = $row + ['children' => []];
    }
    $roots = [];
    foreach ($rows as $row) {
        $id = $row['id'];
        if ($row['parent_id'] === null || !isset($nodes[$row['parent_id']])) {
            $roots[] = &$nodes[$id];
        } else {
            $nodes[$row['parent_id']]['children'][] = &$nodes[$id];
        }
    }
    return $roots;
}

function render_tree($nodes)
{
    if (count($nodes) === 0) return '';
    $html = '<ul>';
    foreach ($nodes as $node) {
        $html .= '<li>' . htmlspecialchars($node['name']) . render_tree($node['children']) . '</li>';
    }
    return $html . '</ul>';
}

function breadcrumb($rows, $id)
{
    $by_id = [];
    foreach ($rows as $row) $by_id[$row['id']] = $row;
    $path = [];
    $seen = [];
    while ($id !== null && isset($by_id[$id]) && !isset($seen[$id])) {
        $seen[$id] = true;
        array_unshift($path, $by_id[$id]['name']);
        $id = $by_id[$id]['parent_id'];
    }
    return $path;
}

class LruCache
{
    private array $items = [];

    public function __construct(private int $capacity) {}

    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->items)) return null;
        $value = $this->items[$key];
        unset($this->items[$key]);
        $this->items[$key] = $value;
        return $value;
    }

    public function put(string $key, mixed $value): void
    {
        unset($this->items[$key]);
        $this->items[$key] = $value;
        if (count($this->items) > $this->capacity) {
            unset($this->items[array_key_first($this->items)]);
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }
}

function memoize_ttl($fn, $ttl, $clock)
{
    $cache = [];
    return function (...$args) use ($fn, $ttl, $clock, &$cache) {
        $key = serialize($args);
        $now = $clock();
        if (isset($cache[$key]) && $now - $cache[$key]['at'] < $ttl) {
            return $cache[$key]['value'];
        }
        $value = $fn(...$args);
        $cache[$key] = ['value' => $value, 'at' => $now];
        return $value;
    };
}
