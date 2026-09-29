<?php
// 15 - Requests, routing and responses: reference solution

class Request
{
    private string $method;
    private string $path;
    private array $query = [];

    public function __construct(string $method, string $uri, private array $post = [], private array $headers = [], private array $cookies = [])
    {
        $this->method = strtoupper($method);
        $parts = parse_url($uri);
        $this->path = ($parts['path'] ?? '') === '' ? '/' : $parts['path'];
        parse_str($parts['query'] ?? '', $this->query);
    }

    public function method(): string { return $this->method; }
    public function path(): string { return $this->path; }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->query : ($this->query[$key] ?? $default);
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->post : ($this->post[$key] ?? $default);
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $k => $v) {
            if (strcasecmp($k, $name) === 0) return $v;
        }
        return null;
    }

    public function cookie(string $name): ?string
    {
        return $this->cookies[$name] ?? null;
    }

    public function isPost(): bool { return $this->method === 'POST'; }
}

class Response
{
    public function __construct(private string $body = '', private int $status = 200, private array $headers = []) {}

    public function status(): int { return $this->status; }
    public function body(): string { return $this->body; }
    public function headers(): array { return $this->headers; }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $k => $v) {
            if (strcasecmp($k, $name) === 0) return $v;
        }
        return null;
    }

    public function withHeader(string $name, string $value): Response
    {
        return new Response($this->body, $this->status, $this->headers + [$name => $value]);
    }

    public static function html(string $body, int $status = 200): Response
    {
        return new Response($body, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function text(string $body, int $status = 200): Response
    {
        return new Response($body, $status, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    public static function json(mixed $data, int $status = 200): Response
    {
        return new Response(json_encode($data), $status, ['Content-Type' => 'application/json']);
    }

    public static function redirect(string $to, int $status = 302): Response
    {
        return new Response('', $status, ['Location' => $to]);
    }
}

function url_with($url, $params)
{
    $parts = parse_url($url);
    parse_str($parts['query'] ?? '', $query);
    foreach ($params as $key => $value) {
        if ($value === null) unset($query[$key]);
        else $query[$key] = $value;
    }
    $base = $parts['path'] ?? '';
    return count($query) ? $base . '?' . http_build_query($query) : $base;
}

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $regex = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern) . '$#';
        $this->routes[] = ['method' => strtoupper($method), 'regex' => $regex, 'handler' => $handler];
    }

    public function get(string $pattern, callable $handler): void { $this->add('GET', $pattern, $handler); }
    public function post(string $pattern, callable $handler): void { $this->add('POST', $pattern, $handler); }

    public function dispatch(Request $request): Response
    {
        $path_matched = false;
        foreach ($this->routes as $route) {
            if (!preg_match($route['regex'], $request->path(), $m)) continue;
            $path_matched = true;
            if ($route['method'] !== $request->method()) continue;
            $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
            return ($route['handler'])($request, $params);
        }
        return $path_matched ? Response::text('Method Not Allowed', 405) : Response::text('Not Found', 404);
    }
}

class Flash
{
    public static function add(array &$session, string $type, string $message): void
    {
        $session['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function pull(array &$session): array
    {
        $messages = $session['flash'] ?? [];
        unset($session['flash']);
        return $messages;
    }
}

class Pipeline
{
    private array $middleware = [];

    public function pipe(callable $middleware): Pipeline
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function handle(Request $request, callable $core): Response
    {
        $chain = array_reduce(
            array_reverse($this->middleware),
            fn($next, $mw) => fn(Request $req) => $mw($req, $next),
            $core
        );
        return $chain($request);
    }
}
