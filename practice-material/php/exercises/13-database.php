<?php
// ============================================================
//  13 - Talking to a database with PDO
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 13)
//
//  PDO is PHP's database layer. The same code talks to MySQL in XAMPP
//  or to SQLite; the tests hand every function a PDO connected to an
//  SQLite database in memory, so nothing needs to be running.
//
//  The one rule: never put a value into SQL by string concatenation.
//  Use a prepared statement with ? placeholders and pass the values to
//  execute(). The tests include names with quotes and search terms
//  that would break (or hijack) a concatenated query.
//
//    $st = $pdo->prepare('SELECT * FROM users WHERE email = ?');
//    $st->execute([$email]);
//    $row = $st->fetch();            // one row as an array, or false
//    $rows = $st->fetchAll();        // every row
//    $pdo->lastInsertId();           // id of the row an INSERT just made
//    $st->rowCount();                // rows changed by UPDATE / DELETE
//
//  The users table (create_schema builds it):
//    id             INTEGER PRIMARY KEY AUTOINCREMENT
//    name           TEXT NOT NULL
//    email          TEXT NOT NULL UNIQUE
//    password_hash  TEXT NOT NULL
//    points         INTEGER NOT NULL DEFAULT 0
//    created_at     TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
//
//  Docs:
//    PDO                 https://www.php.net/manual/en/book.pdo.php
//    prepare / execute   https://www.php.net/manual/en/pdo.prepare.php
//    fetch / fetchAll    https://www.php.net/manual/en/pdostatement.fetch.php
//    transactions        https://www.php.net/manual/en/pdo.transactions.php
//    SQLite CREATE TABLE https://www.sqlite.org/lang_createtable.html
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * create_schema($pdo)
 *
 * Create the users table described above. Running it twice must not
 * fail (CREATE TABLE IF NOT EXISTS).
 *
 * Output:  nothing
 */
function create_schema(PDO $pdo): void
{
    // your code here
}

/**
 * insert_user($pdo, $name, $email, $password_hash)
 *
 * Add a user. Return the new id as an int.
 *
 * Examples:
 *   insert_user($pdo, "Ada", "ada@example.org", "hash")   ->  1
 *   insert_user($pdo, "O'Brien", "ob@example.org", "hash") ->  2   (the quote is fine)
 */
function insert_user(PDO $pdo, string $name, string $email, string $password_hash): int
{
    // your code here
}

/**
 * find_user_by_email($pdo, $email)
 *
 * The user's row as an associative array, or null when there is none.
 *
 * Examples:
 *   find_user_by_email($pdo, "ada@example.org")['name']   ->  "Ada"
 *   find_user_by_email($pdo, "nobody@example.org")        ->  null
 */
function find_user_by_email(PDO $pdo, string $email): ?array
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * count_users($pdo)
 *
 * How many users there are, as an int.
 */
function count_users(PDO $pdo): int
{
    // your code here
}

/**
 * list_users($pdo, $page, $per_page)
 *
 * One page of users ordered by name (then id), as a list of rows with
 * only id, name and email. Pages start at 1.
 *
 * Examples:
 *   list_users($pdo, 1, 2)   ->  [['id' => 1, 'name' => 'Ada', 'email' => ...], ['id' => 3, 'name' => 'Alan', ...]]
 *   list_users($pdo, 9, 2)   ->  []
 */
function list_users(PDO $pdo, int $page, int $per_page): array
{
    // your code here
}

/**
 * rename_user($pdo, $id, $new_name)
 *
 * Change a user's name. True if a row was changed, false if no user
 * has that id.
 */
function rename_user(PDO $pdo, int $id, string $new_name): bool
{
    // your code here
}

/**
 * delete_user($pdo, $id)
 *
 * Remove a user. True if a row was deleted, false otherwise.
 */
function delete_user(PDO $pdo, int $id): bool
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * register($pdo, $name, $email, $password)
 *
 * The registration step of a real site: hash the password with
 * password_hash(), insert the user, and return
 *   ['ok' => true,  'id' => 3]                          on success
 *   ['ok' => false, 'error' => 'Email already registered']
 *                                       when the email exists (any case)
 * Store the email in lower case so "Ada@Example.org" and
 * "ada@example.org" are the same account.
 *
 * Examples:
 *   register($pdo, "Ada", "Ada@Example.org", "Passw0rd")   ->  ['ok' => true, 'id' => 1]
 *   register($pdo, "Ada2", "ada@example.org", "Passw0rd")  ->  ['ok' => false, 'error' => 'Email already registered']
 */
function register(PDO $pdo, string $name, string $email, string $password): array
{
    // your code here
}

/**
 * login($pdo, $email, $password)
 *
 * The login step: find the user by email (case-insensitively), check
 * the password with password_verify(), and return the user's row
 * WITHOUT the password_hash key. Wrong email or wrong password both
 * return null, and must be indistinguishable to the caller.
 *
 * Examples:
 *   login($pdo, "ada@example.org", "Passw0rd")['name']   ->  "Ada"
 *   isset(login($pdo, "ada@example.org", "Passw0rd")['password_hash'])  ->  false
 *   login($pdo, "ada@example.org", "wrong")              ->  null
 *   login($pdo, "nobody@example.org", "Passw0rd")        ->  null
 */
function login(PDO $pdo, string $email, string $password): ?array
{
    // your code here
}

/**
 * transfer_points($pdo, $from_id, $to_id, $points)
 *
 * Move points from one user to another inside a transaction, so the
 * two updates either both happen or neither does. Return true on
 * success. Return false, changing nothing, when the sender does not
 * have enough points, when either user does not exist, or when
 * $points is not positive.
 *
 * Examples:
 *   (Ada has 10 points, Alan has 0)
 *   transfer_points($pdo, $ada, $alan, 4)   ->  true    Ada 6, Alan 4
 *   transfer_points($pdo, $ada, $alan, 50)  ->  false   Ada 6, Alan 4
 *   transfer_points($pdo, $ada, 999, 1)     ->  false   Ada 6
 *
 * Docs: https://www.php.net/manual/en/pdo.begintransaction.php
 */
function transfer_points(PDO $pdo, int $from_id, int $to_id, int $points): bool
{
    // your code here
}

/**
 * search_users($pdo, $term)
 *
 * Users whose name or email contains the term, case-insensitively,
 * ordered by name. Only id, name and email. The term is data: a % or _
 * typed by the user must match those literal characters, not act as
 * wildcards, and a quote must not break the query.
 *
 * Examples:
 *   pluck(search_users($pdo, "ad"), 'name')     ->  ["Ada", ...]
 *   search_users($pdo, "%")                     ->  []      (nobody has a % in their name)
 *   search_users($pdo, "' OR 1=1 --")           ->  []
 *
 * Hint: LIKE ? ESCAPE '\'  and escape %, _ and \ in the term first.
 */
function search_users(PDO $pdo, string $term): array
{
    // your code here
}
