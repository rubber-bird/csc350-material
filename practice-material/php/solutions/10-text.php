<?php
// 10 - Text and parsing: reference solution

function slugify($title)
{
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

function truncate($text, $max)
{
    if (mb_strlen($text) <= $max) return $text;
    return mb_substr($text, 0, $max - 1) . '…';
}

function format_bytes($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    $size = $bytes;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    return $i === 0 ? "$bytes B" : number_format($size, 1) . ' ' . $units[$i];
}

function parse_csv_line($line)
{
    $fields = [];
    $current = '';
    $in_quotes = false;
    $len = strlen($line);
    for ($i = 0; $i < $len; $i++) {
        $ch = $line[$i];
        if ($in_quotes) {
            if ($ch === '"') {
                if ($i + 1 < $len && $line[$i + 1] === '"') { $current .= '"'; $i++; }
                else $in_quotes = false;
            } else {
                $current .= $ch;
            }
        } elseif ($ch === '"') {
            $in_quotes = true;
        } elseif ($ch === ',') {
            $fields[] = $current;
            $current = '';
        } else {
            $current .= $ch;
        }
    }
    $fields[] = $current;
    return $fields;
}

function render_template($template, $vars)
{
    return preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function ($m) use ($vars) {
        return htmlspecialchars((string) ($vars[$m[1]] ?? ''));
    }, $template);
}

function extract_emails($text)
{
    preg_match_all('/[A-Za-z0-9._+\-]+@[A-Za-z0-9\-]+(?:\.[A-Za-z0-9\-]+)+/', $text, $m);
    return array_values(array_unique($m[0]));
}

function wrap_text($text, $width)
{
    $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
    $lines = [];
    $line = '';
    foreach ($words as $word) {
        if ($line === '') {
            $line = $word;
        } elseif (strlen($line) + 1 + strlen($word) <= $width) {
            $line .= ' ' . $word;
        } else {
            $lines[] = $line;
            $line = $word;
        }
    }
    if ($line !== '') $lines[] = $line;
    return implode("\n", $lines);
}

function parse_duration($text)
{
    $text = trim($text);
    if ($text === '' || !preg_match('/^(\s*\d+\s*[hms])+$/', $text)) return null;
    preg_match_all('/(\d+)\s*([hms])/', $text, $m, PREG_SET_ORDER);
    $factor = ['h' => 3600, 'm' => 60, 's' => 1];
    $seconds = 0;
    foreach ($m as [, $n, $unit]) $seconds += (int) $n * $factor[$unit];
    return $seconds;
}

function parse_config($text)
{
    $out = [];
    $section = null;
    foreach (preg_split('/\r?\n/', $text) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || $line[0] === ';') continue;
        if (preg_match('/^\[(.+)\]$/', $line, $m)) {
            $section = trim($m[1]);
            $out[$section] ??= [];
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($value === 'true') $value = true;
        elseif ($value === 'false') $value = false;
        elseif (ctype_digit($value)) $value = (int) $value;
        if ($section === null) $out[$key] = $value;
        else $out[$section][$key] = $value;
    }
    return $out;
}

function highlight($text, $term)
{
    if ($term === '') return htmlspecialchars($text);
    $pattern = '/' . preg_quote($term, '/') . '/i';
    $parts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    // Simpler: rebuild by walking matches.
    $out = '';
    $offset = 0;
    preg_match_all($pattern, $text, $m, PREG_OFFSET_CAPTURE);
    foreach ($m[0] as [$match, $pos]) {
        $out .= htmlspecialchars(substr($text, $offset, $pos - $offset));
        $out .= '<mark>' . htmlspecialchars($match) . '</mark>';
        $offset = $pos + strlen($match);
    }
    return $out . htmlspecialchars(substr($text, $offset));
}
