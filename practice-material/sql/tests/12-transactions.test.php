<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('12 - Transactions and hashing', function () {
    $errors = db_run_exercise('12-transactions.sql');

    // Anything the file left uncommitted is thrown away here, so a missing
    // COMMIT shows up as a failing test instead of passing by accident.
    db()->exec('ROLLBACK');

    // The file's own statements (comments stripped, so the task text does
    // not count), for the few checks that need to see how something was
    // done rather than only what it left behind.
    $source = implode("\n", db_split_sql(file_get_contents(__DIR__ . '/../exercises/12-transactions.sql')));
    $uses = fn(string $word) => stripos($source, $word) !== false;

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    $balances = fn() => db_text_rows(db_query('SELECT account_id, owner, balance FROM accounts ORDER BY account_id'));

    describe('easy', function () use ($balances, $uses) {
        it('Task 1: 100.00 moved from Ada to Alan and was committed', function () use ($balances, $uses) {
            if (!$uses('COMMIT')) throw new Exception('The file never says COMMIT, so nothing inside a transaction can survive.');
            $rows = $balances();
            expect($rows[1]['balance'])->toEqual('300.00');
            // Ada's final balance is checked in Task 4 (it changes again there);
            // here it is enough that no money appeared or vanished.
            expect((string) db_value('SELECT SUM(balance) FROM accounts'))->toEqual('750.00');
        }, [
            'START TRANSACTION; then two UPDATEs on accounts (balance = balance - 100.00 ... and balance = balance + 100.00 ...); then COMMIT;',
        ]);
        it('Task 2: the Mistake account was rolled back', function () use ($uses) {
            if (!$uses('Mistake')) throw new Exception("The file never inserts the 'Mistake' account. Insert it inside a transaction, then ROLLBACK.");
            if (!$uses('ROLLBACK')) throw new Exception('The file never says ROLLBACK.');
            expect((int) db_value("SELECT COUNT(*) FROM accounts WHERE owner = 'Mistake'"))->toEqual(0);
            expect(db_count('accounts'))->toEqual(3);
        });
        it('Task 3: ada\'s pass_hash is SHA2 of the password, 64 characters long', function () {
            $row = db_query("SELECT pass_hash, pass_hash = SHA2('correct horse', 256) AS ok, CHAR_LENGTH(pass_hash) AS len FROM users WHERE username = 'ada'");
            if (count($row) === 0) throw new Exception("There is no user called 'ada'.");
            if ($row[0]['pass_hash'] === 'correct horse') throw new Exception('The password itself was stored. Store SHA2(..., 256) of it instead.');
            // Task 6 may already have changed the hash; either the original or the new one is fine here.
            $okNew = (int) db_value("SELECT pass_hash = SHA2('new horse', 256) FROM users WHERE username = 'ada'");
            expect((int) $row[0]['ok'] === 1 || $okNew === 1)->toEqual(true);
            expect((int) $row[0]['len'])->toEqual(64);
        });
    });

    describe('medium', function () use ($balances, $uses) {
        it('Task 4: the Grace to Ada transfer changed both balances and left a receipt', function () use ($balances) {
            $rows = $balances();
            expect($rows[2]['balance'])->toEqual('25.00');
            expect($rows[0]['balance'])->toEqual('425.00');
            expect(db_text_rows(db_query('SELECT from_account, to_account, amount FROM transfers')))->toEqual([
                ['from_account' => '3', 'to_account' => '1', 'amount' => '25.00'],
            ]);
        }, [
            'Three statements between START TRANSACTION and COMMIT: two UPDATEs and one INSERT INTO transfers (from_account, to_account, amount).',
        ]);
        it('Task 5: alan survived the savepoint rollback and alna did not', function () use ($uses) {
            if (!$uses('SAVEPOINT')) throw new Exception('The file never creates a SAVEPOINT.');
            if (!$uses('ROLLBACK TO')) throw new Exception('The file never says ROLLBACK TO <savepoint>.');
            expect((int) db_value("SELECT COUNT(*) FROM users WHERE username = 'alan' AND pass_hash = SHA2('enigma', 256)"))->toEqual(1);
            expect((int) db_value("SELECT COUNT(*) FROM users WHERE username = 'alna'"))->toEqual(0);
        }, [
            'START TRANSACTION; INSERT alan; SAVEPOINT before_typo; INSERT alna; ROLLBACK TO before_typo; COMMIT;',
            'ROLLBACK TO undoes only what came after the savepoint. The COMMIT then keeps alan.',
        ]);
        it('Task 6: ada\'s password changed, eve\'s did not', function () {
            expect((int) db_value("SELECT pass_hash = SHA2('new horse', 256) FROM users WHERE username = 'ada'"))->toEqual(1);
            expect((int) db_value("SELECT pass_hash = SHA2('eve-original', 256) FROM users WHERE username = 'eve'"))->toEqual(1);
        }, [
            'UPDATE users SET pass_hash = SHA2(\'new horse\', 256) WHERE username = \'ada\' AND pass_hash = SHA2(\'correct horse\', 256);',
            'The eve statement has the same shape with the wrong old password in the WHERE, so it matches no row and changes nothing. That is the point: the database checked the old password for you.',
        ]);
    });

    describe('hard', function () use ($uses) {
        it('Task 7: the new order\'s items point at it through LAST_INSERT_ID()', function () use ($uses) {
            if (!$uses('LAST_INSERT_ID')) throw new Exception('The file never calls LAST_INSERT_ID(). Use it for the order_id instead of typing the number.');
            $order = db_query("SELECT order_id, total FROM orders WHERE customer = 'Ada'");
            if (count($order) !== 1) throw new Exception("Expected exactly one order for customer 'Ada', found " . count($order) . '.');
            expect((string) $order[0]['total'])->toEqual('70.00');
            $items = db_text_rows(db_query('SELECT product, price FROM order_items WHERE order_id = ? ORDER BY product', [$order[0]['order_id']]));
            expect($items)->toEqual([
                ['product' => 'Keyboard', 'price' => '49.00'],
                ['product' => 'Mouse',    'price' => '21.00'],
            ]);
            expect((int) db_value('SELECT COUNT(*) FROM order_items WHERE order_id = 1'))->toEqual(1);
        }, [
            'INSERT the order first. Right after an INSERT, LAST_INSERT_ID() is the AUTO_INCREMENT value it produced, on this connection only.',
            'INSERT INTO order_items (order_id, product, price) VALUES (LAST_INSERT_ID(), \'Keyboard\', 49.00), (LAST_INSERT_ID(), \'Mouse\', 21.00); Both rows in one INSERT, so LAST_INSERT_ID() is still the order\'s id.',
        ]);
        it('Task 8: the token is stored encrypted and `v_tokens` decrypts it', function () {
            $raw = db_query("SELECT token = 'ghp_s3cr3t' AS plain FROM secrets WHERE label = 'github'");
            if (count($raw) === 0) throw new Exception("There is no row in secrets with label 'github'.");
            if ((int) $raw[0]['plain'] === 1) throw new Exception('The token was stored as plain text. Wrap it in AES_ENCRYPT(..., key).');
            expect_view('v_tokens', ['label', 'token'], [
                ['label' => 'github', 'token' => 'ghp_s3cr3t'],
            ]);
        }, [
            'INSERT INTO secrets (label, token) VALUES (\'github\', AES_ENCRYPT(\'ghp_s3cr3t\', \'course-key\'));',
            'CREATE VIEW v_tokens AS SELECT label, CAST(AES_DECRYPT(token, \'course-key\') AS CHAR) AS token FROM secrets ORDER BY label;',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
