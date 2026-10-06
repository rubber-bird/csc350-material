<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('02 - Data types', function () {
    $errors = db_run_exercise('02-data-types.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `prices.price` is DECIMAL(10,2) and stores 19.99 exactly', function () {
            expect(db_type('prices', 'item'))->toEqual('varchar(100)');
            expect(db_type('prices', 'price'))->toEqual('decimal(10,2)');
            db_probe_query(["INSERT INTO prices (item, price) VALUES ('pen', 19.99)"], "SELECT price FROM prices", function ($rows) {
                expect($rows[0]['price'])->toEqual('19.99');
            });
        });
        it('Task 2: `events` has a DATETIME and a DATE', function () {
            expect(db_type('events', 'title'))->toEqual('varchar(100)');
            expect(db_type('events', 'starts_at'))->toEqual('datetime');
            expect(db_type('events', 'on_date'))->toEqual('date');
        });
        it('Task 3: `flags.is_active` is BOOLEAN, which the server stores as TINYINT(1)', function () {
            expect(db_type('flags', 'name'))->toEqual('varchar(50)');
            expect(db_type('flags', 'is_active'))->toEqual('tinyint(1)');
        });
    });

    describe('medium', function () {
        it('Task 4: `people.name` is required, `people.bio` is optional TEXT', function () {
            expect(db_type('people', 'name'))->toEqual('varchar(100)');
            expect(db_nullable('people', 'name'))->toEqual(false);
            expect(db_type('people', 'bio'))->toEqual('text');
            expect(db_nullable('people', 'bio'))->toEqual(true);
        });
        it('Task 4: a person without a name is rejected', function () {
            expect_rejected("INSERT INTO people (bio) VALUES ('Likes cats.')", 'name is NOT NULL');
            expect_rejected("INSERT INTO people (name, bio) VALUES (NULL, 'Likes cats.')", 'name is NOT NULL');
            expect_accepted("INSERT INTO people (name) VALUES ('Ada')");
        });
        it('Task 5: `accounts` fills in balance 0.00 and created_at by itself', function () {
            expect(db_type('accounts', 'balance'))->toEqual('decimal(12,2)');
            expect(db_nullable('accounts', 'balance'))->toEqual(false);
            expect(db_default('accounts', 'balance'))->toEqual('0.00');
            expect(db_type('accounts', 'created_at'))->toEqual('timestamp');
            expect(db_default('accounts', 'created_at'))->toEqual('CURRENT_TIMESTAMP');
            db_probe_query(["INSERT INTO accounts (id) VALUES (1)"], "SELECT balance, created_at FROM accounts", function ($rows) {
                expect($rows[0]['balance'])->toEqual('0.00');
                expect($rows[0]['created_at'] !== null)->toEqual(true);
            });
        });
        it("Task 6: `sizes.label` is ENUM('S','M','L','XL') and rejects 'XXL'", function () {
            expect(db_type('sizes', 'label'))->toEqual("enum('S','M','L','XL')");
            expect(db_nullable('sizes', 'label'))->toEqual(false);
            expect_accepted("INSERT INTO sizes (label) VALUES ('M')");
            expect_rejected("INSERT INTO sizes (label) VALUES ('XXL')", "'XXL' is not in the list");
        });
    });

    describe('hard', function () {
        it('Task 7: `counters.hits` is INT UNSIGNED NOT NULL DEFAULT 0, `total` is BIGINT', function () {
            expect(db_type('counters', 'hits'))->toEqual('int unsigned');
            expect(db_nullable('counters', 'hits'))->toEqual(false);
            expect(db_default('counters', 'hits'))->toEqual('0');
            expect(db_type('counters', 'total'))->toEqual('bigint');
            expect_rejected("INSERT INTO counters (name, hits) VALUES ('home', -1)", 'hits is UNSIGNED');
            expect_accepted("INSERT INTO counters (name, total) VALUES ('home', 9000000000)");
        }, [
            'UNSIGNED goes right after the integer type: INT UNSIGNED. BIGINT holds up to 9.2 quintillion.',
            'hits INT UNSIGNED NOT NULL DEFAULT 0,  total BIGINT',
        ]);
        it('Task 8: `people_ages.age` rejects 200 and -1 but accepts 30', function () {
            expect(db_type('people_ages', 'age'))->toEqual('tinyint unsigned');
            expect_accepted("INSERT INTO people_ages (name, age) VALUES ('Ada', 30)");
            expect_rejected("INSERT INTO people_ages (name, age) VALUES ('Old', 200)", 'the CHECK says age <= 150');
            expect_rejected("INSERT INTO people_ages (name, age) VALUES ('New', -1)", 'age is UNSIGNED');
        }, [
            'TINYINT UNSIGNED holds 0 to 255. The rule about 150 needs a CHECK constraint, written as its own line in the CREATE TABLE.',
            'age TINYINT UNSIGNED,  CHECK (age <= 150)',
        ]);
        it("Task 9: `codes.code` is CHAR(3) NOT NULL and rejects 'ABCD'", function () {
            expect(db_type('codes', 'code'))->toEqual('char(3)');
            expect(db_nullable('codes', 'code'))->toEqual(false);
            expect_accepted("INSERT INTO codes (code) VALUES ('LHR')");
            expect_rejected("INSERT INTO codes (code) VALUES ('ABCD')", 'CHAR(3) holds at most 3 characters');
        }, [
            'CHAR(n) is a fixed-length string. In strict mode a longer value is an error, not truncated.',
            'code CHAR(3) NOT NULL',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
