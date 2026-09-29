<?php
// 16 - A complete small application: reference solution

require_once __DIR__ . '/15-routing.php';

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function migrate(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id            INTEGER PRIMARY KEY AUTOINCREMENT,
            name          TEXT NOT NULL,
            email         TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at    TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )');
    }

    public function create(string $name, string $email, string $password_hash): int
    {
        $st = $this->pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $st->execute([$name, $email, $password_hash]);
        return (int) $this->pdo->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $st = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function page(int $page, int $per_page): array
    {
        $st = $this->pdo->prepare('SELECT id, name, email FROM users ORDER BY name, id LIMIT ? OFFSET ?');
        $st->bindValue(1, $per_page, PDO::PARAM_INT);
        $st->bindValue(2, ($page - 1) * $per_page, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}

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
        $this->router->get('/', fn() => $this->home());
        $this->router->get('/register', fn() => $this->registerForm());
        $this->router->post('/register', fn(Request $r) => $this->register($r));
        $this->router->get('/login', fn() => $this->loginForm());
        $this->router->post('/login', fn(Request $r) => $this->login($r));
        $this->router->get('/dashboard', fn() => $this->dashboard());
        $this->router->post('/logout', fn(Request $r) => $this->logout($r));
        $this->router->get('/api/users', fn(Request $r) => $this->apiUsers($r));
    }

    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }

    // ---- helpers ----

    private function e(mixed $value): string
    {
        return htmlspecialchars((string) $value);
    }

    private function csrfToken(): string
    {
        if (empty($this->session['csrf'])) $this->session['csrf'] = bin2hex(random_bytes(32));
        return $this->session['csrf'];
    }

    private function csrfOk(Request $request): bool
    {
        $submitted = $request->input('csrf');
        return is_string($submitted) && $submitted !== '' && !empty($this->session['csrf'])
            && hash_equals($this->session['csrf'], $submitted);
    }

    private function layout(string $title, string $body): string
    {
        return "<!doctype html><html><head><meta charset=\"utf-8\"><title>{$this->e($title)}</title></head><body>{$body}</body></html>";
    }

    private function flashes(): string
    {
        $html = '';
        foreach (Flash::pull($this->session) as $f) {
            $html .= "<div class=\"flash {$this->e($f['type'])}\">{$this->e($f['message'])}</div>";
        }
        return $html;
    }

    private function currentUser(): ?array
    {
        $id = $this->session['user_id'] ?? null;
        return $id === null ? null : $this->users->findById((int) $id);
    }

    private function validateRegistration(array $in): array
    {
        $errors = [];
        $len = mb_strlen($in['name']);
        if ($len < 2 || $len > 50) $errors['name'] = 'Name must be 2 to 50 characters';
        if (filter_var($in['email'], FILTER_VALIDATE_EMAIL) === false) $errors['email'] = 'Email is not valid';
        elseif ($this->users->findByEmail($in['email']) !== null) $errors['email'] = 'Email already registered';
        if (strlen($in['password']) < 8) $errors['password'] = 'Password must be at least 8 characters';
        elseif ($in['password'] !== $in['password_confirm']) $errors['password_confirm'] = 'Passwords do not match';
        return $errors;
    }

    // ---- pages ----

    private function home(): Response
    {
        return Response::html($this->layout('Home', '<h1>Welcome</h1><p><a href="/register">Register</a> or <a href="/login">Log in</a></p>'));
    }

    private function registerForm(array $values = [], array $errors = [], int $status = 200): Response
    {
        $err = fn($f) => isset($errors[$f]) ? "<p class=\"error\">{$this->e($errors[$f])}</p>" : '';
        $body = '<h1>Register</h1><form method="post" action="/register">'
            . "<input type=\"hidden\" name=\"csrf\" value=\"{$this->e($this->csrfToken())}\">"
            . "<label>Name <input name=\"name\" value=\"{$this->e($values['name'] ?? '')}\"></label>{$err('name')}"
            . "<label>Email <input name=\"email\" value=\"{$this->e($values['email'] ?? '')}\"></label>{$err('email')}"
            . "<label>Password <input type=\"password\" name=\"password\"></label>{$err('password')}"
            . "<label>Confirm <input type=\"password\" name=\"password_confirm\"></label>{$err('password_confirm')}"
            . '<button>Register</button></form>';
        return Response::html($this->layout('Register', $body), $status);
    }

    private function register(Request $request): Response
    {
        if (!$this->csrfOk($request)) return Response::html($this->layout('Forbidden', '<p>Invalid form token</p>'), 403);
        $in = [
            'name' => trim((string) $request->input('name', '')),
            'email' => strtolower(trim((string) $request->input('email', ''))),
            'password' => (string) $request->input('password', ''),
            'password_confirm' => (string) $request->input('password_confirm', ''),
        ];
        $errors = $this->validateRegistration($in);
        if ($errors) return $this->registerForm($in, $errors, 422);
        $this->users->create($in['name'], $in['email'], password_hash($in['password'], PASSWORD_DEFAULT));
        Flash::add($this->session, 'success', "Welcome, {$in['name']}");
        return Response::redirect('/login');
    }

    private function loginForm(string $error = '', int $status = 200): Response
    {
        $body = $this->flashes() . '<h1>Log in</h1>'
            . ($error !== '' ? "<p class=\"error\">{$this->e($error)}</p>" : '')
            . '<form method="post" action="/login">'
            . "<input type=\"hidden\" name=\"csrf\" value=\"{$this->e($this->csrfToken())}\">"
            . '<label>Email <input name="email"></label>'
            . '<label>Password <input type="password" name="password"></label>'
            . '<button>Log in</button></form>';
        return Response::html($this->layout('Log in', $body), $status);
    }

    private function login(Request $request): Response
    {
        if (!$this->csrfOk($request)) return Response::html($this->layout('Forbidden', '<p>Invalid form token</p>'), 403);
        $email = strtolower(trim((string) $request->input('email', '')));
        $user = $this->users->findByEmail($email);
        if ($user === null || !password_verify((string) $request->input('password', ''), $user['password_hash'])) {
            return $this->loginForm('Invalid email or password', 401);
        }
        $this->session['user_id'] = (int) $user['id'];
        return Response::redirect('/dashboard');
    }

    private function dashboard(): Response
    {
        $user = $this->currentUser();
        if ($user === null) return Response::redirect('/login');
        $body = $this->flashes() . "<h1>Hello, {$this->e($user['name'])}</h1>"
            . '<form method="post" action="/logout">'
            . "<input type=\"hidden\" name=\"csrf\" value=\"{$this->e($this->csrfToken())}\"><button>Log out</button></form>";
        return Response::html($this->layout('Dashboard', $body));
    }

    private function logout(Request $request): Response
    {
        if (!$this->csrfOk($request)) return Response::html($this->layout('Forbidden', '<p>Invalid form token</p>'), 403);
        unset($this->session['user_id']);
        return Response::redirect('/login');
    }

    private function apiUsers(Request $request): Response
    {
        $page = max(1, (int) $request->query('page', 1));
        $per_page = min(50, max(1, (int) $request->query('per_page', 10)));
        $total = $this->users->count();
        return Response::json([
            'items' => $this->users->page($page, $per_page),
            'page' => $page,
            'pages' => (int) ceil($total / $per_page),
            'total' => $total,
        ]);
    }
}
