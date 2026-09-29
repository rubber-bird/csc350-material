<?php
// 09 - Working with records: reference solution

function index_by($records, $key)
{
    $out = [];
    foreach ($records as $r) $out[$r[$key]] = $r;
    return $out;
}

function pluck($records, $key)
{
    return array_values(array_column($records, $key));
}

function where($records, $key, $value)
{
    return array_values(array_filter($records, fn($r) => $r[$key] === $value));
}

function sort_by($records, ...$keys)
{
    $records = array_values($records);
    usort($records, function ($a, $b) use ($keys) {
        foreach ($keys as $key) {
            $desc = str_starts_with($key, '-');
            $field = $desc ? substr($key, 1) : $key;
            $cmp = $a[$field] <=> $b[$field];
            if ($cmp !== 0) return $desc ? -$cmp : $cmp;
        }
        return 0;
    });
    return $records;
}

function sum_by($records, $group_key, $value_key)
{
    $out = [];
    foreach ($records as $r) {
        $out[$r[$group_key]] = ($out[$r[$group_key]] ?? 0) + $r[$value_key];
    }
    return $out;
}

function top_n($records, $key, $n)
{
    return array_slice(sort_by($records, '-' . $key), 0, $n);
}

function pivot($records, $row_key, $col_key, $value_key)
{
    $rows = [];
    $cols = [];
    foreach ($records as $r) {
        $rows[$r[$row_key]] = true;
        $cols[$r[$col_key]] = true;
    }
    $out = [];
    foreach (array_keys($rows) as $row) {
        $out[$row] = array_fill_keys(array_keys($cols), 0);
    }
    foreach ($records as $r) {
        $out[$r[$row_key]][$r[$col_key]] += $r[$value_key];
    }
    return $out;
}

function paginate($records, $page, $per_page)
{
    $total = count($records);
    return [
        'items' => array_slice(array_values($records), ($page - 1) * $per_page, $per_page),
        'page'  => $page,
        'pages' => (int) ceil($total / $per_page),
        'total' => $total,
    ];
}

function deep_merge($base, $override)
{
    foreach ($override as $key => $value) {
        $both_assoc = is_array($value) && !array_is_list($value)
            && isset($base[$key]) && is_array($base[$key]) && !array_is_list($base[$key]);
        $base[$key] = $both_assoc ? deep_merge($base[$key], $value) : $value;
    }
    return $base;
}

function flatten_keys($array, $prefix = '')
{
    $out = [];
    foreach ($array as $key => $value) {
        $path = $prefix === '' ? (string) $key : "$prefix.$key";
        if (is_array($value) && count($value) > 0) {
            $out += flatten_keys($value, $path);
        } else {
            $out[$path] = $value;
        }
    }
    return $out;
}
