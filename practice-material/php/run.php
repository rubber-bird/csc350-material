<?php
// Terminal runner. Needs the php command, which XAMPP ships in its bin folder.
//
// Usage:
//   php run.php          run every test file
//   php run.php 03       run only the test file whose name contains "03"
//   php run.php 03 --hints   also print the hints under failing tests
//
// Each test file runs in its own PHP process, so a syntax error in one
// module does not hide the results of the others.

$args = array_slice($argv, 1);
if (in_array('--hints', $args, true)) {
    putenv('SPEC_HINTS=1');
    $args = array_values(array_diff($args, ['--hints']));
}
$filter = $args[0] ?? '';
$testDir = __DIR__ . '/tests';

$files = array_values(array_filter(scandir($testDir), function ($f) use ($filter) {
    return str_ends_with($f, '.test.php') && str_contains($f, $filter);
}));

if (count($files) === 0) {
    echo "No test files matching \"{$filter}\" in tests/\n";
    exit(1);
}

$failed = 0;
foreach ($files as $file) {
    echo "\n\033[1m{$file}\033[0m\n";
    passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg("$testDir/$file"), $code);
    if ($code !== 0) $failed++;
}

exit($failed > 0 ? 1 : 0);
