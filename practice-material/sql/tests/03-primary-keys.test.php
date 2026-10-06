<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('03 - Primary keys', function () {
    $errors = db_run_exercise('03-primary-keys.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `users.id` is the primary key', function () {
            expect(db_primary_key('users'))->toEqual(['id']);
            expect(db_type('users', 'id'))->toEqual('int');
            expect(db_type('users', 'username'))->toEqual('varchar(50)');
        });
        it('Task 1: two users with the same id, or a NULL id, are rejected', function () {
            expect_accepted(["INSERT INTO users (id, username) VALUES (1, 'ada')", "INSERT INTO users (id, username) VALUES (2, 'alan')"]);
            expect_rejected(["INSERT INTO users (id, username) VALUES (1, 'ada')", "INSERT INTO users (id, username) VALUES (1, 'alan')"], 'id is the primary key');
            expect_rejected("INSERT INTO users (id, username) VALUES (NULL, 'nobody')", 'a primary key can never be NULL');
        });
        it('Task 2: `posts.id` is an AUTO_INCREMENT primary key that numbers rows by itself', function () {
            expect(db_primary_key('posts'))->toEqual(['id']);
            expect(db_auto_increment('posts', 'id'))->toEqual(true);
            db_probe_query(["INSERT INTO posts (title) VALUES ('Hello')", "INSERT INTO posts (title) VALUES ('World')"],
                "SELECT id FROM posts ORDER BY id", function ($rows) {
                    expect(count($rows))->toEqual(2);
                    expect((int) $rows[1]['id'] > (int) $rows[0]['id'])->toEqual(true);
                });
        });
        it('Task 3: `countries.code` CHAR(2) is the primary key', function () {
            expect(db_primary_key('countries'))->toEqual(['code']);
            expect(db_type('countries', 'code'))->toEqual('char(2)');
            expect_rejected(["INSERT INTO countries VALUES ('US', 'United States')", "INSERT INTO countries VALUES ('US', 'Also US')"], 'code is the primary key');
        });
    });

    describe('medium', function () {
        it('Task 4: `enrollments` has the composite key (student_id, course_id)', function () {
            expect(db_primary_key('enrollments'))->toEqual(['student_id', 'course_id']);
        });
        it('Task 4: the same student in the same course twice is rejected, other combinations are fine', function () {
            expect_accepted(["INSERT INTO enrollments VALUES (1, 1)", "INSERT INTO enrollments VALUES (1, 2)", "INSERT INTO enrollments VALUES (2, 1)"]);
            expect_rejected(["INSERT INTO enrollments VALUES (1, 1)", "INSERT INTO enrollments VALUES (1, 1)"], '(1, 1) already exists');
        });
        it('Task 5: `emails` has id as primary key and a UNIQUE address', function () {
            expect(db_primary_key('emails'))->toEqual(['id']);
            expect(db_auto_increment('emails', 'id'))->toEqual(true);
            expect(db_nullable('emails', 'address'))->toEqual(false);
            expect(db_has_index_on('emails', ['address'], true))->toEqual(true);
            expect_rejected(["INSERT INTO emails (address) VALUES ('a@x.org')", "INSERT INTO emails (address) VALUES ('a@x.org')"], 'address is UNIQUE');
        });
        it('Task 6: `legacy` got a primary key on id and kept its three rows', function () {
            expect(db_primary_key('legacy'))->toEqual(['id']);
            expect(db_count('legacy'))->toEqual(3);
        });
    });

    describe('hard', function () {
        it('Task 7: `seats` has the three-column key (flight_no, seat_row, seat_letter)', function () {
            expect(db_primary_key('seats'))->toEqual(['flight_no', 'seat_row', 'seat_letter']);
            expect_accepted(["INSERT INTO seats VALUES ('BA0117', 12, 'A', 'Ada')", "INSERT INTO seats VALUES ('BA0117', 12, 'B', 'Alan')", "INSERT INTO seats VALUES ('BA0118', 12, 'A', 'Grace')"]);
            expect_rejected(["INSERT INTO seats VALUES ('BA0117', 12, 'A', 'Ada')", "INSERT INTO seats VALUES ('BA0117', 12, 'A', 'Alan')"], 'seat 12A on BA0117 is already taken');
        }, [
            'A key over several columns is written on its own line inside the CREATE TABLE: PRIMARY KEY (a, b, c).',
            'PRIMARY KEY (flight_no, seat_row, seat_letter)',
        ]);
        it('Task 8: the first ticket gets id 1000', function () {
            expect(db_primary_key('tickets'))->toEqual(['id']);
            expect(db_type('tickets', 'id'))->toEqual('bigint unsigned');
            expect(db_auto_increment('tickets', 'id'))->toEqual(true);
            db_probe_query(["INSERT INTO tickets (event) VALUES ('Gig')"], "SELECT MIN(id) AS id FROM tickets", function ($rows) {
                expect((int) $rows[0]['id'])->toEqual(1000);
            });
        }, [
            'Table options go after the closing parenthesis: CREATE TABLE t (...) AUTO_INCREMENT = 1000;',
            "CREATE TABLE tickets (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, event VARCHAR(100)) AUTO_INCREMENT = 1000;",
        ]);
        it('Task 9: `bad_pk` now has an auto-numbered id as its key, first, and the rows got 1, 2, 3', function () {
            expect(db_column_names('bad_pk'))->toEqual(['id', 'name']);
            expect(db_primary_key('bad_pk'))->toEqual(['id']);
            expect(db_auto_increment('bad_pk', 'id'))->toEqual(true);
            expect(db_query('SELECT id, name FROM bad_pk ORDER BY id'))->toEqual([
                ['id' => 1, 'name' => 'Ada'], ['id' => 2, 'name' => 'Alan'], ['id' => 3, 'name' => 'Grace'],
            ]);
            expect_accepted("INSERT INTO bad_pk (name) VALUES ('Ada')");
        }, [
            'One ALTER TABLE can do several things, separated by commas: ALTER TABLE t DROP ..., ADD ...;',
            'ADD COLUMN can say where the column goes: ADD COLUMN id INT ... FIRST.',
            'ALTER TABLE bad_pk DROP PRIMARY KEY, ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
