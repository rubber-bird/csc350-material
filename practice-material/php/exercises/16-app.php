<?php
// ============================================================
//  16 - A complete small application
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 16)
//
//  This module builds on 15. Finish 15 first: its Request, Response,
//  Router and Flash classes are loaded here and used throughout.
//
//  You are building what the term project is: registration, login, a
//  page only for logged-in users, logout, and a JSON API, on top of a
//  database, with CSRF protection and escaped output. The tests drive
//  it exactly like a browser would, request after request, sharing one
//  session array.
//
//  The app is one class. Its constructor receives the database
//  connection and the session ARRAY BY REFERENCE (in production that
//  is $_SESSION); handle() takes a Request and returns a Response.
//
//  Routes:
//    GET  /              200 html, contains links to /register and /login
//    GET  /register      200 html: a form with name, email, password,
//                        password_confirm and a hidden csrf field
//    POST /register      403 "Invalid form token" if csrf is wrong
//                        422 html with the error messages and the name
//                            and email filled back in (escaped) on error
//                        on success: create the user, flash
//                            "Welcome, <name>" as success, 302 to /login
//    GET  /login         200 html form with email, password, csrf;
//                        shows any flash messages as
//                            <div class="flash success">…</div>
//    POST /login         403 on bad csrf
//                        401 html containing "Invalid email or password"
//                            for a wrong email OR a wrong password
//                        on success: $session['user_id'] = id, 302 /dashboard
//    GET  /dashboard     302 to /login when not logged in
//                        200 html containing "Hello, <name>" (escaped)
//    POST /logout        403 on bad csrf; otherwise forget user_id, 302 /login
//    GET  /api/users     200 json: {"items":[{id,name,email},...],
//                        "page":N,"pages":N,"total":N}, ordered by name,
//                        ?page= (default 1) and ?per_page= (default 10, at most 50)
//    anything else       404
//
//  Validation for /register (one message per field, in this order):
//    name              2 to 50 characters after trim
//                      -> "Name must be 2 to 50 characters"
//    email             valid (filter_var), stored lower-case
//                      -> "Email is not valid"
//                      -> "Email already registered"   (case-insensitive)
//    password          at least 8 characters
//                      -> "Password must be at least 8 characters"
//    password_confirm  equal to password
//                      -> "Passwords do not match"
//
//  The users table (UserRepository::migrate creates it):
//    id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL,
//    email TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL,
//    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
//
//  Everything the user typed goes through htmlspecialchars() on the
//  way out. The tests register a user called <b>Bobby</b>.
//
// ============================================================

require_once __DIR__ . '/15-routing.php';

/**
 * UserRepository: every SQL statement about users lives here, and
 * nowhere else. Prepared statements only.
 *
 *   migrate()                          create the table if needed
 *   create($name, $email, $hash): int  the new id
 *   findByEmail($email): ?array        the whole row, or null
 *   findById($id): ?array
 *   page($page, $per_page): array      rows with id, name, email, ordered by name
 *   count(): int
 */
class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function migrate(): void
    {
        // your code here
    }

    public function create(string $name, string $email, string $password_hash): int
    {
        // your code here
    }

    public function findByEmail(string $email): ?array
    {
        // your code here
    }

    public function findById(int $id): ?array
    {
        // your code here
    }

    public function page(int $page, int $per_page): array
    {
        // your code here
    }

    public function count(): int
    {
        // your code here
    }
}

/**
 * App: the routes above, wired to a Router in the constructor.
 * Keep the pieces small: one private method per page, a private
 * csrfToken() / csrfOk() pair, a private validateRegistration(),
 * and a private e() that escapes. There are no tests for those
 * helpers, only for the behaviour, so shape them as you like.
 */
class App
{
    private array $session;
    private Router $router;
    private UserRepository $users;

    public function __construct(PDO $pdo, array &$session)
    {
        $this->session = &$session;
        $this->users = new UserRepository($pdo);
        $this->users->migrate();
        $this->router = new Router();
        // your code here: register the routes
    }

    public function handle(Request $request): Response
    {
        // your code here
    }

    // your code here: the private methods
}
