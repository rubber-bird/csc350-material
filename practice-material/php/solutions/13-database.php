<?php
// 13 - Talking to a database with PDO: reference solution

function create_schema(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id            INTEGER PRIMARY KEY AUTOINCREMENT,
        name          TEXT NOT NULL,
        email         TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        points        INTEGER NOT NULL DEFAULT 0,
        created_at    TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )');
}

function insert_user(PDO $pdo, string $name, string $email, string $password_hash): int
{
    $st = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
    $st->execute([$name, $email, $password_hash]);
    return (int) $pdo->lastInsertId();
}

function find_user_by_email(PDO $pdo, string $email): ?array
{
    $st = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $st->execute([$email]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function count_users(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
}

function list_users(PDO $pdo, int $page, int $per_page): array
{
    $st = $pdo->prepare('SELECT id, name, email FROM users ORDER BY name, id LIMIT ? OFFSET ?');
    $st->bindValue(1, $per_page, PDO::PARAM_INT);
    $st->bindValue(2, ($page - 1) * $per_page, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

function rename_user(PDO $pdo, int $id, string $new_name): bool
{
    $st = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
    $st->execute([$new_name, $id]);
    return $st->rowCount() === 1;
}

function delete_user(PDO $pdo, int $id): bool
{
    $st = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $st->execute([$id]);
    return $st->rowCount() === 1;
}

function register(PDO $pdo, string $name, string $email, string $password): array
{
    $email = strtolower(trim($email));
    if (find_user_by_email($pdo, $email) !== null) {
        return ['ok' => false, 'error' => 'Email already registered'];
    }
    $id = insert_user($pdo, $name, $email, password_hash($password, PASSWORD_DEFAULT));
    return ['ok' => true, 'id' => $id];
}

function login(PDO $pdo, string $email, string $password): ?array
{
    $user = find_user_by_email($pdo, strtolower(trim($email)));
    if ($user === null || !password_verify($password, $user['password_hash'])) return null;
    unset($user['password_hash']);
    return $user;
}

function transfer_points(PDO $pdo, int $from_id, int $to_id, int $points): bool
{
    if ($points <= 0) return false;
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare('SELECT points FROM users WHERE id = ?');
        $st->execute([$from_id]);
        $balance = $st->fetchColumn();
        $st->execute([$to_id]);
        $receiver = $st->fetchColumn();
        if ($balance === false || $receiver === false || (int) $balance < $points) {
            $pdo->rollBack();
            return false;
        }
        $pdo->prepare('UPDATE users SET points = points - ? WHERE id = ?')->execute([$points, $from_id]);
        $pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?')->execute([$points, $to_id]);
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function search_users(PDO $pdo, string $term): array
{
    $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $term);
    $like = '%' . $escaped . '%';
    $st = $pdo->prepare("SELECT id, name, email FROM users
                         WHERE name LIKE ? ESCAPE '\\' OR email LIKE ? ESCAPE '\\'
                         ORDER BY name");
    $st->execute([$like, $like]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}
