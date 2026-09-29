<?php
// 12 - Validation, passwords and tokens: reference solution

function is_valid_email($value)
{
    return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function clean($value)
{
    $value = trim((string) ($value ?? ''));
    return preg_replace('/\s+/', ' ', $value);
}

function require_fields($data, $fields)
{
    $missing = [];
    foreach ($fields as $field) {
        if (clean($data[$field] ?? null) === '') $missing[] = $field;
    }
    return $missing;
}

function password_strength_errors($password)
{
    $errors = [];
    if (strlen($password) < 8) $errors[] = 'at least 8 characters';
    if (!preg_match('/[a-z]/', $password)) $errors[] = 'a lower-case letter';
    if (!preg_match('/[A-Z]/', $password)) $errors[] = 'an upper-case letter';
    if (!preg_match('/\d/', $password)) $errors[] = 'a digit';
    return $errors;
}

function validate_registration($post)
{
    $name = clean($post['name'] ?? null);
    $email = clean($post['email'] ?? null);
    $password = (string) ($post['password'] ?? '');
    $confirm = (string) ($post['password_confirm'] ?? '');
    $age = clean($post['age'] ?? null);
    $errors = [];

    if ($name === '') $errors['name'] = 'Name is required';
    elseif (mb_strlen($name) < 2 || mb_strlen($name) > 50) $errors['name'] = 'Name must be 2 to 50 characters';

    if ($email === '') $errors['email'] = 'Email is required';
    elseif (!is_valid_email($email)) $errors['email'] = 'Email is not valid';

    if ($password === '') {
        $errors['password'] = 'Password is required';
    } else {
        $strength = password_strength_errors($password);
        if ($strength) $errors['password'] = 'Password needs ' . implode(', ', $strength);
        elseif ($confirm !== $password) $errors['password_confirm'] = 'Passwords do not match';
    }

    if ($age !== '' && (!ctype_digit($age) || (int) $age < 13 || (int) $age > 120)) {
        $errors['age'] = 'Age must be a number between 13 and 120';
    }

    return ['errors' => $errors, 'values' => ['name' => $name, 'email' => $email]];
}

function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function check_password($password, $hash)
{
    return password_verify($password, $hash);
}

function make_token($bytes = 32)
{
    return bin2hex(random_bytes($bytes));
}

function csrf_token(&$session)
{
    if (empty($session['csrf'])) $session['csrf'] = make_token();
    return $session['csrf'];
}

function csrf_check(&$session, $submitted)
{
    if (empty($session['csrf']) || !is_string($submitted) || $submitted === '') return false;
    return hash_equals($session['csrf'], $submitted);
}

class LoginThrottle
{
    public const MAX_FAILURES = 5;
    public const WINDOW = 15 * 60;

    private array $failures = [];

    public function record_failure(string $key, int $now): void
    {
        $this->failures[$key][] = $now;
    }

    public function record_success(string $key): void
    {
        unset($this->failures[$key]);
    }

    private function recent(string $key, int $now): array
    {
        return array_values(array_filter($this->failures[$key] ?? [], fn($t) => $t > $now - self::WINDOW));
    }

    public function failures(string $key, int $now): int
    {
        return count($this->recent($key, $now));
    }

    public function is_blocked(string $key, int $now): bool
    {
        return $this->failures($key, $now) >= self::MAX_FAILURES;
    }
}

function validate($data, $rules)
{
    $errors = [];
    foreach ($rules as $field => $spec) {
        $value = clean($data[$field] ?? null);
        $list = explode('|', $spec);
        $is_int = in_array('int', $list, true);
        foreach ($list as $rule) {
            [$name, $arg] = array_pad(explode(':', $rule, 2), 2, null);
            if ($name !== 'required' && $value === '') continue;
            $ok = match ($name) {
                'required' => $value !== '',
                'email' => is_valid_email($value),
                'int' => ctype_digit($value),
                'min' => $is_int ? (int) $value >= (int) $arg : mb_strlen($value) >= (int) $arg,
                'max' => $is_int ? (int) $value <= (int) $arg : mb_strlen($value) <= (int) $arg,
                'in' => in_array($value, explode(',', $arg), true),
                default => true,
            };
            if (!$ok) { $errors[$field] = "$field failed $rule"; break; }
        }
    }
    return $errors;
}
