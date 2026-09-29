<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/16-app.php';

// Drives an App like a browser: one session, one database, request after request.
class AppHarness
{
    public array $session = [];
    public PDO $pdo;
    public App $app;

    public function __construct()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->app = new App($this->pdo, $this->session);
    }

    public function get(string $uri): Response
    {
        return $this->app->handle(new Request('GET', $uri));
    }

    public function post(string $uri, array $data, bool $with_csrf = true): Response
    {
        if ($with_csrf) $data['csrf'] = $this->csrf();
        return $this->app->handle(new Request('POST', $uri, $data));
    }

    // Read the token the way a browser would: from the form.
    public function csrf(): string
    {
        $html = $this->get('/register')->body();
        preg_match('/name="csrf" value="([^"]+)"/', $html, $m);
        return $m[1] ?? '';
    }

    public function register(string $name, string $email, string $password = 'Passw0rd!'): Response
    {
        return $this->post('/register', ['name' => $name, 'email' => $email, 'password' => $password, 'password_confirm' => $password]);
    }
}

describe('16 - A complete small application', function () {
    describe('the repository', function () {
        it('UserRepository: migrate, create, find, page, count', function () {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $repo = new UserRepository($pdo);
            $repo->migrate();
            $repo->migrate();
            expect($repo->count())->toEqual(0);
            expect($repo->create('Grace', 'grace@example.org', 'h1'))->toEqual(1);
            expect($repo->create("O'Brien", 'ob@example.org', 'h2'))->toEqual(2);
            expect($repo->create('Ada', 'ada@example.org', 'h3'))->toEqual(3);
            expect($repo->findByEmail('ada@example.org')['name'])->toEqual('Ada');
            expect($repo->findByEmail('nobody@example.org'))->toEqual(null);
            expect($repo->findById(2)['email'])->toEqual('ob@example.org');
            expect($repo->findById(99))->toEqual(null);
            expect($repo->page(1, 2))->toEqual([['id' => 3, 'name' => 'Ada', 'email' => 'ada@example.org'], ['id' => 1, 'name' => 'Grace', 'email' => 'grace@example.org']]);
            expect(array_column($repo->page(2, 2), 'name'))->toEqual(["O'Brien"]);
            expect($repo->count())->toEqual(3);
            $failed = false;
            try { $repo->create('Dup', 'ada@example.org', 'h'); } catch (PDOException $e) { $failed = true; }
            expect($failed)->toEqual(true);
        });
    });

    describe('pages', function () {
        it('GET / and GET /register render, and unknown paths are 404', function () {
            $h = new AppHarness();
            $home = $h->get('/');
            expect($home->status())->toEqual(200);
            expect(str_contains($home->body(), 'href="/register"'))->toEqual(true);
            expect(str_contains($home->body(), 'href="/login"'))->toEqual(true);
            $form = $h->get('/register');
            expect($form->status())->toEqual(200);
            expect($form->header('Content-Type'))->toEqual('text/html; charset=utf-8');
            expect(preg_match('/<form[^>]*method="post"[^>]*action="\/register"/', $form->body()))->toEqual(1);
            foreach (['name', 'email', 'password', 'password_confirm', 'csrf'] as $field) {
                expect(str_contains($form->body(), "name=\"$field\""))->toEqual(true);
            }
            expect($h->get('/nope')->status())->toEqual(404);
        });
        it('the csrf token is stored in the session, is stable, and differs between sessions', function () {
            $h = new AppHarness();
            $t = $h->csrf();
            expect(strlen($t) >= 32)->toEqual(true);
            expect($h->session['csrf'])->toEqual($t);
            expect($h->csrf())->toEqual($t);
            expect((new AppHarness())->csrf() !== $t)->toEqual(true);
        });
    });

    describe('registration', function () {
        it('rejects a missing or wrong csrf token with 403', function () {
            $h = new AppHarness();
            $r = $h->post('/register', ['name' => 'Ada', 'email' => 'ada@example.org', 'password' => 'Passw0rd!', 'password_confirm' => 'Passw0rd!'], false);
            expect($r->status())->toEqual(403);
            expect(str_contains($r->body(), 'Invalid form token'))->toEqual(true);
            $h->csrf();
            $r = $h->app->handle(new Request('POST', '/register', ['csrf' => 'wrong', 'name' => 'Ada']));
            expect($r->status())->toEqual(403);
            expect((new UserRepository($h->pdo))->count())->toEqual(0);
        });
        it('shows every validation message with 422 and fills the form back in, escaped', function () {
            $h = new AppHarness();
            $r = $h->post('/register', ['name' => 'A', 'email' => 'nope', 'password' => 'short', 'password_confirm' => 'short']);
            expect($r->status())->toEqual(422);
            foreach (['Name must be 2 to 50 characters', 'Email is not valid', 'Password must be at least 8 characters'] as $msg) {
                expect(str_contains($r->body(), $msg))->toEqual(true);
            }
            $r = $h->post('/register', ['name' => '<b>Bobby</b>', 'email' => 'bobby@example.org', 'password' => 'Passw0rd!', 'password_confirm' => 'different']);
            expect($r->status())->toEqual(422);
            expect(str_contains($r->body(), 'Passwords do not match'))->toEqual(true);
            expect(str_contains($r->body(), 'value="&lt;b&gt;Bobby&lt;/b&gt;"'))->toEqual(true);
            expect(str_contains($r->body(), '<b>Bobby</b>'))->toEqual(false);
            expect(str_contains($r->body(), 'value="bobby@example.org"'))->toEqual(true);
            expect(str_contains($r->body(), 'Passw0rd!'))->toEqual(false);
            expect((new UserRepository($h->pdo))->count())->toEqual(0);
        });
        it('creates the user, hashes the password, lower-cases the email, flashes, redirects to /login', function () {
            $h = new AppHarness();
            $r = $h->register(' Ada ', 'Ada@Example.org');
            expect($r->status())->toEqual(302);
            expect($r->header('Location'))->toEqual('/login');
            $user = (new UserRepository($h->pdo))->findByEmail('ada@example.org');
            expect($user['name'])->toEqual('Ada');
            expect(password_verify('Passw0rd!', $user['password_hash']))->toEqual(true);
            $login = $h->get('/login');
            expect(str_contains($login->body(), '<div class="flash success">Welcome, Ada</div>'))->toEqual(true);
            expect(str_contains($h->get('/login')->body(), 'Welcome, Ada'))->toEqual(false);
        });
        it('refuses an email that is already registered, whatever its case', function () {
            $h = new AppHarness();
            $h->register('Ada', 'ada@example.org');
            $r = $h->register('Ada Again', 'ADA@example.org');
            expect($r->status())->toEqual(422);
            expect(str_contains($r->body(), 'Email already registered'))->toEqual(true);
            expect((new UserRepository($h->pdo))->count())->toEqual(1);
        });
    });

    describe('login, dashboard, logout', function () {
        it('/dashboard redirects to /login when nobody is logged in', function () {
            $h = new AppHarness();
            $r = $h->get('/dashboard');
            expect($r->status())->toEqual(302);
            expect($r->header('Location'))->toEqual('/login');
        });
        it('login: 403 without csrf, 401 with the same message for wrong email or wrong password', function () {
            $h = new AppHarness();
            $h->register('Ada', 'ada@example.org');
            expect($h->post('/login', ['email' => 'ada@example.org', 'password' => 'Passw0rd!'], false)->status())->toEqual(403);
            $wrong_pw = $h->post('/login', ['email' => 'ada@example.org', 'password' => 'nope']);
            $wrong_email = $h->post('/login', ['email' => 'nobody@example.org', 'password' => 'Passw0rd!']);
            expect($wrong_pw->status())->toEqual(401);
            expect($wrong_email->status())->toEqual(401);
            expect(str_contains($wrong_pw->body(), 'Invalid email or password'))->toEqual(true);
            expect(str_contains($wrong_email->body(), 'Invalid email or password'))->toEqual(true);
            expect(isset($h->session['user_id']))->toEqual(false);
        });
        it('the full flow: register, log in, see the dashboard (escaped), log out', function () {
            $h = new AppHarness();
            $h->register('<b>Bobby</b>', 'bobby@example.org');
            $r = $h->post('/login', ['email' => 'BOBBY@example.org', 'password' => 'Passw0rd!']);
            expect($r->status())->toEqual(302);
            expect($r->header('Location'))->toEqual('/dashboard');
            expect($h->session['user_id'])->toEqual(1);
            $dash = $h->get('/dashboard');
            expect($dash->status())->toEqual(200);
            expect(str_contains($dash->body(), 'Hello, &lt;b&gt;Bobby&lt;/b&gt;'))->toEqual(true);
            expect(str_contains($dash->body(), '<b>Bobby</b>'))->toEqual(false);
            expect($h->post('/logout', [], false)->status())->toEqual(403);
            expect(isset($h->session['user_id']))->toEqual(true);
            $out = $h->post('/logout', []);
            expect($out->status())->toEqual(302);
            expect($out->header('Location'))->toEqual('/login');
            expect(isset($h->session['user_id']))->toEqual(false);
            expect($h->get('/dashboard')->status())->toEqual(302);
        }, [
            'Keep $session by reference: $this->session = &$session; in the constructor, and read/write $this->session everywhere.',
            'dashboard(): look up $this->session["user_id"] with findById(); redirect when it is missing.',
        ]);
    });

    describe('the API', function () {
        it('GET /api/users returns json pages ordered by name, never the password hash', function () {
            $h = new AppHarness();
            foreach (['Grace', 'Ada', 'Alan'] as $n) $h->register($n, strtolower($n) . '@example.org');
            $r = $h->get('/api/users?per_page=2');
            expect($r->status())->toEqual(200);
            expect($r->header('Content-Type'))->toEqual('application/json');
            $data = json_decode($r->body(), true);
            expect(array_column($data['items'], 'name'))->toEqual(['Ada', 'Alan']);
            expect(array_keys($data['items'][0]))->toEqual(['id', 'name', 'email']);
            expect([$data['page'], $data['pages'], $data['total']])->toEqual([1, 2, 3]);
            $page2 = json_decode($h->get('/api/users?per_page=2&page=2')->body(), true);
            expect(array_column($page2['items'], 'name'))->toEqual(['Grace']);
            expect(json_decode($h->get('/api/users')->body(), true)['pages'])->toEqual(1);
            expect(json_decode($h->get('/api/users?per_page=999')->body(), true)['total'])->toEqual(3);
            expect(str_contains($r->body(), 'password'))->toEqual(false);
            expect($h->get('/api/users?page=9')->status())->toEqual(200);
            expect(json_decode($h->get('/api/users?page=9')->body(), true)['items'])->toEqual([]);
        }, [
            'Clamp the inputs: $page = max(1, (int) $request->query("page", 1)); $per_page = min(50, max(1, (int) $request->query("per_page", 10))).',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
