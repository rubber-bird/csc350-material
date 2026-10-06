<?php
// A tiny test runner with no dependencies. It gives the test files the
// describe / it / expect names that Mocha and Chai use in js-fundamentals,
// so the two projects read the same way.
//
// It prints HTML when a web server (XAMPP) runs it, and plain text when
// the terminal runs it:   php run.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$GLOBALS['spec'] = ['passed' => 0, 'failed' => 0, 'depth' => 0];

define('SPEC_IN_BROWSER', PHP_SAPI !== 'cli');

// Turn warnings and notices into exceptions, so a test that triggers
// "Undefined variable" fails instead of quietly passing.
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

function describe(string $name, callable $body): void
{
    $s = &$GLOBALS['spec'];
    if (SPEC_IN_BROWSER) {
        $level = min(4, $s['depth'] + 2);
        echo "<h{$level} class=\"group\">" . htmlspecialchars($name) . "</h{$level}>\n";
    } else {
        echo str_repeat('  ', $s['depth']) . $name . "\n";
    }
    $s['depth']++;
    try {
        $body();
    } finally {
        $s['depth']--;
    }
}

// $hints is optional: a list of strings, from a gentle nudge to the
// full answer. They only show under a failing test, one fold at a time.
function it(string $name, callable $body, array $hints = []): void
{
    $s = &$GLOBALS['spec'];
    try {
        $body();
        $s['passed']++;
        spec_print_result(true, $name, '');
    } catch (Throwable $err) {
        $s['failed']++;
        spec_print_result(false, $name, $err->getMessage(), $hints);
    }
}

function spec_print_result(bool $ok, string $name, string $message, array $hints = []): void
{
    $indent = str_repeat('  ', $GLOBALS['spec']['depth']);
    if (SPEC_IN_BROWSER) {
        $class = $ok ? 'pass' : 'fail';
        $mark = $ok ? '&#10003;' : '&#10007;';
        echo "<div class=\"test {$class}\"><span class=\"mark\">{$mark}</span> " . htmlspecialchars($name);
        if (!$ok) {
            echo "<pre>" . htmlspecialchars($message) . "</pre>";
            echo spec_hints_html($hints);
        }
        echo "</div>\n";
    } else {
        if ($ok) {
            echo "{$indent}\033[32m✓\033[0m {$name}\n";
        } else {
            echo "{$indent}\033[31m✗ {$name}\033[0m\n";
            foreach (explode("\n", $message) as $line) {
                echo "\033[31m{$indent}    {$line}\033[0m\n";
            }
            if (count($hints) > 0) {
                if (getenv('SPEC_HINTS')) {
                    foreach ($hints as $i => $hint) {
                        echo "\033[33m{$indent}    hint " . ($i + 1) . ": {$hint}\033[0m\n";
                    }
                } else {
                    echo "\033[2m{$indent}    (" . count($hints) . " hints available: add --hints, or open index.php)\033[0m\n";
                }
            }
            echo "\n";
        }
    }
}

// Supports:   expect($x)->toEqual($y)      expect($x)->notToEqual($y)
// Comparison is strict (===), except that two floats count as equal when
// they differ by less than 0.000000001, so 0.1 + 0.2 equals 0.3.
class Expectation
{
    public function __construct(private mixed $received) {}

    public function toEqual(mixed $expected): void
    {
        if (!spec_same($this->received, $expected)) {
            throw new Exception(
                "Expected: " . spec_show($expected) . "\n" .
                "Received: " . spec_show($this->received)
            );
        }
    }

    public function notToEqual(mixed $expected): void
    {
        if (spec_same($this->received, $expected)) {
            throw new Exception(
                "Expected anything but: " . spec_show($expected) . "\n" .
                "Received: " . spec_show($this->received)
            );
        }
    }
}

function expect(mixed $received): Expectation
{
    return new Expectation($received);
}

function spec_same(mixed $a, mixed $b): bool
{
    if (is_float($a) || is_float($b)) {
        if (!is_int($a) && !is_float($a)) return false;
        if (!is_int($b) && !is_float($b)) return false;
        return abs($a - $b) < 1e-9;
    }
    if (is_array($a) && is_array($b)) {
        if (array_keys($a) !== array_keys($b)) return false;
        foreach ($a as $key => $value) {
            if (!spec_same($value, $b[$key])) return false;
        }
        return true;
    }
    return $a === $b;
}

function spec_show(mixed $value): string
{
    if ($value === null) return 'null';
    if (is_bool($value)) return $value ? 'true' : 'false';
    if (is_string($value)) return '"' . $value . '"';
    if (is_array($value)) {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }
    if (is_object($value) && $value instanceof Closure) return 'a function';
    return var_export($value, true);
}

// Hint 1 contains Hint 2 contains Hint 3, so each click reveals one more step.
function spec_hints_html(array $hints): string
{
    if (count($hints) === 0) return '';
    $html = '';
    foreach (array_reverse($hints, true) as $i => $hint) {
        $html = '<details class="hint"><summary>Hint ' . ($i + 1) . '</summary>'
            . '<p>' . htmlspecialchars($hint) . '</p>' . $html . '</details>';
    }
    return $html;
}

function spec_summary(): void
{
    $s = $GLOBALS['spec'];
    $total = $s['passed'] + $s['failed'];
    if (SPEC_IN_BROWSER) {
        $class = $s['failed'] > 0 ? 'fail' : 'pass';
        echo "<p class=\"summary {$class}\">Tests: {$s['failed']} failed, {$s['passed']} passed, {$total} total</p>\n";
    } else {
        echo "\nTests: {$s['failed']} failed, {$s['passed']} passed, {$total} total\n";
    }
}

// ---- HTML helpers, used by module 08 ----
// Whitespace between tags and runs of spaces do not matter, so students
// can indent their HTML however they like.
function normalize_html(string $html): string
{
    $html = preg_replace('/>\s+</', '><', trim($html));
    return preg_replace('/\s+/', ' ', $html);
}

class HtmlExpectation
{
    public function __construct(private mixed $received) {}

    public function toMatchHtml(string $expected): void
    {
        if (!is_string($this->received)) {
            throw new Exception(
                "Expected: " . spec_show($expected) . "\n" .
                "Received: " . spec_show($this->received)
            );
        }
        $got = normalize_html($this->received);
        $want = normalize_html($expected);
        if ($got !== $want) {
            throw new Exception("Expected: \"{$want}\"\nReceived: \"{$got}\"");
        }
    }
}

function expect_html(mixed $received): HtmlExpectation
{
    return new HtmlExpectation($received);
}
