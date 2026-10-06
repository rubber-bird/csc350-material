<?php
// Database helpers for the tests. They connect to MariaDB (XAMPP) or MySQL,
// run a student's .sql file against a fresh database, and then look at what
// was created through information_schema.

require_once __DIR__ . '/spec.php';

function db_config(): array
{
    static $config = null;
    return $config ??= require __DIR__ . '/config.php';
}

// One shared connection. Throws with a friendly message when the server is
// not running.
function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $c = db_config();
    try {
        $pdo = new PDO(
            "mysql:host={$c['host']};port={$c['port']};charset=utf8mb4",
            $c['user'],
            $c['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    } catch (PDOException $e) {
        throw new RuntimeException(
            "Could not connect to the database server at {$c['host']}:{$c['port']} as {$c['user']}.\n" .
            "Start MySQL in the XAMPP control panel, or fix config.php.\n" .
            "The server said: " . $e->getMessage()
        );
    }
    // Strict mode: bad values are errors, not silent conversions. This is
    // what a well-configured production server does, and it makes the
    // "this insert must be rejected" tests meaningful.
    $pdo->exec("SET SESSION sql_mode = 'STRICT_ALL_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    $pdo->exec("SET SESSION foreign_key_checks = 1");
    return $pdo;
}

function db_name(): string
{
    return db_config()['database'];
}

// Drop and re-create the practice database, so every run starts empty.
function db_reset(): void
{
    $name = db_name();
    db()->exec("DROP DATABASE IF EXISTS `{$name}`");
    db()->exec("CREATE DATABASE `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    db()->exec("USE `{$name}`");
}

// Split a .sql file into statements. Understands quotes, backticks and the
// three comment styles, so a ";" inside a string does not split.
function db_split_sql(string $sql): array
{
    $statements = [];
    $current = '';
    $len = strlen($sql);
    $i = 0;
    while ($i < $len) {
        $ch = $sql[$i];
        $two = substr($sql, $i, 2);
        if ($two === '--' || $ch === '#') {                       // line comment
            while ($i < $len && $sql[$i] !== "\n") $i++;
            continue;
        }
        if ($two === '/*') {                                       // block comment
            $end = strpos($sql, '*/', $i + 2);
            $i = $end === false ? $len : $end + 2;
            continue;
        }
        if ($ch === "'" || $ch === '"' || $ch === '`') {           // quoted
            $quote = $ch;
            $current .= $ch;
            $i++;
            while ($i < $len) {
                $c = $sql[$i];
                $current .= $c;
                $i++;
                if ($c === '\\' && $i < $len) { $current .= $sql[$i]; $i++; continue; }
                if ($c === $quote) {
                    if ($i < $len && $sql[$i] === $quote) { $current .= $quote; $i++; continue; }
                    break;
                }
            }
            continue;
        }
        if ($ch === ';') {
            if (trim($current) !== '') $statements[] = trim($current);
            $current = '';
            $i++;
            continue;
        }
        $current .= $ch;
        $i++;
    }
    if (trim($current) !== '') $statements[] = trim($current);
    return $statements;
}

// Reset the database and run one exercise file from top to bottom.
// Returns a list of error messages, one per failed statement (empty when
// everything ran). Statements after a failed one still run.
function db_run_exercise(string $file): array
{
    db_reset();
    $path = __DIR__ . '/exercises/' . $file;
    if (!is_file($path)) return ["Missing file exercises/{$file}"];
    $errors = [];
    foreach (db_split_sql(file_get_contents($path)) as $n => $statement) {
        try {
            db()->exec($statement);
        } catch (PDOException $e) {
            $preview = preg_replace('/\s+/', ' ', $statement);
            if (strlen($preview) > 90) $preview = substr($preview, 0, 87) . '...';
            $errors[] = "Statement " . ($n + 1) . " failed: " . db_error_text($e) . "\n    " . $preview;
        }
    }
    return $errors;
}

function db_error_text(PDOException $e): string
{
    // "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'x' doesn't exist" -> the useful part
    $msg = $e->getMessage();
    if (preg_match('/^SQLSTATE\[\w+\]: [^:]+: \d+ (.*)$/s', $msg, $m)) return $m[1];
    return $msg;
}

// ---- Reading the schema ----

function db_tables(): array
{
    $st = db()->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME");
    $st->execute([db_name()]);
    return array_map(fn($r) => $r['TABLE_NAME'], $st->fetchAll());
}

function db_table_exists(string $table): bool
{
    return in_array($table, db_tables(), true);
}

function db_require_table(string $table): void
{
    if (!db_table_exists($table)) {
        $have = db_tables();
        throw new Exception("There is no table called `{$table}`.\n" .
            (count($have) ? "Tables that exist: " . implode(', ', $have) : "The database has no tables at all."));
    }
}

function db_engine(string $table): ?string
{
    db_require_table($table);
    $st = db()->prepare("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?");
    $st->execute([db_name(), $table]);
    return $st->fetchColumn() ?: null;
}

// All columns of a table, in order. Each is an array with the raw
// information_schema fields we use.
function db_columns(string $table): array
{
    db_require_table($table);
    $st = db()->prepare("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA,
                                CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE
                         FROM information_schema.COLUMNS
                         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION");
    $st->execute([db_name(), $table]);
    $out = [];
    foreach ($st->fetchAll() as $r) $out[$r['COLUMN_NAME']] = $r;
    return $out;
}

function db_column_names(string $table): array
{
    return array_keys(db_columns($table));
}

function db_column(string $table, string $column): array
{
    $cols = db_columns($table);
    if (!isset($cols[$column])) {
        throw new Exception("Table `{$table}` has no column called `{$column}`.\n" .
            "Its columns are: " . implode(', ', array_keys($cols)));
    }
    return $cols[$column];
}

// A short, normalised description of a column's type, the same on MariaDB
// and MySQL:  "int", "int unsigned", "bigint", "tinyint(1)", "varchar(100)",
// "char(2)", "decimal(10,2)", "text", "date", "datetime", "timestamp",
// "enum('s','m','l')".
function db_type(string $table, string $column): string
{
    $c = db_column($table, $column);
    $data = strtolower($c['DATA_TYPE']);
    $full = strtolower($c['COLUMN_TYPE']);
    $unsigned = str_contains($full, 'unsigned') ? ' unsigned' : '';
    switch ($data) {
        case 'varchar': case 'char': case 'binary': case 'varbinary':
            return $data . '(' . $c['CHARACTER_MAXIMUM_LENGTH'] . ')';
        case 'decimal': case 'numeric':
            return 'decimal(' . $c['NUMERIC_PRECISION'] . ',' . $c['NUMERIC_SCALE'] . ')';
        case 'tinyint':
            if (str_starts_with($full, 'tinyint(1)')) return 'tinyint(1)';
            return 'tinyint' . $unsigned;
        case 'smallint': case 'mediumint': case 'int': case 'bigint':
            return $data . $unsigned;
        case 'enum': case 'set':
            return $c['COLUMN_TYPE'];   // keeps the case of the values: enum('S','M','L')
        default:
            return $data;
    }
}

function db_nullable(string $table, string $column): bool
{
    return db_column($table, $column)['IS_NULLABLE'] === 'YES';
}

// The default value as a plain string, or null when there is none.
// "CURRENT_TIMESTAMP" for the timestamp function, whatever the server calls it.
// Numbers come back the way the server stores them: DEFAULT 0 on a
// DECIMAL(10,2) column is "0.00".
function db_default(string $table, string $column): ?string
{
    $d = db_column($table, $column)['COLUMN_DEFAULT'];
    if ($d === null || strtoupper($d) === 'NULL') return null;
    if (preg_match('/^current_timestamp(\(\))?$/i', $d)) return 'CURRENT_TIMESTAMP';
    if (strlen($d) >= 2 && $d[0] === "'" && str_ends_with($d, "'")) $d = str_replace("''", "'", substr($d, 1, -1));
    return $d;
}

function db_auto_increment(string $table, string $column): bool
{
    return str_contains(strtolower(db_column($table, $column)['EXTRA']), 'auto_increment');
}

// Primary key columns in order, or [] when the table has none.
function db_primary_key(string $table): array
{
    db_require_table($table);
    $st = db()->prepare("SELECT COLUMN_NAME FROM information_schema.STATISTICS
                         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = 'PRIMARY' ORDER BY SEQ_IN_INDEX");
    $st->execute([db_name(), $table]);
    return array_map(fn($r) => $r['COLUMN_NAME'], $st->fetchAll());
}

// Every index except the primary key:  name => [unique, columns, type, prefix lengths]
function db_indexes(string $table): array
{
    db_require_table($table);
    $st = db()->prepare("SELECT INDEX_NAME, NON_UNIQUE, COLUMN_NAME, INDEX_TYPE, SUB_PART FROM information_schema.STATISTICS
                         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME <> 'PRIMARY' ORDER BY INDEX_NAME, SEQ_IN_INDEX");
    $st->execute([db_name(), $table]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $name = $r['INDEX_NAME'];
        $out[$name] ??= ['unique' => false, 'columns' => [], 'type' => $r['INDEX_TYPE'], 'prefix' => []];
        $out[$name]['unique'] = ((int) $r['NON_UNIQUE']) === 0;
        $out[$name]['columns'][] = $r['COLUMN_NAME'];
        $out[$name]['prefix'][] = $r['SUB_PART'] === null ? null : (int) $r['SUB_PART'];
    }
    return $out;
}

function db_index(string $table, string $name): array
{
    $all = db_indexes($table);
    if (!isset($all[$name])) {
        throw new Exception("Table `{$table}` has no index called `{$name}`.\n" .
            (count($all) ? "Its indexes are: " . implode(', ', array_keys($all)) : "It has no indexes besides the primary key."));
    }
    return $all[$name];
}

// The columns of the index with this name, in order.
function db_index_columns(string $table, string $name): array
{
    return db_index($table, $name)['columns'];
}

function db_index_is_unique(string $table, string $name): bool
{
    return db_index($table, $name)['unique'];
}

// Is there any index (whatever its name) on exactly these columns, in this order?
function db_has_index_on(string $table, array $columns, ?bool $unique = null): bool
{
    foreach (db_indexes($table) as $idx) {
        if ($idx['columns'] === $columns && ($unique === null || $idx['unique'] === $unique)) return true;
    }
    return false;
}

// Foreign keys of a table:  name => [columns, ref_table, ref_columns, on_delete, on_update]
function db_foreign_keys(string $table): array
{
    db_require_table($table);
    $st = db()->prepare("SELECT k.CONSTRAINT_NAME, k.COLUMN_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME,
                                r.DELETE_RULE, r.UPDATE_RULE
                         FROM information_schema.KEY_COLUMN_USAGE k
                         JOIN information_schema.REFERENTIAL_CONSTRAINTS r
                           ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                              AND r.TABLE_NAME = k.TABLE_NAME
                         WHERE k.TABLE_SCHEMA = ? AND k.TABLE_NAME = ? AND k.REFERENCED_TABLE_NAME IS NOT NULL
                         ORDER BY k.CONSTRAINT_NAME, k.ORDINAL_POSITION");
    $st->execute([db_name(), $table]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $name = $r['CONSTRAINT_NAME'];
        $out[$name] ??= ['columns' => [], 'ref_table' => $r['REFERENCED_TABLE_NAME'], 'ref_columns' => [],
                         'on_delete' => db_rule($r['DELETE_RULE']), 'on_update' => db_rule($r['UPDATE_RULE'])];
        $out[$name]['columns'][] = $r['COLUMN_NAME'];
        $out[$name]['ref_columns'][] = $r['REFERENCED_COLUMN_NAME'];
    }
    return $out;
}

// MariaDB says RESTRICT where MySQL says NO ACTION. They behave the same.
function db_rule(string $rule): string
{
    $rule = strtoupper($rule);
    return $rule === 'NO ACTION' ? 'RESTRICT' : $rule;
}

// The foreign key on exactly these columns (in order), whatever its name.
function db_foreign_key(string $table, array $columns): array
{
    foreach (db_foreign_keys($table) as $name => $fk) {
        if ($fk['columns'] === $columns) return $fk + ['name' => $name];
    }
    $have = array_map(fn($fk) => '(' . implode(', ', $fk['columns']) . ')', db_foreign_keys($table));
    throw new Exception("Table `{$table}` has no foreign key on (" . implode(', ', $columns) . ").\n" .
        (count($have) ? "Its foreign keys are on: " . implode(' ', $have) : "It has no foreign keys at all."));
}

// Is this name a view rather than a table?
function db_is_view(string $name): bool
{
    $st = db()->prepare("SELECT TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?");
    $st->execute([db_name(), $name]);
    return $st->fetchColumn() === 'VIEW';
}

// Fails with a clear message unless a view with this name exists.
function db_require_view(string $name): void
{
    if (!db_table_exists($name)) {
        $have = db_tables();
        throw new Exception("There is no view called `{$name}`.\n" .
            (count($have) ? "Tables and views that exist: " . implode(', ', $have) : "The database has no tables at all."));
    }
    if (!db_is_view($name)) {
        throw new Exception("`{$name}` exists, but it is a table. The task asks for a VIEW (CREATE VIEW {$name} AS SELECT ...).");
    }
}

// "STORED" or "VIRTUAL" for a generated column, null for an ordinary one.
function db_generated(string $table, string $column): ?string
{
    $extra = strtoupper(db_column($table, $column)['EXTRA']);
    if (!str_contains($extra, 'GENERATED')) return null;
    return str_contains($extra, 'STORED') ? 'STORED' : 'VIRTUAL';
}

// Names of the CHECK constraints on a table.
function db_check_constraints(string $table): array
{
    db_require_table($table);
    $st = db()->prepare("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'CHECK' ORDER BY CONSTRAINT_NAME");
    $st->execute([db_name(), $table]);
    return array_map(fn($r) => $r['CONSTRAINT_NAME'], $st->fetchAll());
}

// ---- Reading views (modules 09 to 12 check queries through views) ----

// Column names of a view or table, in order.
function db_view_columns(string $name): array
{
    $st = db()->prepare("SELECT COLUMN_NAME FROM information_schema.COLUMNS
                         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION");
    $st->execute([db_name(), $name]);
    return array_map(fn($r) => $r['COLUMN_NAME'], $st->fetchAll());
}

// Every value as text (NULL stays null), so a test can compare rows without
// caring whether the driver hands back 3 or "3".
function db_text_rows(array $rows): array
{
    return array_map(fn($row) => array_map(fn($v) => $v === null ? null : (string) $v, $row), $rows);
}

// The rows of a view, in the view's own order, as text.
function db_view_rows(string $view): array
{
    db_require_view($view);
    return db_text_rows(db_query("SELECT * FROM `{$view}`"));
}

// A view must exist, have exactly these columns in this order, and return
// exactly these rows in this order.
function expect_view(string $view, array $columns, array $rows): void
{
    db_require_view($view);
    $have = db_view_columns($view);
    if ($have !== $columns) {
        throw new Exception("View `{$view}` has the wrong columns.\n" .
            "Expected: " . implode(', ', $columns) . "\n" .
            "Received: " . implode(', ', $have) . "\n" .
            "Use AS to name each column exactly as the task says.");
    }
    expect(db_view_rows($view))->toEqual($rows);
}

// ---- Running statements ----

function db_query(string $sql, array $params = []): array
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

function db_value(string $sql, array $params = []): mixed
{
    $rows = db_query($sql, $params);
    return count($rows) ? array_values($rows[0])[0] : null;
}

function db_count(string $table): int
{
    db_require_table($table);
    return (int) db_value("SELECT COUNT(*) FROM `{$table}`");
}

// Run some statements (INSERT / UPDATE / DELETE) and then undo them, so a
// test can poke at the schema without leaving anything behind.
// Returns the error message of the first statement that failed, or null.
function db_probe(string|array $sql): ?string
{
    $statements = is_array($sql) ? $sql : [$sql];
    $pdo = db();
    $pdo->beginTransaction();
    try {
        foreach ($statements as $s) $pdo->exec($s);
        return null;
    } catch (PDOException $e) {
        return db_error_text($e);
    } finally {
        if ($pdo->inTransaction()) $pdo->rollBack();
    }
}

// Like db_probe, but keeps the last statement's result rows. The callback
// receives them while the transaction is still open; then everything is
// rolled back.
function db_probe_query(array $setup, string $query, callable $check): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        foreach ($setup as $s) $pdo->exec($s);
        $rows = $pdo->query($query)->fetchAll();
        $check($rows);
    } finally {
        if ($pdo->inTransaction()) $pdo->rollBack();
    }
}

// The database must reject these statements (constraint violation, bad
// value, ...). Fails the test if it accepts them.
function expect_rejected(string|array $sql, string $because): void
{
    $error = db_probe($sql);
    if ($error === null) {
        $shown = is_array($sql) ? end($sql) : $sql;
        throw new Exception("Expected the database to reject:\n    {$shown}\nbecause {$because},\nbut it was accepted.");
    }
}

// The database must accept these statements. Fails the test with the
// database's own error message if it does not.
function expect_accepted(string|array $sql): void
{
    $error = db_probe($sql);
    if ($error !== null) {
        $shown = is_array($sql) ? end($sql) : $sql;
        throw new Exception("Expected the database to accept:\n    {$shown}\nbut it said: {$error}");
    }
}
