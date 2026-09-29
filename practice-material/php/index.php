<?php
// Browser runner. Put this folder in XAMPP's htdocs, start Apache, and open
//   http://localhost/php-fundamentals/
//
// It shows every module on one page, block by block, in the order the tests
// run. Each module is loaded inside try/catch, so a syntax error in one
// exercise file is reported in its place and the other modules still run.

$testDir = __DIR__ . '/tests';
$files = array_values(array_filter(scandir($testDir), fn($f) => str_ends_with($f, '.test.php')));
sort($files);

require __DIR__ . '/spec.php';

// A fatal error that try/catch cannot see (for example a function declared
// twice) would otherwise leave a blank page. Print what ran so far and name
// the file that broke.
$currentFile = null;
$modules = [];
register_shutdown_function(function () use (&$currentFile, &$modules, $files) {
    $err = error_get_last();
    if ($err === null || !in_array($err['type'], [E_ERROR, E_COMPILE_ERROR, E_CORE_ERROR], true)) return;
    $partial = ob_get_clean();
    $modules[] = [
        'file' => $currentFile,
        'html' => $partial . '<div class="broken"><strong>PHP could not run this file.</strong><pre>'
            . htmlspecialchars($err['message'] . "\n" . $err['file'] . ' line ' . $err['line']) . '</pre></div>',
        'passed' => 0, 'failed' => 0,
    ];
    foreach ($files as $f) {
        if (array_search($f, array_column($modules, 'file'), true) === false) {
            $modules[] = ['file' => $f, 'html' => '<div class="broken">Not run: fix the error above first.</div>', 'passed' => 0, 'failed' => 0];
        }
    }
    render_page($modules);
});

foreach ($files as $f) {
    $currentFile = $f;
    $before = $GLOBALS['spec'];
    ob_start();
    try {
        require "$testDir/$f";
    } catch (Throwable $e) {
        echo '<div class="broken"><strong>PHP could not run this file.</strong><pre>'
            . htmlspecialchars(get_class($e) . ': ' . $e->getMessage() . "\n" . $e->getFile() . ' line ' . $e->getLine())
            . '</pre></div>';
    }
    $modules[] = [
        'file' => $f,
        'html' => ob_get_clean(),
        'passed' => $GLOBALS['spec']['passed'] - $before['passed'],
        'failed' => $GLOBALS['spec']['failed'] - $before['failed'],
    ];
}
$currentFile = null;
render_page($modules);

function render_page(array $modules): void
{
    $passed = array_sum(array_column($modules, 'passed'));
    $failed = array_sum(array_column($modules, 'failed'));
    $total = $passed + $failed;
    header('Content-Type: text/html; charset=utf-8');
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP Fundamentals</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>PHP Fundamentals</h1>
<p class="lead">Every test, block by block, in the order it runs. Edit a file in <code>exercises/</code>, save, and refresh this page.</p>
<p class="summary"><?= $total ?> tests: <span class="pass"><?= $passed ?> passed</span>, <span class="fail"><?= $failed ?> failed</span></p>
<?php foreach ($modules as $m): ?>
<section class="module">
<?= $m['html'] ?>
<p class="module-summary"><?= htmlspecialchars($m['file']) ?>: <?= $m['passed'] ?> passed, <?= $m['failed'] ?> failed</p>
</section>
<?php endforeach; ?>
</body>
</html>
<?php
}
