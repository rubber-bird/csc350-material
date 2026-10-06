<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('11 - Conditional values, subqueries and text search', function () {
    $errors = db_run_exercise('11-conditions-subqueries-search.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `v_contacts` falls back from email to phone to a fixed text', function () {
            expect_view('v_contacts', ['student', 'contact'], [
                ['student' => 'Ada Lovelace',   'contact' => 'ada@college.edu'],
                ['student' => 'Alan Turing',    'contact' => '555-0102'],
                ['student' => 'Emmy Noether',   'contact' => 'emmy@college.edu'],
                ['student' => 'Grace Hopper',   'contact' => 'grace@college.edu'],
                ['student' => 'Linus Torvalds', 'contact' => 'no contact'],
                ['student' => 'Mary Beard',     'contact' => 'mary@college.edu'],
            ]);
        });
        it('Task 2: `v_results` labels each grade pass or fail with IF', function () {
            expect_view('v_results', ['student', 'code', 'result'], [
                ['student' => 'Ada Lovelace', 'code' => 'CSC101', 'result' => 'pass'],
                ['student' => 'Ada Lovelace', 'code' => 'CSC350', 'result' => 'pass'],
                ['student' => 'Alan Turing',  'code' => 'CSC101', 'result' => 'pass'],
                ['student' => 'Alan Turing',  'code' => 'CSC360', 'result' => 'pass'],
                ['student' => 'Emmy Noether', 'code' => 'CSC350', 'result' => 'pass'],
                ['student' => 'Emmy Noether', 'code' => 'MAT201', 'result' => 'pass'],
                ['student' => 'Grace Hopper', 'code' => 'MAT201', 'result' => 'pass'],
                ['student' => 'Grace Hopper', 'code' => 'MAT301', 'result' => 'fail'],
                ['student' => 'Mary Beard',   'code' => 'HIS101', 'result' => 'pass'],
            ]);
        });
        it('Task 3: `v_letter_grades` turns numbers into letters with CASE', function () {
            expect_view('v_letter_grades', ['student', 'code', 'letter'], [
                ['student' => 'Ada Lovelace', 'code' => 'CSC101', 'letter' => 'A'],
                ['student' => 'Ada Lovelace', 'code' => 'CSC350', 'letter' => 'B'],
                ['student' => 'Alan Turing',  'code' => 'CSC101', 'letter' => 'C'],
                ['student' => 'Alan Turing',  'code' => 'CSC360', 'letter' => 'A'],
                ['student' => 'Emmy Noether', 'code' => 'CSC350', 'letter' => 'C'],
                ['student' => 'Emmy Noether', 'code' => 'MAT201', 'letter' => 'A'],
                ['student' => 'Grace Hopper', 'code' => 'MAT201', 'letter' => 'B'],
                ['student' => 'Grace Hopper', 'code' => 'MAT301', 'letter' => 'F'],
                ['student' => 'Mary Beard',   'code' => 'HIS101', 'letter' => 'D'],
            ]);
        });
    });

    describe('medium', function () {
        it('Task 4: `v_above_average` compares against a subquery', function () {
            expect_view('v_above_average', ['student', 'code', 'grade'], [
                ['student' => 'Emmy Noether', 'code' => 'MAT201', 'grade' => '100.0'],
                ['student' => 'Ada Lovelace', 'code' => 'CSC101', 'grade' => '95.0'],
                ['student' => 'Alan Turing',  'code' => 'CSC360', 'grade' => '91.0'],
                ['student' => 'Ada Lovelace', 'code' => 'CSC350', 'grade' => '88.5'],
                ['student' => 'Grace Hopper', 'code' => 'MAT201', 'grade' => '85.0'],
            ]);
        }, [
            'The average of all grades is one value, so a subquery in parentheses can stand where a number would: WHERE e.grade > (SELECT AVG(grade) FROM enrollments).',
        ]);
        it('Task 5: `v_idle_students` uses NOT IN with a subquery', function () {
            expect_view('v_idle_students', ['student'], [
                ['student' => 'Linus Torvalds'],
            ]);
        }, [
            'WHERE student_id NOT IN (SELECT student_id FROM enrollments)',
        ]);
        it('Task 6: `v_people` stacks students and staff with UNION', function () {
            expect_view('v_people', ['name', 'role'], [
                ['name' => 'Ada Lovelace',   'role' => 'student'],
                ['name' => 'Alan Turing',    'role' => 'student'],
                ['name' => 'Barbara Liskov', 'role' => 'staff'],
                ['name' => 'Donald Knuth',   'role' => 'staff'],
                ['name' => 'Emmy Noether',   'role' => 'student'],
                ['name' => 'Grace Hopper',   'role' => 'student'],
                ['name' => 'Howard Zinn',    'role' => 'staff'],
                ['name' => 'Linus Torvalds', 'role' => 'student'],
                ['name' => 'Mary Beard',     'role' => 'student'],
            ]);
        }, [
            'Two SELECTs with the same two columns, joined by UNION. A fixed text like \'student\' can be selected as a column. The column names come from the first SELECT, and one ORDER BY at the very end sorts the combined list.',
        ]);
    });

    describe('hard', function () {
        it('Task 7: courses has a FULLTEXT index on (title, description)', function () {
            $found = false;
            foreach (db_indexes('courses') as $idx) {
                if ($idx['type'] === 'FULLTEXT' && $idx['columns'] === ['title', 'description']) $found = true;
            }
            if (!$found) {
                $have = array_map(fn($i) => $i['type'] . ' (' . implode(', ', $i['columns']) . ')', db_indexes('courses'));
                throw new Exception("No FULLTEXT index on courses (title, description).\nIndexes on courses: " . implode(', ', $have));
            }
        }, [
            'ALTER TABLE courses ADD FULLTEXT (title, description);',
        ]);
        it('Task 7: `v_search_database` finds the word "database" in two courses', function () {
            expect_view('v_search_database', ['code', 'title'], [
                ['code' => 'CSC350', 'title' => 'Software Development'],
                ['code' => 'CSC360', 'title' => 'Database Systems'],
            ]);
        }, [
            'WHERE MATCH (title, description) AGAINST (\'database\'). The MATCH columns must be the same two columns the index was built on, in the same order.',
        ]);
        it('Task 8: `v_search_web_not_php` uses + and - in boolean mode', function () {
            expect_view('v_search_web_not_php', ['code', 'title'], [
                ['code' => 'HIS101', 'title' => 'World History'],
            ]);
        }, [
            'WHERE MATCH (title, description) AGAINST (\'+web -php\' IN BOOLEAN MODE). CSC350 mentions both words, so the -php drops it.',
        ]);
        it('Task 9: `v_department_summary` counts students and courses without double counting', function () {
            expect_view('v_department_summary', ['department', 'students', 'courses'], [
                ['department' => 'Computer Science', 'students' => '3', 'courses' => '4'],
                ['department' => 'History',          'students' => '1', 'courses' => '1'],
                ['department' => 'Mathematics',      'students' => '2', 'courses' => '2'],
                ['department' => 'Music',            'students' => '0', 'courses' => '0'],
            ]);
        }, [
            'LEFT JOIN students and LEFT JOIN courses from departments. Computer Science then has 3 x 4 = 12 joined rows, so COUNT(*) is wrong for both numbers.',
            'COUNT(DISTINCT s.student_id) and COUNT(DISTINCT c.course_id) count each student and each course once, and give 0 for Music because DISTINCT ignores NULL.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
