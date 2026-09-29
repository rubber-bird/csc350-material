<?php
// ============================================================
//  12 - Validation, passwords and tokens
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 12)
//
//  Everything a user types is untrusted. A registration form, a login
//  form, a comment box: the server checks every field, keeps passwords
//  in a form that cannot be read back, and proves that a request came
//  from its own page. This module is the server side of the term
//  project's registration and login.
//
//  Functions you will need (click for the manual):
//    filter_var()          https://www.php.net/filter_var
//    trim()                https://www.php.net/trim
//    mb_strlen()           https://www.php.net/mb_strlen
//    preg_match()          https://www.php.net/preg_match
//    password_hash()       https://www.php.net/password_hash
//    password_verify()     https://www.php.net/password_verify
//    random_bytes()        https://www.php.net/random_bytes
//    bin2hex()             https://www.php.net/bin2hex
//    hash_equals()         https://www.php.net/hash_equals
//    ctype_digit()         https://www.php.net/ctype_digit
//    explode()             https://www.php.net/explode
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * is_valid_email($value)
 *
 * True for a well-formed email address. Use filter_var with
 * FILTER_VALIDATE_EMAIL; do not write your own regex.
 *
 * Examples:
 *   is_valid_email("ada@example.org")   ->  true
 *   is_valid_email("ada@")              ->  false
 *   is_valid_email("")                  ->  false
 *   is_valid_email(" ada@example.org")  ->  false   (no trimming here)
 */
function is_valid_email($value)
{
    // your code here
}

/**
 * clean($value)
 *
 * What every text field goes through first: make sure it is a string,
 * trim whitespace, and collapse runs of internal whitespace to one
 * space. Null becomes "".
 *
 * Examples:
 *   clean("  Ada   Lovelace \n")  ->  "Ada Lovelace"
 *   clean(null)                   ->  ""
 *   clean(42)                     ->  "42"
 */
function clean($value)
{
    // your code here
}

/**
 * require_fields($data, $fields)
 *
 * Which of the required fields are missing or blank (after trimming)?
 *
 * Input:   $data   - an associative array, like $_POST
 *          $fields - a list of field names
 * Output:  a list of the missing field names, in the order given
 *
 * Examples:
 *   require_fields(['name' => 'Ada', 'email' => ''], ['name', 'email', 'age'])  ->  ['email', 'age']
 *   require_fields(['name' => '  '], ['name'])                                   ->  ['name']
 *   require_fields(['name' => 'Ada'], ['name'])                                  ->  []
 */
function require_fields($data, $fields)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * password_strength_errors($password)
 *
 * The list of rules a password breaks, as messages, in this order:
 *   "at least 8 characters"
 *   "a lower-case letter"
 *   "an upper-case letter"
 *   "a digit"
 * An empty list means the password is fine.
 *
 * Examples:
 *   password_strength_errors("Passw0rd")  ->  []
 *   password_strength_errors("short")     ->  ["at least 8 characters", "an upper-case letter", "a digit"]
 *   password_strength_errors("")          ->  all four
 */
function password_strength_errors($password)
{
    // your code here
}

/**
 * validate_registration($post)
 *
 * Check a registration form. Return ['errors' => [...], 'values' => [...]].
 * values holds the cleaned name and email (never the password), so the
 * form can be re-shown filled in. errors maps a field to ONE message:
 *   name      required; 2 to 50 characters after clean()
 *             -> "Name is required" / "Name must be 2 to 50 characters"
 *   email     required; valid after clean()
 *             -> "Email is required" / "Email is not valid"
 *   password  required; no strength errors
 *             -> "Password is required" / "Password needs " + the
 *                strength errors joined with ", "
 *   password_confirm   must equal password
 *             -> "Passwords do not match"   (only checked when password is fine)
 *   age       optional; if given, digits only and 13 to 120
 *             -> "Age must be a number between 13 and 120"
 * Missing keys count as empty. Fields without errors do not appear.
 *
 * Examples:
 *   validate_registration(['name' => ' Ada ', 'email' => 'ada@example.org', 'password' => 'Passw0rd', 'password_confirm' => 'Passw0rd'])
 *     ->  ['errors' => [], 'values' => ['name' => 'Ada', 'email' => 'ada@example.org']]
 *   validate_registration([])['errors']
 *     ->  ['name' => 'Name is required', 'email' => 'Email is required', 'password' => 'Password is required']
 *   validate_registration([... 'password' => 'short' ...])['errors']['password']
 *     ->  "Password needs at least 8 characters, an upper-case letter, a digit"
 */
function validate_registration($post)
{
    // your code here
}

