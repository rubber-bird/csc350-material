<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('10 - Aggregates and GROUP BY', function () {
    $errors = db_run_exercise('10-aggregates.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `v_enrollment_stats` summarises the whole table in one row', function () {
            expect_view('v_enrollment_stats', ['enrollments', 'graded', 'average', 'lowest', 'highest'], [
                ['enrollments' => '10', 'graded' => '9', 'average' => '81.6', 'lowest' => '58.0', 'highest' => '100.0'],
            ]);
        });
        it('Task 2: `v_students_per_department` counts students in each department', function () {
            expect_view('v_students_per_department', ['department', 'students'], [
                ['department' => 'Computer Science', 'students' => '3'],
                ['department' => 'History',          'students' => '1'],
                ['department' => 'Mathematics',      'students' => '2'],
            ]);
        });
        it('Task 3: `v_course_averages` averages the grades per course', function () {
            expect_view('v_course_averages', ['code', 'average'], [
                ['code' => 'CSC101', 'average' => '83.5'],
                ['code' => 'CSC350', 'average' => '83.8'],
                ['code' => 'CSC360', 'average' => '91.0'],
                ['code' => 'HIS101', 'average' => '66.0'],
                ['code' => 'MAT201', 'average' => '92.5'],
                ['code' => 'MAT301', 'average' => '58.0'],
            ]);
        });
    });

    describe('medium', function () {
        it('Task 4: `v_popular_courses` keeps only groups with 2 or more students', function () {
            expect_view('v_popular_courses', ['code', 'students'], [
                ['code' => 'CSC101', 'students' => '2'],
                ['code' => 'CSC350', 'students' => '2'],
                ['code' => 'CSC360', 'students' => '2'],
                ['code' => 'MAT201', 'students' => '2'],
            ]);
        }, [
            'A condition on an aggregate cannot go in WHERE, because WHERE runs before the groups exist. Use HAVING after GROUP BY.',
        ]);
        it('Task 5: `v_department_headcount` shows Music with 0', function () {
            expect_view('v_department_headcount', ['department', 'students'], [
                ['department' => 'Computer Science', 'students' => '3'],
                ['department' => 'History',          'students' => '1'],
                ['department' => 'Mathematics',      'students' => '2'],
                ['department' => 'Music',            'students' => '0'],
            ]);
        }, [
            'Start FROM departments and LEFT JOIN students, so Music keeps a row even though nothing matches.',
            'COUNT(*) would give Music 1, because the unmatched row still exists. Count a column from students instead: COUNT(s.student_id) skips the NULLs.',
        ]);
        it('Task 6: `v_student_courses` lists each student\'s codes with GROUP_CONCAT', function () {
            expect_view('v_student_courses', ['student', 'courses'], [
                ['student' => 'Ada Lovelace', 'courses' => 'CSC101, CSC350, CSC360'],
                ['student' => 'Alan Turing',  'courses' => 'CSC101, CSC360'],
                ['student' => 'Emmy Noether', 'courses' => 'CSC350, MAT201'],
                ['student' => 'Grace Hopper', 'courses' => 'MAT201, MAT301'],
                ['student' => 'Mary Beard',   'courses' => 'HIS101'],
            ]);
        }, [
            'GROUP_CONCAT takes its own ORDER BY and SEPARATOR inside the parentheses: GROUP_CONCAT(c.code ORDER BY c.code SEPARATOR \', \').',
        ]);
    });

    describe('hard', function () {
        it('Task 7: `v_credit_load` sums credits and keeps students above 6', function () {
            expect_view('v_credit_load', ['student', 'credits'], [
                ['student' => 'Ada Lovelace', 'credits' => '10'],
                ['student' => 'Emmy Noether', 'credits' => '8'],
                ['student' => 'Grace Hopper', 'credits' => '7'],
            ]);
        }, [
            'Join students to enrollments to courses, group by the student, and SUM(c.credits).',
            'The filter is on the sum, so it belongs in HAVING: HAVING credits > 6. The alias works there.',
        ]);
        it('Task 8: `v_department_grades` groups by the course\'s department', function () {
            expect_view('v_department_grades', ['department', 'graded', 'average', 'lowest', 'highest'], [
                ['department' => 'Computer Science', 'graded' => '5', 'average' => '85.1', 'lowest' => '72.0', 'highest' => '95.0'],
                ['department' => 'History',          'graded' => '1', 'average' => '66.0', 'lowest' => '66.0', 'highest' => '66.0'],
                ['department' => 'Mathematics',      'graded' => '3', 'average' => '81.0', 'lowest' => '58.0', 'highest' => '100.0'],
            ]);
        }, [
            'Go departments -> courses -> enrollments. The student\'s own department is not involved.',
            'graded is COUNT(e.grade), not COUNT(*): the ungraded enrollment in CSC360 must not be counted. Music drops out with HAVING graded > 0.',
        ]);
        it('Task 9: `v_top_student` is the one row with the highest average', function () {
            expect_view('v_top_student', ['student', 'average'], [
                ['student' => 'Ada Lovelace', 'average' => '91.8'],
            ]);
        }, [
            'Group by student, compute the average, ORDER BY it descending and LIMIT 1.',
            'The ORDER BY and LIMIT go inside the view, after the GROUP BY.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
