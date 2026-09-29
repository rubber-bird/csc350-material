<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/13-database.php';

// A fresh in-memory SQLite database with the schema and, optionally, some users.
function fresh_db(bool $seed = false): PDO
{
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    create_schema($pdo);
    if ($seed) {
        $pdo->exec("INSERT INTO users (name, email, password_hash, points) VALUES
            ('Ada', 'ada@example.org', 'h1', 10),
            ('Grace', 'grace@example.org', 'h2', 0),
            ('Alan', 'alan@example.org', 'h3', 0),
            ('O''Brien', 'ob@example.org', 'h4', 5)");
    }
    return $pdo;
}
function names(array $rows): array { return array_column($rows, 'name'); }

describe('13 - Talking to a database with PDO', function () {
    describe('easy', function () {
        it('create_schema builds the users table and can run twice', function () {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            create_schema($pdo);
            create_schema($pdo);
            $cols = array_column($pdo->query('PRAGMA table_info(users)')->fetchAll(PDO::FETCH_ASSOC), 'name');
            expect($cols)->toEqual(['id', 'name', 'email', 'password_hash', 'points', 'created_at']);
            $pdo->exec("INSERT INTO users (name, email, password_hash) VALUES ('a', 'a@x.org', 'h')");
            $row = $pdo->query('SELECT * FROM users')->fetch(PDO::FETCH_ASSOC);
            expect($row['id'])->toEqual(1);
            expect($row['points'])->toEqual(0);
            expect(strlen((string) $row['created_at']) >= 19)->toEqual(true);
            $failed = false;
            try { $pdo->exec("INSERT INTO users (name, email, password_hash) VALUES ('b', 'a@x.org', 'h')"); } catch (PDOException $e) { $failed = true; }
            expect($failed)->toEqual(true);
        });
        it('insert_user returns the new id and copes with quotes', function () {
            $pdo = fresh_db();
            expect(insert_user($pdo, 'Ada', 'ada@example.org', 'hash'))->toEqual(1);
            expect(insert_user($pdo, "O'Brien", 'ob@example.org', 'hash'))->toEqual(2);
            expect(insert_user($pdo, 'Robert"); DROP TABLE users; --', 'bobby@example.org', 'hash'))->toEqual(3);
            expect($pdo->query('SELECT COUNT(*) FROM users')->fetchColumn())->toEqual(3);
            expect($pdo->query("SELECT name FROM users WHERE id = 2")->fetchColumn())->toEqual("O'Brien");
        });
        it('find_user_by_email returns the row or null', function () {
            $pdo = fresh_db(true);
            $u = find_user_by_email($pdo, 'ada@example.org');
            expect($u['name'])->toEqual('Ada');
            expect($u['points'])->toEqual(10);
            expect(find_user_by_email($pdo, 'nobody@example.org'))->toEqual(null);
            expect(find_user_by_email($pdo, "' OR 1=1 --"))->toEqual(null);
        });
    });

    describe('medium', function () {
        it('count_users', function () {
            expect(count_users(fresh_db()))->toEqual(0);
            expect(count_users(fresh_db(true)))->toEqual(4);
        });
        it('list_users pages through users ordered by name, id/name/email only', function () {
            $pdo = fresh_db(true);
            $page1 = list_users($pdo, 1, 2);
            expect(names($page1))->toEqual(['Ada', 'Alan']);
            expect(array_keys($page1[0]))->toEqual(['id', 'name', 'email']);
            expect(names(list_users($pdo, 2, 2)))->toEqual(['Grace', "O'Brien"]);
            expect(list_users($pdo, 9, 2))->toEqual([]);
            expect(count(list_users($pdo, 1, 10)))->toEqual(4);
        });
        it('rename_user and delete_user report whether a row was affected', function () {
            $pdo = fresh_db(true);
            expect(rename_user($pdo, 1, 'Ada L.'))->toEqual(true);
            expect(find_user_by_email($pdo, 'ada@example.org')['name'])->toEqual('Ada L.');
            expect(rename_user($pdo, 999, 'Nobody'))->toEqual(false);
            expect(delete_user($pdo, 3))->toEqual(true);
            expect(delete_user($pdo, 3))->toEqual(false);
            expect(count_users($pdo))->toEqual(3);
        });
    });

    describe('hard', function () {
        it('register hashes the password, lower-cases the email, and refuses duplicates', function () {
            $pdo = fresh_db();
            expect(register($pdo, 'Ada', 'Ada@Example.org', 'Passw0rd'))->toEqual(['ok' => true, 'id' => 1]);
            $row = find_user_by_email($pdo, 'ada@example.org');
            expect($row['name'])->toEqual('Ada');
            expect($row['password_hash'] !== 'Passw0rd')->toEqual(true);
            expect(password_verify('Passw0rd', $row['password_hash']))->toEqual(true);
            expect(register($pdo, 'Ada2', 'ada@example.org', 'Passw0rd'))->toEqual(['ok' => false, 'error' => 'Email already registered']);
            expect(register($pdo, 'Ada3', 'ADA@EXAMPLE.ORG', 'Passw0rd'))->toEqual(['ok' => false, 'error' => 'Email already registered']);
            expect(count_users($pdo))->toEqual(1);
        }, [
            'strtolower() the email first, then look it up with find_user_by_email; if found, return the error.',
            'Otherwise insert_user with password_hash($password, PASSWORD_DEFAULT) and return the id.',
        ]);
        it('login verifies the password and never returns the hash', function () {
            $pdo = fresh_db();
            register($pdo, 'Ada', 'ada@example.org', 'Passw0rd');
            $u = login($pdo, 'ada@example.org', 'Passw0rd');
            expect($u['name'])->toEqual('Ada');
            expect(array_key_exists('password_hash', $u))->toEqual(false);
            expect(login($pdo, 'ADA@example.org', 'Passw0rd')['name'])->toEqual('Ada');
            expect(login($pdo, 'ada@example.org', 'wrong'))->toEqual(null);
            expect(login($pdo, 'nobody@example.org', 'Passw0rd'))->toEqual(null);
        }, [
            'Find the user, password_verify() against its password_hash, unset() the hash from the array before returning it.',
        ]);
        it('transfer_points moves points atomically and refuses bad transfers', function () {
            $pdo = fresh_db(true);
            $points = fn($id) => (int) $pdo->query("SELECT points FROM users WHERE id = $id")->fetchColumn();
            expect(transfer_points($pdo, 1, 3, 4))->toEqual(true);
            expect([$points(1), $points(3)])->toEqual([6, 4]);
            expect(transfer_points($pdo, 1, 3, 50))->toEqual(false);
            expect([$points(1), $points(3)])->toEqual([6, 4]);
            expect(transfer_points($pdo, 1, 999, 1))->toEqual(false);
            expect($points(1))->toEqual(6);
            expect(transfer_points($pdo, 999, 1, 1))->toEqual(false);
            expect(transfer_points($pdo, 1, 3, 0))->toEqual(false);
            expect(transfer_points($pdo, 1, 3, -2))->toEqual(false);
            expect([$points(1), $points(3)])->toEqual([6, 4]);
            expect($pdo->inTransaction())->toEqual(false);
        }, [
            'beginTransaction(); read both balances; if anything is wrong, rollBack() and return false.',
            'Otherwise run the two UPDATEs and commit(). Leave no transaction open on any path.',
        ]);
        it('search_users matches name or email, treats % _ and quotes as plain text', function () {
            $pdo = fresh_db(true);
            insert_user($pdo, '100% Legit', 'legit@example.org', 'h');
            insert_user($pdo, 'under_score', 'us@example.org', 'h');
            expect(names(search_users($pdo, 'ad')))->toEqual(['Ada']);
            expect(names(search_users($pdo, 'AD')))->toEqual(['Ada']);
            expect(names(search_users($pdo, 'example.org')))->toEqual(['100% Legit', 'Ada', 'Alan', 'Grace', "O'Brien", 'under_score']);
            expect(names(search_users($pdo, '%')))->toEqual(['100% Legit']);
            expect(names(search_users($pdo, '_')))->toEqual(['under_score']);
            expect(names(search_users($pdo, "O'B")))->toEqual(["O'Brien"]);
            expect(search_users($pdo, "' OR 1=1 --"))->toEqual([]);
            expect(array_keys(search_users($pdo, 'Ada')[0]))->toEqual(['id', 'name', 'email']);
        }, [
            'Escape the term before wrapping it in %...%: replace \\ with \\\\, % with \\% and _ with \\_.',
            "WHERE name LIKE ? ESCAPE '\\\\' OR email LIKE ? ESCAPE '\\\\'   with the same value bound twice.",
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