/**
 * hash_password($password) and check_password($password, $hash)
 *
 * Store a password so it cannot be read back, and check a login
 * attempt against what was stored. Use password_hash with
 * PASSWORD_DEFAULT and password_verify. Never compare hashes with ==.
 *
 * Examples:
 *   $h = hash_password("Passw0rd");
 *   $h !== "Passw0rd"                        ->  true
 *   check_password("Passw0rd", $h)           ->  true
 *   check_password("passw0rd", $h)           ->  false
 *   hash_password("x") !== hash_password("x") ->  true   (a salt is built in)
 */
function hash_password($password)
{
    // your code here
}

function check_password($password, $hash)
{
    // your code here
}

// ---------------------------------------------- HARD --------

/**
 * make_token($bytes = 32)
 *
 * A random token as lower-case hex, for password resets and CSRF.
 * $bytes random bytes give 2 * $bytes hex characters. It must come
 * from random_bytes(), not rand() or uniqid().
 *
 * Examples:
 *   strlen(make_token())          ->  64
 *   strlen(make_token(8))         ->  16
 *   make_token() !== make_token() ->  true
 */
function make_token($bytes = 32)
{
    // your code here
}

/**
 * csrf_token(&$session) and csrf_check(&$session, $submitted)
 *
 * Cross-site request forgery protection. csrf_token() returns the
 * token stored in $session['csrf'], creating one with make_token() the
 * first time; the same token is returned on later calls in the same
 * session. csrf_check() is true only when the session has a token and
 * the submitted value equals it, compared with hash_equals(). A missing
 * or empty submission is false, and never throws.
 *
 * Examples:
 *   $s = [];
 *   $t = csrf_token($s);           strlen($t)  ->  64
 *   csrf_token($s) === $t          ->  true
 *   csrf_check($s, $t)             ->  true
 *   csrf_check($s, 'nope')         ->  false
 *   csrf_check($s, null)           ->  false
 *   $empty = [];  csrf_check($empty, $t)  ->  false
 */
function csrf_token(&$session)
{
    // your code here
}

function csrf_check(&$session, $submitted)
{
    // your code here
}

/**
 * LoginThrottle
 *
 * Slow down password guessing. After 5 failed attempts from the same
 * key (an IP or a username) within a 15-minute window, that key is
 * blocked until 15 minutes have passed since its OLDEST failure in the
 * window. A success clears the key. Time is passed in as a UNIX
 * timestamp so the tests can control it.
 *
 *   record_failure($key, $now)
 *   record_success($key)
 *   is_blocked($key, $now)      -> bool
 *   failures($key, $now)        -> how many failures in the last 15 minutes
 *
 * Examples:
 *   $t = new LoginThrottle();
 *   for 4 failures at $now:  is_blocked('1.2.3.4', $now)  ->  false
 *   5th failure:             is_blocked('1.2.3.4', $now)  ->  true
 *   is_blocked('1.2.3.4', $now + 15 * 60)                  ->  false   (window passed)
 *   another key is never affected
 */
class LoginThrottle
{
    public const MAX_FAILURES = 5;
    public const WINDOW = 15 * 60;

    private array $failures = [];

    public function record_failure(string $key, int $now): void
    {
        // your code here
    }

    public function record_success(string $key): void
    {
        // your code here
    }

    public function failures(string $key, int $now): int
    {
        // your code here
    }

    public function is_blocked(string $key, int $now): bool
    {
        // your code here
    }
}

/**
 * validate($data, $rules)
 *
 * A tiny rule language, like the ones web frameworks have:
 *   'required'   the field must be present and not blank
 *   'email'      must pass is_valid_email
 *   'int'        must be digits only
 *   'min:N'      for int: value >= N; for other strings: length >= N
 *   'max:N'      for int: value <= N; for other strings: length <= N
 *   'in:a,b,c'   must be one of the listed values
 * Rules for a field are joined with "|". A field that is not required
 * and is blank passes all its other rules. Return the first broken
 * rule per field as "<field> failed <rule>", in the order the rules
 * are written. Values are compared after clean().
 *
 * Examples:
 *   validate(['email' => 'ada@example.org', 'age' => '36'],
 *            ['email' => 'required|email', 'age' => 'int|min:13|max:120'])
 *     ->  []
 *   validate(['email' => 'nope', 'age' => '9'], ['email' => 'required|email', 'age' => 'int|min:13'])
 *     ->  ['email' => 'email failed email', 'age' => 'age failed min:13']
 *   validate([], ['name' => 'required|min:2'])   ->  ['name' => 'name failed required']
 *   validate(['plan' => 'gold'], ['plan' => 'in:free,pro'])  ->  ['plan' => 'plan failed in:free,pro']
 *   validate(['nick' => ''], ['nick' => 'min:2'])  ->  []      (optional and blank)
 */
function validate($data, $rules)
{
    // your code here
}
