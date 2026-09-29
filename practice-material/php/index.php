<?php
// Browser runner. Put this folder in XAMPP's htdocs, start Apache, and open
//   http://localhost/php-fundamentals/
//
// With no ?module= it shows every module, each inside its own frame, so a
// syntax error in one file does not hide the results of the others.
// With ?module=03 it runs just that module's tests and prints the results.

$testDir = __DIR__ . '/tests';
$files = array_values(array_filter(scandir($testDir), fn($f) => str_ends_with($f, '.test.php')));
sort($files);

$module = $_GET['module'] ?? '';

if ($module !== '') {
    // ---- one module: run its tests and print the results ----
    $matches = array_values(array_filter($files, fn($f) => str_starts_with($f, $module)));
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><meta charset=\"utf-8\"><link rel=\"stylesheet\" href=\"style.css\"><body class=\"frame\">\n";
    if (count($matches) === 0) {
        echo "<p class=\"fail\">No test file starts with \"" . htmlspecialchars($module) . "\"</p>";
        exit;
    }
    require __DIR__ . '/spec.php';
    require "$testDir/{$matches[0]}";
    spec_summary();
    exit;
}

// ---- overview: one frame per module ----
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
<p class="hint">Edit a file in <code>exercises/</code>, save, and refresh this page.
Click a module name to open only that module.</p>
<nav>
<?php foreach ($files as $f): $id = substr($f, 0, 2); ?>
  <a href="index.php?module=<?= $id ?>"><?= htmlspecialchars(str_replace('.test.php', '', $f)) ?></a>
<?php endforeach; ?>
</nav>
<?php foreach ($files as $f): $id = substr($f, 0, 2); ?>
<section>
  <iframe src="index.php?module=<?= $id ?>" title="<?= htmlspecialchars($f) ?>"></iframe>
</section>
<?php endforeach; ?>
<script>
// Grow each frame to fit its content.
document.querySelectorAll('iframe').forEach(function (frame) {
  frame.addEventListener('load', function () {
    frame.style.height = frame.contentDocument.documentElement.scrollHeight + 20 + 'px';
  });
});
</script>
</body>
</html>
