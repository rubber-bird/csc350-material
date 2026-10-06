<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('05 - Indexes', function () {
    $errors = db_run_exercise('05-indexes.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: idx_customers_last_name is an index on customers(last_name)', function () {
            expect(db_index_columns('customers', 'idx_customers_last_name'))->toEqual(['last_name']);
            expect(db_index_is_unique('customers', 'idx_customers_last_name'))->toEqual(false);
        });
        it('Task 2: idx_customers_email is UNIQUE and rejects a duplicate email', function () {
            expect(db_index_columns('customers', 'idx_customers_email'))->toEqual(['email']);
            expect(db_index_is_unique('customers', 'idx_customers_email'))->toEqual(true);
            expect_rejected("INSERT INTO customers (email, first_name, last_name) VALUES ('ada@example.org', 'Ada', 'Again')", 'that email is already taken');
        });
        it('Task 3: idx_orders_customer_id is an index on orders(customer_id)', function () {
            expect(db_index_columns('orders', 'idx_orders_customer_id'))->toEqual(['customer_id']);
        });
    });

    describe('medium', function () {
        it('Task 4: idx_customers_name covers (last_name, first_name) in that order', function () {
            expect(db_index_columns('customers', 'idx_customers_name'))->toEqual(['last_name', 'first_name']);
        });
        it('Task 5: idx_orders_status_placed_at covers (status, placed_at) in that order', function () {
            expect(db_index_columns('orders', 'idx_orders_status_placed_at'))->toEqual(['status', 'placed_at']);
        });
        it('Task 6: idx_customers_city_old is gone', function () {
            expect(array_key_exists('idx_customers_city_old', db_indexes('customers')))->toEqual(false);
        });
    });

    describe('hard', function () {
        it('Task 7: `sessions` has a CHAR(64) primary key and two indexes declared in CREATE TABLE', function () {
            expect(db_primary_key('sessions'))->toEqual(['token']);
            expect(db_type('sessions', 'token'))->toEqual('char(64)');
            expect(db_nullable('sessions', 'user_id'))->toEqual(false);
            expect(db_nullable('sessions', 'expires_at'))->toEqual(false);
            expect(db_index_columns('sessions', 'idx_sessions_user_id'))->toEqual(['user_id']);
            expect(db_index_columns('sessions', 'idx_sessions_expires_at'))->toEqual(['expires_at']);
        }, [
            'Inside CREATE TABLE, after the columns, add lines like: INDEX idx_name (column)',
            'CREATE TABLE sessions (token CHAR(64) PRIMARY KEY, user_id INT NOT NULL, expires_at DATETIME NOT NULL, INDEX idx_sessions_user_id (user_id), INDEX idx_sessions_expires_at (expires_at));',
        ]);
        it('Task 8: idx_articles_title indexes only the first 20 characters of title', function () {
            $idx = db_index('articles', 'idx_articles_title');
            expect($idx['columns'])->toEqual(['title']);
            expect($idx['prefix'])->toEqual([20]);
        }, [
            'The prefix length goes in parentheses after the column name in the index definition.',
            'CREATE INDEX idx_articles_title ON articles (title(20));',
        ]);
        it("Task 9: ft_articles_body is a FULLTEXT index and MATCH ... AGAINST('database') finds the articles", function () {
            $idx = db_index('articles', 'ft_articles_body');
            expect($idx['columns'])->toEqual(['body']);
            expect($idx['type'])->toEqual('FULLTEXT');
            $n = (int) db_value("SELECT COUNT(*) FROM articles WHERE MATCH(body) AGAINST('database')");
            expect($n)->toEqual(1);
        }, [
            'It is CREATE FULLTEXT INDEX instead of CREATE INDEX. Only CHAR, VARCHAR and TEXT columns can have one.',
            'CREATE FULLTEXT INDEX ft_articles_body ON articles (body);',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
