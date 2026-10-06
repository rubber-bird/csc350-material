<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('06 - Library schema', function () {
    $errors = db_run_exercise('06-library-schema.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    // Rows the behaviour tests start from.
    $seed = [
        "INSERT INTO authors (id, name) VALUES (1, 'Ursula K. Le Guin'), (2, 'Octavia Butler')",
        "INSERT INTO books (id, isbn, title, author_id, price) VALUES (1, '9780061054884', 'The Dispossessed', 1, 9.99), (2, '9780807083697', 'Kindred', 2, 12.00)",
        "INSERT INTO members (id, email, name) VALUES (1, 'ada@example.org', 'Ada'), (2, 'alan@example.org', 'Alan')",
        "INSERT INTO loans (book_id, member_id, borrowed_on, due_on) VALUES (1, 1, '2024-03-01', '2024-03-22')",
    ];

    describe('authors', function () {
        it('has an auto-numbered id primary key and a required name', function () {
            expect(db_primary_key('authors'))->toEqual(['id']);
            expect(db_auto_increment('authors', 'id'))->toEqual(true);
            expect(db_type('authors', 'name'))->toEqual('varchar(100)');
            expect(db_nullable('authors', 'name'))->toEqual(false);
        });
    });

    describe('books', function () use ($seed) {
        it('has the right columns and types', function () {
            expect(db_column_names('books'))->toEqual(['id', 'isbn', 'title', 'author_id', 'price', 'published_on']);
            expect(db_primary_key('books'))->toEqual(['id']);
            expect(db_auto_increment('books', 'id'))->toEqual(true);
            expect(db_type('books', 'isbn'))->toEqual('char(13)');
            expect(db_type('books', 'title'))->toEqual('varchar(200)');
            expect(db_type('books', 'price'))->toEqual('decimal(8,2)');
            expect(db_type('books', 'published_on'))->toEqual('date');
        });
        it('isbn is required and unique', function () use ($seed) {
            expect(db_nullable('books', 'isbn'))->toEqual(false);
            expect(db_has_index_on('books', ['isbn'], true))->toEqual(true);
            expect_rejected(array_merge($seed, ["INSERT INTO books (isbn, title, author_id) VALUES ('9780061054884', 'A copy', 1)"]), 'that ISBN already exists');
        });
        it('price defaults to 0.00 and published_on may be NULL', function () use ($seed) {
            expect(db_nullable('books', 'price'))->toEqual(false);
            expect(db_default('books', 'price'))->toEqual('0.00');
            expect(db_nullable('books', 'published_on'))->toEqual(true);
            db_probe_query(array_merge($seed, ["INSERT INTO books (isbn, title, author_id) VALUES ('9780000000001', 'Free book', 1)"]),
                "SELECT price, published_on FROM books WHERE isbn = '9780000000001'",
                fn($rows) => expect($rows)->toEqual([['price' => '0.00', 'published_on' => null]]));
        });
        it('author_id references authors(id) and an author with books cannot be deleted', function () use ($seed) {
            expect(db_nullable('books', 'author_id'))->toEqual(false);
            $fk = db_foreign_key('books', ['author_id']);
            expect($fk['ref_table'])->toEqual('authors');
            expect($fk['on_delete'])->toEqual('RESTRICT');
            expect_rejected("INSERT INTO books (isbn, title, author_id) VALUES ('9780000000002', 'Orphan', 999)", 'author 999 does not exist');
            expect_rejected(array_merge($seed, ["DELETE FROM authors WHERE id = 1"]), 'author 1 still has a book');
        }, [
            'ON DELETE RESTRICT is the default, so a plain FOREIGN KEY (author_id) REFERENCES authors (id) is enough.',
        ]);
    });

    describe('members', function () use ($seed) {
        it('has an auto-numbered id, a required unique email, a required name', function () use ($seed) {
            expect(db_primary_key('members'))->toEqual(['id']);
            expect(db_auto_increment('members', 'id'))->toEqual(true);
            expect(db_type('members', 'email'))->toEqual('varchar(255)');
            expect(db_nullable('members', 'email'))->toEqual(false);
            expect(db_has_index_on('members', ['email'], true))->toEqual(true);
            expect(db_nullable('members', 'name'))->toEqual(false);
            expect_rejected(array_merge($seed, ["INSERT INTO members (email, name) VALUES ('ada@example.org', 'Another Ada')"]), 'that email is already registered');
        });
        it('joined_at is filled in with the current time', function () {
            expect(db_type('members', 'joined_at'))->toEqual('timestamp');
            expect(db_nullable('members', 'joined_at'))->toEqual(false);
            expect(db_default('members', 'joined_at'))->toEqual('CURRENT_TIMESTAMP');
        });
    });

    describe('loans', function () use ($seed) {
        it('has the composite key (book_id, member_id, borrowed_on)', function () use ($seed) {
            expect(db_primary_key('loans'))->toEqual(['book_id', 'member_id', 'borrowed_on']);
            expect(db_nullable('loans', 'due_on'))->toEqual(false);
            expect(db_nullable('loans', 'returned_on'))->toEqual(true);
            expect_rejected(array_merge($seed, ["INSERT INTO loans (book_id, member_id, borrowed_on, due_on) VALUES (1, 1, '2024-03-01', '2024-03-22')"]), 'the same loan on the same day already exists');
            expect_accepted(array_merge($seed, ["INSERT INTO loans (book_id, member_id, borrowed_on, due_on) VALUES (1, 1, '2024-04-01', '2024-04-22')"]));
        }, [
            'Three columns in the key: PRIMARY KEY (book_id, member_id, borrowed_on).',
        ]);
        it('book_id and member_id are foreign keys that cascade on delete', function () use ($seed) {
            $book = db_foreign_key('loans', ['book_id']);
            $member = db_foreign_key('loans', ['member_id']);
            expect($book['ref_table'])->toEqual('books');
            expect($book['on_delete'])->toEqual('CASCADE');
            expect($member['ref_table'])->toEqual('members');
            expect($member['on_delete'])->toEqual('CASCADE');
            expect_rejected(array_merge($seed, ["INSERT INTO loans (book_id, member_id, borrowed_on, due_on) VALUES (1, 999, '2024-05-01', '2024-05-22')"]), 'member 999 does not exist');
            db_probe_query(array_merge($seed, ["DELETE FROM members WHERE id = 1"]), "SELECT COUNT(*) AS n FROM loans",
                fn($rows) => expect((int) $rows[0]['n'])->toEqual(0));
        }, [
            'Two FOREIGN KEY lines, each with its own ON DELETE CASCADE.',
        ]);
        it('has idx_loans_due_on for the overdue report', function () {
            expect(db_index_columns('loans', 'idx_loans_due_on'))->toEqual(['due_on']);
        });
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
