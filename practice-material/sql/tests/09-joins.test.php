<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('09 - Joins', function () {
    $errors = db_run_exercise('09-joins.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('easy', function () {
        it('Task 1: `v_catalog` lists every course with its department name', function () {
            expect_view('v_catalog', ['code', 'title', 'department'], [
                ['code' => 'CSC101', 'title' => 'Intro to Programming', 'department' => 'Computer Science'],
                ['code' => 'CSC350', 'title' => 'Software Development', 'department' => 'Computer Science'],
                ['code' => 'CSC360', 'title' => 'Database Systems',     'department' => 'Computer Science'],
                ['code' => 'CSC499', 'title' => 'Senior Project',       'department' => 'Computer Science'],
                ['code' => 'HIS101', 'title' => 'World History',        'department' => 'History'],
                ['code' => 'MAT201', 'title' => 'Linear Algebra',       'department' => 'Mathematics'],
                ['code' => 'MAT301', 'title' => 'Statistics',           'department' => 'Mathematics'],
            ]);
        });
        it('Task 2: `v_roster` joins enrollments to students and courses', function () {
            expect_view('v_roster', ['code', 'student'], [
                ['code' => 'CSC101', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC101', 'student' => 'Alan Turing'],
                ['code' => 'CSC350', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC350', 'student' => 'Emmy Noether'],
                ['code' => 'CSC360', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC360', 'student' => 'Alan Turing'],
                ['code' => 'HIS101', 'student' => 'Mary Beard'],
                ['code' => 'MAT201', 'student' => 'Emmy Noether'],
                ['code' => 'MAT201', 'student' => 'Grace Hopper'],
                ['code' => 'MAT301', 'student' => 'Grace Hopper'],
            ]);
        });
        it('Task 3: `v_majors` names each student\'s department', function () {
            expect_view('v_majors', ['student', 'major'], [
                ['student' => 'Ada Lovelace',   'major' => 'Computer Science'],
                ['student' => 'Alan Turing',    'major' => 'Computer Science'],
                ['student' => 'Emmy Noether',   'major' => 'Mathematics'],
                ['student' => 'Grace Hopper',   'major' => 'Mathematics'],
                ['student' => 'Linus Torvalds', 'major' => 'Computer Science'],
                ['student' => 'Mary Beard',     'major' => 'History'],
            ]);
        });
    });

    describe('medium', function () {
        it('Task 4: `v_never_enrolled` finds the student with no enrollments', function () {
            expect_view('v_never_enrolled', ['student'], [
                ['student' => 'Linus Torvalds'],
            ]);
        });
        it('Task 5: `v_course_rosters` keeps the empty course with a NULL student', function () {
            expect_view('v_course_rosters', ['code', 'student'], [
                ['code' => 'CSC101', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC101', 'student' => 'Alan Turing'],
                ['code' => 'CSC350', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC350', 'student' => 'Emmy Noether'],
                ['code' => 'CSC360', 'student' => 'Ada Lovelace'],
                ['code' => 'CSC360', 'student' => 'Alan Turing'],
                ['code' => 'CSC499', 'student' => null],
                ['code' => 'HIS101', 'student' => 'Mary Beard'],
                ['code' => 'MAT201', 'student' => 'Emmy Noether'],
                ['code' => 'MAT201', 'student' => 'Grace Hopper'],
                ['code' => 'MAT301', 'student' => 'Grace Hopper'],
            ]);
        });
        it('Task 6: `v_prerequisites` joins courses to themselves', function () {
            expect_view('v_prerequisites', ['code', 'prerequisite'], [
                ['code' => 'CSC101', 'prerequisite' => null],
                ['code' => 'CSC350', 'prerequisite' => 'CSC101'],
                ['code' => 'CSC360', 'prerequisite' => 'CSC101'],
                ['code' => 'CSC499', 'prerequisite' => 'CSC350'],
                ['code' => 'HIS101', 'prerequisite' => null],
                ['code' => 'MAT201', 'prerequisite' => null],
                ['code' => 'MAT301', 'prerequisite' => 'MAT201'],
            ]);
        });
    });

    describe('hard', function () {
        it('Task 7: `v_transcript` joins four tables and keeps only graded rows', function () {
            expect_view('v_transcript', ['student', 'code', 'department', 'grade'], [
                ['student' => 'Ada Lovelace', 'code' => 'CSC101', 'department' => 'Computer Science', 'grade' => '95.0'],
                ['student' => 'Ada Lovelace', 'code' => 'CSC350', 'department' => 'Computer Science', 'grade' => '88.5'],
                ['student' => 'Alan Turing',  'code' => 'CSC101', 'department' => 'Computer Science', 'grade' => '72.0'],
                ['student' => 'Alan Turing',  'code' => 'CSC360', 'department' => 'Computer Science', 'grade' => '91.0'],
                ['student' => 'Emmy Noether', 'code' => 'CSC350', 'department' => 'Computer Science', 'grade' => '79.0'],
                ['student' => 'Emmy Noether', 'code' => 'MAT201', 'department' => 'Mathematics',      'grade' => '100.0'],
                ['student' => 'Grace Hopper', 'code' => 'MAT201', 'department' => 'Mathematics',      'grade' => '85.0'],
                ['student' => 'Grace Hopper', 'code' => 'MAT301', 'department' => 'Mathematics',      'grade' => '58.0'],
                ['student' => 'Mary Beard',   'code' => 'HIS101', 'department' => 'History',          'grade' => '66.0'],
            ]);
        }, [
            'Start from enrollments and join students, courses and departments one at a time. The department comes from the course, so join departments ON courses.department_id.',
            'FROM enrollments e JOIN students s ON e.student_id = s.student_id JOIN courses c ON e.course_id = c.course_id JOIN departments d ON c.department_id = d.department_id WHERE e.grade IS NOT NULL',
        ]);
        it('Task 8: `v_classmates` pairs up students who share a course', function () {
            expect_view('v_classmates', ['code', 'student_a', 'student_b'], [
                ['code' => 'CSC101', 'student_a' => 'Ada Lovelace', 'student_b' => 'Alan Turing'],
                ['code' => 'CSC350', 'student_a' => 'Ada Lovelace', 'student_b' => 'Emmy Noether'],
                ['code' => 'CSC360', 'student_a' => 'Ada Lovelace', 'student_b' => 'Alan Turing'],
                ['code' => 'MAT201', 'student_a' => 'Grace Hopper', 'student_b' => 'Emmy Noether'],
            ]);
        }, [
            'Join enrollments to a second copy of enrollments on the same course_id. Without a second condition every student pairs with themselves and every pair appears twice.',
            'Add e1.student_id < e2.student_id to the ON clause. Then join students twice, once for e1 and once for e2, under different aliases.',
            'FROM enrollments e1 JOIN enrollments e2 ON e1.course_id = e2.course_id AND e1.student_id < e2.student_id JOIN courses c ON e1.course_id = c.course_id JOIN students a ON e1.student_id = a.student_id JOIN students b ON e2.student_id = b.student_id',
        ]);
        it('Task 9: `v_not_taken_by_ada` puts the student condition in the ON clause', function () {
            expect_view('v_not_taken_by_ada', ['code'], [
                ['code' => 'CSC499'],
                ['code' => 'HIS101'],
                ['code' => 'MAT201'],
                ['code' => 'MAT301'],
            ]);
        }, [
            'LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.student_id = 1 matches only Ada\'s enrollments, so the unmatched courses are the ones she has not taken.',
            'Then WHERE e.student_id IS NULL keeps those unmatched rows. Putting e.student_id = 1 in the WHERE instead would drop them, because for them e.student_id is NULL.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
