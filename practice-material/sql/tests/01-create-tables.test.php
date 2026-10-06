<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('01 - Creating tables', function () {
    $errors = db_run_exercise('01-create-tables.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `students` has id INT and name VARCHAR(100)', function () {
            expect(array_slice(db_column_names('students'), 0, 2))->toEqual(['id', 'name']);
            expect(db_type('students', 'id'))->toEqual('int');
            expect(db_type('students', 'name'))->toEqual('varchar(100)');
        });
        it('Task 2: `courses` has id INT, title VARCHAR(200), credits INT', function () {
            expect(db_column_names('courses'))->toEqual(['id', 'title', 'credits']);
            expect(db_type('courses', 'id'))->toEqual('int');
            expect(db_type('courses', 'title'))->toEqual('varchar(200)');
            expect(db_type('courses', 'credits'))->toEqual('int');
        });
        it('Task 3: `scratch` is gone', function () {
            expect(db_table_exists('scratch'))->toEqual(false);
        });
    });

    describe('medium', function () {
        it('Task 4: `students` gained an email VARCHAR(255) column', function () {
            expect(db_column_names('students'))->toEqual(['id', 'name', 'email']);
            expect(db_type('students', 'email'))->toEqual('varchar(255)');
        });
        it('Task 5: `employees` lost its fax_number column and kept the rest', function () {
            expect(db_column_names('employees'))->toEqual(['id', 'name', 'salary']);
        });
        it('Task 6: `rooms` is now called `classrooms`, same columns', function () {
            expect(db_table_exists('rooms'))->toEqual(false);
            expect(db_column_names('classrooms'))->toEqual(['id', 'building', 'capacity']);
        });
    });

    describe('hard', function () {
        it('Task 7: `students_archive` has the same columns and types as `students`', function () {
            $describe = fn($t) => array_map(fn($c) => $c . ' ' . db_type($t, $c), db_column_names($t));
            expect($describe('students_archive'))->toEqual($describe('students'));
        }, [
            'CREATE TABLE new_name LIKE existing_table; copies the structure, not the rows.',
            'CREATE TABLE students_archive LIKE students;',
        ]);
        it('Task 8: `course_titles` has only id and title, and the three rows', function () {
            expect(db_column_names('course_titles'))->toEqual(['id', 'title']);
            expect(db_count('course_titles'))->toEqual(3);
            expect(db_query('SELECT title FROM course_titles ORDER BY id'))->toEqual([
                ['title' => 'Databases'], ['title' => 'Networks'], ['title' => 'Algorithms'],
            ]);
        }, [
            'A CREATE TABLE can be followed by a SELECT instead of a column list. The new table gets the columns of the query and its rows.',
            'CREATE TABLE course_titles AS SELECT id, title FROM courses;',
        ]);
        it('Task 9: `tweets.body` is VARCHAR(280)', function () {
            expect(db_column_names('tweets'))->toEqual(['id', 'body']);
            expect(db_type('tweets', 'body'))->toEqual('varchar(280)');
        }, [
            'ALTER TABLE has a MODIFY clause that takes the column name and its complete new definition.',
            'ALTER TABLE tweets MODIFY body VARCHAR(280);',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
