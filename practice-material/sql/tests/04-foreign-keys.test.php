<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('04 - Foreign keys', function () {
    $errors = db_run_exercise('04-foreign-keys.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `books.author_id` references authors(id)', function () {
            expect(db_primary_key('books'))->toEqual(['id']);
            expect(db_nullable('books', 'author_id'))->toEqual(false);
            $fk = db_foreign_key('books', ['author_id']);
            expect($fk['ref_table'])->toEqual('authors');
            expect($fk['ref_columns'])->toEqual(['id']);
        });
        it('Task 1: a book with an unknown author is rejected, and an author with books cannot be deleted', function () {
            expect_accepted("INSERT INTO books (id, title, author_id) VALUES (1, 'The Dispossessed', 1)");
            expect_rejected("INSERT INTO books (id, title, author_id) VALUES (2, 'Nobody wrote this', 999)", 'author 999 does not exist');
            expect_rejected(["INSERT INTO books (id, title, author_id) VALUES (1, 'The Dispossessed', 1)", "DELETE FROM authors WHERE id = 1"], 'author 1 still has a book (ON DELETE RESTRICT is the default)');
        });
        it('Task 2: deleting a book deletes its reviews (ON DELETE CASCADE)', function () {
            $fk = db_foreign_key('reviews', ['book_id']);
            expect($fk['ref_table'])->toEqual('books');
            expect($fk['on_delete'])->toEqual('CASCADE');
            db_probe_query([
                "INSERT INTO books (id, title, author_id) VALUES (1, 'Kindred', 2)",
                "INSERT INTO reviews (book_id, stars) VALUES (1, 5), (1, 4)",
                "DELETE FROM books WHERE id = 1",
            ], "SELECT COUNT(*) AS n FROM reviews", function ($rows) {
                expect((int) $rows[0]['n'])->toEqual(0);
            });
        });
        it('Task 3: deleting a customer sets profiles.customer_id to NULL (ON DELETE SET NULL)', function () {
            expect(db_nullable('profiles', 'customer_id'))->toEqual(true);
            $fk = db_foreign_key('profiles', ['customer_id']);
            expect($fk['ref_table'])->toEqual('customers');
            expect($fk['on_delete'])->toEqual('SET NULL');
            db_probe_query([
                "INSERT INTO profiles (customer_id, nickname) VALUES (1, 'ada42')",
                "DELETE FROM customers WHERE id = 1",
            ], "SELECT customer_id, nickname FROM profiles", function ($rows) {
                expect($rows)->toEqual([['customer_id' => null, 'nickname' => 'ada42']]);
            });
        });
    });

    describe('medium', function () {
        it('Task 4: the foreign key on `orders.customer_id` is named fk_orders_customer', function () {
            $fk = db_foreign_key('orders', ['customer_id']);
            expect($fk['name'])->toEqual('fk_orders_customer');
            expect($fk['ref_table'])->toEqual('customers');
            expect_rejected("INSERT INTO orders (customer_id, total) VALUES (999, 10.00)", 'customer 999 does not exist');
        });
        it('Task 5: updating a country code updates its cities (ON UPDATE CASCADE)', function () {
            $fk = db_foreign_key('cities', ['country_code']);
            expect($fk['ref_table'])->toEqual('countries');
            expect($fk['on_update'])->toEqual('CASCADE');
            db_probe_query([
                "INSERT INTO cities (name, country_code) VALUES ('Paris', 'FR')",
                "UPDATE countries SET code = 'FX' WHERE code = 'FR'",
            ], "SELECT country_code FROM cities WHERE name = 'Paris'", function ($rows) {
                expect($rows[0]['country_code'])->toEqual('FX');
            });
        });
        it('Task 6: `payments` gained fk_payments_order via ALTER TABLE', function () {
            $fk = db_foreign_key('payments', ['order_id']);
            expect($fk['name'])->toEqual('fk_payments_order');
            expect($fk['ref_table'])->toEqual('orders');
            expect($fk['ref_columns'])->toEqual(['id']);
            expect_rejected("INSERT INTO payments (order_id, amount) VALUES (999, 5.00)", 'order 999 does not exist');
        });
    });

    describe('hard', function () {
        it('Task 7: `employees.manager_id` references employees(id) and is cleared when the manager leaves', function () {
            expect(db_nullable('employees', 'manager_id'))->toEqual(true);
            $fk = db_foreign_key('employees', ['manager_id']);
            expect($fk['ref_table'])->toEqual('employees');
            expect($fk['on_delete'])->toEqual('SET NULL');
            expect_rejected("INSERT INTO employees (name, manager_id) VALUES ('Lost', 999)", 'there is no employee 999');
            db_probe_query([
                "INSERT INTO employees (id, name, manager_id) VALUES (1, 'Boss', NULL)",
                "INSERT INTO employees (id, name, manager_id) VALUES (2, 'Worker', 1)",
                "DELETE FROM employees WHERE id = 1",
            ], "SELECT name, manager_id FROM employees", function ($rows) {
                expect($rows)->toEqual([['name' => 'Worker', 'manager_id' => null]]);
            });
        }, [
            'A foreign key may reference the table it lives in. Write it exactly like any other: FOREIGN KEY (manager_id) REFERENCES employees (id).',
            'The column must allow NULL (the boss has no manager) and the rule is ON DELETE SET NULL.',
        ]);
        it('Task 8: `grades` references the composite key of enrollments', function () {
            expect(db_primary_key('grades'))->toEqual(['student_id', 'course_id']);
            $fk = db_foreign_key('grades', ['student_id', 'course_id']);
            expect($fk['ref_table'])->toEqual('enrollments');
            expect($fk['ref_columns'])->toEqual(['student_id', 'course_id']);
            expect($fk['on_delete'])->toEqual('CASCADE');
            expect_accepted("INSERT INTO grades VALUES (1, 1, 'A')");
            expect_rejected("INSERT INTO grades VALUES (2, 1, 'B')", 'student 2 is not enrolled in course 1');
            db_probe_query(["INSERT INTO grades VALUES (1, 2, 'B+')", "DELETE FROM enrollments WHERE student_id = 1 AND course_id = 2"],
                "SELECT COUNT(*) AS n FROM grades", fn($rows) => expect((int) $rows[0]['n'])->toEqual(0));
        }, [
            'A foreign key can span several columns. Both sides list the columns in the same order: FOREIGN KEY (a, b) REFERENCES other (a, b).',
            'FOREIGN KEY (student_id, course_id) REFERENCES enrollments (student_id, course_id) ON DELETE CASCADE',
        ]);
        it('Task 9: `book_tags` joins books and tags, and follows deletions on both sides', function () {
            expect(db_primary_key('book_tags'))->toEqual(['book_id', 'tag_id']);
            $books = db_foreign_key('book_tags', ['book_id']);
            $tags = db_foreign_key('book_tags', ['tag_id']);
            expect($books['ref_table'])->toEqual('books');
            expect($books['on_delete'])->toEqual('CASCADE');
            expect($tags['ref_table'])->toEqual('tags');
            expect($tags['on_delete'])->toEqual('CASCADE');
            db_probe_query([
                "INSERT INTO books (id, title, author_id) VALUES (1, 'The Left Hand of Darkness', 1)",
                "INSERT INTO book_tags VALUES (1, 1), (1, 2)",
                "DELETE FROM tags WHERE id = 2",
            ], "SELECT tag_id FROM book_tags", fn($rows) => expect($rows)->toEqual([['tag_id' => 1]]));
        }, [
            'A join table has two foreign keys and a composite primary key over the same two columns.',
            'Each FOREIGN KEY line gets its own ON DELETE CASCADE.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
