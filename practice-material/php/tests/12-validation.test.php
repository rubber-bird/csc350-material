<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/12-validation.php';

describe('12 - Validation, passwords and tokens', function () {
    describe('easy', function () {
        it('is_valid_email accepts well-formed addresses only', function () {
            expect(is_valid_email('ada@example.org'))->toEqual(true);
            expect(is_valid_email('a.b+c@sub.example.co.uk'))->toEqual(true);
            expect(is_valid_email('ada@'))->toEqual(false);
            expect(is_valid_email('ada@example'))->toEqual(false);
            expect(is_valid_email(''))->toEqual(false);
            expect(is_valid_email(' ada@example.org'))->toEqual(false);
        });
        it('clean trims, collapses whitespace, and turns null into ""', function () {
            expect(clean("  Ada   Lovelace \n"))->toEqual('Ada Lovelace');
            expect(clean(null))->toEqual('');
            expect(clean(42))->toEqual('42');
            expect(clean("a\t\tb"))->toEqual('a b');
        });
        it('require_fields lists missing or blank fields in order', function () {
            expect(require_fields(['name' => 'Ada', 'email' => ''], ['name', 'email', 'age']))->toEqual(['email', 'age']);
            expect(require_fields(['name' => '  '], ['name']))->toEqual(['name']);
            expect(require_fields(['name' => 'Ada'], ['name']))->toEqual([]);
            expect(require_fields([], []))->toEqual([]);
        });
    });

    describe('medium', function () {
        it('password_strength_errors lists the broken rules in order', function () {
            expect(password_strength_errors('Passw0rd'))->toEqual([]);
            expect(password_strength_errors('short'))->toEqual(['at least 8 characters', 'an upper-case letter', 'a digit']);
            expect(password_strength_errors(''))->toEqual(['at least 8 characters', 'a lower-case letter', 'an upper-case letter', 'a digit']);
            expect(password_strength_errors('ALLUPPER1'))->toEqual(['a lower-case letter']);
            expect(password_strength_errors('lowerlower'))->toEqual(['an upper-case letter', 'a digit']);
        });
        it('validate_registration: the happy path returns no errors and the cleaned values', function () {
            $r = validate_registration(['name' => ' Ada ', 'email' => 'ada@example.org', 'password' => 'Passw0rd', 'password_confirm' => 'Passw0rd', 'age' => '36']);
            expect($r)->toEqual(['errors' => [], 'values' => ['name' => 'Ada', 'email' => 'ada@example.org']]);
            expect(array_key_exists('password', $r['values']))->toEqual(false);
        });
        it('validate_registration: every rule and message', function () {
            expect(validate_registration([])['errors'])->toEqual([
                'name' => 'Name is required', 'email' => 'Email is required', 'password' => 'Password is required',
            ]);
            $base = ['name' => 'Ada', 'email' => 'ada@example.org', 'password' => 'Passw0rd', 'password_confirm' => 'Passw0rd'];
            expect(validate_registration(['name' => 'A'] + $base)['errors'])->toEqual(['name' => 'Name must be 2 to 50 characters']);
            expect(validate_registration(['name' => str_repeat('a', 51)] + $base)['errors'])->toEqual(['name' => 'Name must be 2 to 50 characters']);
            expect(validate_registration(['email' => 'nope'] + $base)['errors'])->toEqual(['email' => 'Email is not valid']);
            expect(validate_registration(['password' => 'short', 'password_confirm' => 'short'] + $base)['errors'])
                ->toEqual(['password' => 'Password needs at least 8 characters, an upper-case letter, a digit']);
            expect(validate_registration(['password_confirm' => 'Passw0rd!'] + $base)['errors'])->toEqual(['password_confirm' => 'Passwords do not match']);
            expect(validate_registration(['age' => '9'] + $base)['errors'])->toEqual(['age' => 'Age must be a number between 13 and 120']);
            expect(validate_registration(['age' => 'old'] + $base)['errors'])->toEqual(['age' => 'Age must be a number between 13 and 120']);
            expect(validate_registration(['age' => ''] + $base)['errors'])->toEqual([]);
        });
        it('hash_password / check_password use a salted one-way hash', function () {
            $h = hash_password('Passw0rd');
            expect($h !== 'Passw0rd')->toEqual(true);
            expect(strlen($h) >= 60)->toEqual(true);
            expect(check_password('Passw0rd', $h))->toEqual(true);
            expect(check_password('passw0rd', $h))->toEqual(false);
            expect(hash_password('x') !== hash_password('x'))->toEqual(true);
            expect(password_get_info($h)['algo'] !== null)->toEqual(true);
        });
    });

    describe('hard', function () {
        it('make_token gives 2 * bytes hex characters, different every time', function () {
            expect(strlen(make_token()))->toEqual(64);
            expect(strlen(make_token(8)))->toEqual(16);
            expect(preg_match('/^[0-9a-f]+$/', make_token()))->toEqual(1);
            expect(make_token() !== make_token())->toEqual(true);
        }, ['bin2hex(random_bytes($bytes))']);
        it('csrf_token stores one token per session; csrf_check compares safely', function () {
            $s = [];
            $t = csrf_token($s);
            expect(strlen($t))->toEqual(64);
            expect($s['csrf'])->toEqual($t);
            expect(csrf_token($s))->toEqual($t);
            expect(csrf_check($s, $t))->toEqual(true);
            expect(csrf_check($s, 'nope'))->toEqual(false);
            expect(csrf_check($s, null))->toEqual(false);
            expect(csrf_check($s, ''))->toEqual(false);
            $empty = [];
            expect(csrf_check($empty, $t))->toEqual(false);
            $other = [];
            expect(csrf_token($other) !== $t)->toEqual(true);
        }, [
            'csrf_token: if (empty($session["csrf"])) $session["csrf"] = make_token(); return it.',
            'csrf_check: guard against a missing token or a non-string submission first, then hash_equals($session["csrf"], $submitted).',
        ]);
        it('LoginThrottle blocks after 5 failures in 15 minutes, per key', function () {
            $t = new LoginThrottle();
            $now = 1700000000;
            for ($i = 0; $i < 4; $i++) $t->record_failure('1.2.3.4', $now + $i);
            expect($t->failures('1.2.3.4', $now + 10))->toEqual(4);
            expect($t->is_blocked('1.2.3.4', $now + 10))->toEqual(false);
            $t->record_failure('1.2.3.4', $now + 4);
            expect($t->is_blocked('1.2.3.4', $now + 10))->toEqual(true);
            expect($t->is_blocked('5.6.7.8', $now + 10))->toEqual(false);
            expect($t->is_blocked('1.2.3.4', $now + 15 * 60))->toEqual(false);
            expect($t->failures('1.2.3.4', $now + 15 * 60 + 3))->toEqual(1);
            $t->record_success('1.2.3.4');
            expect($t->failures('1.2.3.4', $now + 10))->toEqual(0);
            expect($t->is_blocked('1.2.3.4', $now + 10))->toEqual(false);
        }, [
            'Keep a list of timestamps per key: $this->failures[$key][] = $now.',
            'failures() counts the timestamps newer than $now - WINDOW; is_blocked() is failures() >= MAX_FAILURES.',
        ]);
        it('validate applies a "rule|rule:arg" language and reports the first broken rule per field', function () {
            expect(validate(['email' => 'ada@example.org', 'age' => '36'], ['email' => 'required|email', 'age' => 'int|min:13|max:120']))->toEqual([]);
            expect(validate(['email' => 'nope', 'age' => '9'], ['email' => 'required|email', 'age' => 'int|min:13']))
                ->toEqual(['email' => 'email failed email', 'age' => 'age failed min:13']);
            expect(validate([], ['name' => 'required|min:2']))->toEqual(['name' => 'name failed required']);
            expect(validate(['plan' => 'gold'], ['plan' => 'in:free,pro']))->toEqual(['plan' => 'plan failed in:free,pro']);
            expect(validate(['plan' => 'pro'], ['plan' => 'in:free,pro']))->toEqual([]);
            expect(validate(['nick' => ''], ['nick' => 'min:2']))->toEqual([]);
            expect(validate(['nick' => 'x'], ['nick' => 'min:2']))->toEqual(['nick' => 'nick failed min:2']);
            expect(validate(['nick' => 'toolongname'], ['nick' => 'max:5']))->toEqual(['nick' => 'nick failed max:5']);
            expect(validate(['age' => 'abc'], ['age' => 'int|min:1']))->toEqual(['age' => 'age failed int']);
            expect(validate(['age' => '500'], ['age' => 'int|max:120']))->toEqual(['age' => 'age failed max:120']);
        }, [
            'Split the spec on "|", then each rule on ":" (at most 2 parts). Loop; stop at the first rule that fails.',
            'Skip every rule except required when the value is blank. min/max mean numeric comparison when the field also has the int rule, string length otherwise.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
