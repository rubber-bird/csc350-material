<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('07 - Migrations', function () {
    $errors = db_run_exercise('07-migrations.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    $sorted = function (array $a) { sort($a); return $a; };

    describe('easy', function () use ($sorted) {
        it('Task 1: `people` has first_name and last_name filled from full_name, and no full_name', function () use ($sorted) {
            expect($sorted(db_column_names('people')))->toEqual(['email', 'first_name', 'id', 'last_name']);
            expect(db_type('people', 'first_name'))->toEqual('varchar(100)');
            expect(db_nullable('people', 'first_name'))->toEqual(false);
            expect(db_nullable('people', 'last_name'))->toEqual(false);
            expect(db_query('SELECT first_name, last_name FROM people ORDER BY id'))->toEqual([
                ['first_name' => 'Ada', 'last_name' => 'Lovelace'],
                ['first_name' => 'Alan', 'last_name' => 'Turing'],
                ['first_name' => 'Grace', 'last_name' => 'Hopper'],
            ]);
        });
        it('Task 2: `people.email` is NOT NULL and Alan got alan@example.org', function () {
            expect(db_nullable('people', 'email'))->toEqual(false);
            expect(db_query('SELECT email FROM people ORDER BY id'))->toEqual([
                ['email' => 'ada@example.org'], ['email' => 'alan@example.org'], ['email' => 'grace@example.org'],
            ]);
            expect_rejected("INSERT INTO people (first_name, last_name, email) VALUES ('No', 'Mail', NULL)", 'email is NOT NULL now');
        });
    });

    describe('medium', function () {
        it("Task 3: `tickets.status` is ENUM('open','in_progress','closed') and the values were cleaned", function () {
            expect(db_type('tickets', 'status'))->toEqual("enum('open','in_progress','closed')");
            expect(db_nullable('tickets', 'status'))->toEqual(false);
            expect(db_query('SELECT status, COUNT(*) AS n FROM tickets GROUP BY status ORDER BY status'))->toEqual([
                ['status' => 'open', 'n' => 2], ['status' => 'in_progress', 'n' => 1], ['status' => 'closed', 'n' => 2],
            ]);
            expect_rejected("INSERT INTO tickets (title, status) VALUES ('Bad', 'pending')", "'pending' is not one of the three values");
        });
        it('Task 4: `posts.slug` is NOT NULL, UNIQUE and filled from the title', function () {
            expect(db_type('posts', 'slug'))->toEqual('varchar(200)');
            expect(db_nullable('posts', 'slug'))->toEqual(false);
            expect(db_has_index_on('posts', ['slug'], true))->toEqual(true);
            expect(db_query('SELECT slug FROM posts ORDER BY id'))->toEqual([['slug' => 'hello-world'], ['slug' => 'second-post']]);
        });
    });

    describe('hard', function () use ($sorted) {
        it('Task 5: duplicates are gone (ids 1, 2, 4 remain) and email is UNIQUE', function () {
            expect(db_query('SELECT id, email FROM subscribers ORDER BY id'))->toEqual([
                ['id' => 1, 'email' => 'ada@example.org'], ['id' => 2, 'email' => 'alan@example.org'], ['id' => 4, 'email' => 'grace@example.org'],
            ]);
            expect(db_index_columns('subscribers', 'idx_subscribers_email'))->toEqual(['email']);
            expect(db_index_is_unique('subscribers', 'idx_subscribers_email'))->toEqual(true);
        }, [
            'Join the table to itself on equal emails. For each pair where s.id > t.id, s is a duplicate of an earlier row.',
            'DELETE s FROM subscribers s JOIN subscribers t ON s.email = t.email AND s.id > t.id;',
            'Then: CREATE UNIQUE INDEX idx_subscribers_email ON subscribers (email);',
        ]);
        it('Task 6: `customers` holds each person once and `orders` points at them', function () use ($sorted) {
            expect(db_primary_key('customers'))->toEqual(['id']);
            expect(db_has_index_on('customers', ['email'], true))->toEqual(true);
            expect(db_query('SELECT name, email FROM customers ORDER BY email'))->toEqual([
                ['name' => 'Ada Lovelace', 'email' => 'ada@example.org'],
                ['name' => 'Alan Turing', 'email' => 'alan@example.org'],
                ['name' => 'Grace Hopper', 'email' => 'grace@example.org'],
            ]);
            expect($sorted(db_column_names('orders')))->toEqual(['customer_id', 'id', 'total']);
            expect(db_nullable('orders', 'customer_id'))->toEqual(false);
            $fk = db_foreign_key('orders', ['customer_id']);
            expect($fk['ref_table'])->toEqual('customers');
            expect(db_query('SELECT c.email, SUM(o.total) AS spent FROM orders o JOIN customers c ON c.id = o.customer_id GROUP BY c.email ORDER BY c.email'))->toEqual([
                ['email' => 'ada@example.org', 'spent' => '37.50'],
                ['email' => 'alan@example.org', 'spent' => '99.99'],
                ['email' => 'grace@example.org', 'spent' => '7.00'],
            ]);
        }, [
            'INSERT INTO customers (name, email) SELECT DISTINCT customer_name, customer_email FROM orders;',
            'UPDATE orders o JOIN customers c ON c.email = o.customer_email SET o.customer_id = c.id;',
            'Add customer_id as NULL first, fill it, then MODIFY it to NOT NULL, ADD CONSTRAINT ... FOREIGN KEY, and DROP the two old columns.',
        ]);
        it('Task 7: `posts.id` and `comments.post_id` are BIGINT UNSIGNED and fk_comments_post is back', function () {
            expect(db_type('posts', 'id'))->toEqual('bigint unsigned');
            expect(db_auto_increment('posts', 'id'))->toEqual(true);
            expect(db_type('comments', 'post_id'))->toEqual('bigint unsigned');
            $fk = db_foreign_key('comments', ['post_id']);
            expect($fk['name'])->toEqual('fk_comments_post');
            expect($fk['on_delete'])->toEqual('CASCADE');
            expect(db_count('comments'))->toEqual(3);
            db_probe_query(["DELETE FROM posts WHERE id = 1"], "SELECT COUNT(*) AS n FROM comments", fn($rows) => expect((int) $rows[0]['n'])->toEqual(1));
        }, [
            'ALTER TABLE comments DROP FOREIGN KEY fk_comments_post; comes first.',
            'MODIFY needs the whole column definition again, including NOT NULL and AUTO_INCREMENT: MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT.',
            'Finish with ALTER TABLE comments ADD CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE;',
        ]);
        it('Task 8: chk_loans_dates rejects a loan due before it was borrowed, and days_out is computed', function () {
            expect(db_check_constraints('loans'))->toEqual(['chk_loans_dates']);
            expect_rejected("INSERT INTO loans (borrowed_on, due_on) VALUES ('2024-05-10', '2024-05-01')", 'due_on is before borrowed_on');
            expect_accepted("INSERT INTO loans (borrowed_on, due_on) VALUES ('2024-05-01', '2024-05-01')");
            expect(db_generated('loans', 'days_out'))->toEqual('STORED');
            expect(db_query('SELECT days_out FROM loans ORDER BY id'))->toEqual([['days_out' => 21], ['days_out' => 21]]);
        }, [
            'ALTER TABLE loans ADD CONSTRAINT chk_loans_dates CHECK (due_on >= borrowed_on);',
            'A generated column is declared with AS (expression) STORED: ADD COLUMN days_out INT AS (DATEDIFF(due_on, borrowed_on)) STORED.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
