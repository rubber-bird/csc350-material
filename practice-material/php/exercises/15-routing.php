<?php
// ============================================================
//  15 - Requests, routing and responses
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 15)
//
//  A web application is one function: it takes a request and returns
//  a response. Everything else (routing, sessions, controllers) is
//  plumbing around that. Here you build the plumbing as plain objects
//  that the tests can drive without a web server. Module 16 then uses
//  them to build a complete application.
//
//    $request  = new Request('GET', '/users/7?tab=posts');
//    $router   = new Router();
//    $router->get('/users/{id}', fn($req, $params) => Response::text("user $params[id]"));
//    $response = $router->dispatch($request);
//    $response->body()    // "user 7"
//
//  In real life Request::fromGlobals() would read $_SERVER, $_GET and
//  $_POST, and $response->send() would call header() and echo. The
//  tests never need those, so they are not part of this module.
//
//  Docs:
//    parse_url            https://www.php.net/parse_url
//    parse_str            https://www.php.net/parse_str
//    http_build_query     https://www.php.net/http_build_query
//    preg_replace_callback https://www.php.net/preg_replace_callback
//    preg_match           https://www.php.net/preg_match
//    json_encode          https://www.php.net/json_encode
//    array_reduce         https://www.php.net/array_reduce
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * Request
 *
 * new Request($method, $uri, $post = [], $headers = [], $cookies = [])
 *   $method   'GET', 'POST', ... (stored upper case)
 *   $uri      path with optional query string: '/users/7?tab=posts'
 *   $post     form fields, like $_POST
 *   $headers  ['Content-Type' => 'text/html', ...]
 *
 *   method()               'GET'
 *   path()                 '/users/7'            (no query string; '' becomes '/')
 *   query($key, $default)  from the query string; query() with no key: all of it
 *   input($key, $default)  from $post; input() with no key: all of it
 *   header($name)          case-insensitive lookup, null when absent
 *   cookie($name)          null when absent
 *   isPost()               method() === 'POST'
 *
 * Examples:
 *   $r = new Request('post', '/login?next=%2Fdash', ['email' => 'a@b.c'], ['Accept' => 'text/html']);
 *   $r->method()           ->  'POST'
 *   $r->path()             ->  '/login'
 *   $r->query('next')      ->  '/dash'
 *   $r->query('missing', 'x')  ->  'x'
 *   $r->input('email')     ->  'a@b.c'
 *   $r->header('accept')   ->  'text/html'
 *   $r->isPost()           ->  true
 */
class Request
{
    private string $method;
    private string $path;
    private array $query = [];

    public function __construct(string $method, string $uri, private array $post = [], private array $headers = [], private array $cookies = [])
    {
        // your code here
    }

    public function method(): string
    {
        // your code here
    }

    public function path(): string
    {
        // your code here
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        // your code here
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        // your code here
    }

    public function header(string $name): ?string
    {
        // your code here
    }

    public function cookie(string $name): ?string
    {
        // your code here
    }

    public function isPost(): bool
    {
        // your code here
    }
}

/**
 * Response
 *
 * new Response($body = '', $status = 200, $headers = [])
 *   status(), body(), headers()   (headers as ['Name' => 'value'])
 *   header($name)                 case-insensitive, null when absent
 *   withHeader($name, $value)     returns a NEW Response with the header added
 *
 * Static factories:
 *   Response::html($body, $status = 200)   Content-Type: text/html; charset=utf-8
 *   Response::text($body, $status = 200)   Content-Type: text/plain; charset=utf-8
 *   Response::json($data, $status = 200)   Content-Type: application/json, body json_encode($data)
 *   Response::redirect($to, $status = 302) Location: $to, empty body
 *
 * Examples:
 *   Response::json(['ok' => true])->body()        ->  '{"ok":true}'
 *   Response::redirect('/login')->status()        ->  302
 *   Response::redirect('/login')->header('location')  ->  '/login'
 *   Response::text('hi')->withHeader('X-Test', '1')->header('x-test')  ->  '1'
 */
class Response
{
    public function __construct(private string $body = '', private int $status = 200, private array $headers = []) {}

    public function status(): int
    {
        // your code here
    }

    public function body(): string
    {
        // your code here
    }

    public function headers(): array
    {
        // your code here
    }

    public function header(string $name): ?string
    {
        // your code here
    }

    public function withHeader(string $name, string $value): Response
    {
        // your code here
    }

    public static function html(string $body, int $status = 200): Response
    {
        // your code here
    }

    public static function text(string $body, int $status = 200): Response
    {
        // your code here
    }

    public static function json(mixed $data, int $status = 200): Response
    {
        // your code here
    }

    public static function redirect(string $to, int $status = 302): Response
    {
        // your code here
    }
}

/**
 * url_with($url, $params)
 *
 * Add or replace query parameters on a URL. A null value removes the
 * parameter. Values are URL-encoded. The result has no "?" when there
 * are no parameters left.
 *
 * Examples:
 *   url_with('/users', ['page' => 2])                    ->  '/users?page=2'
 *   url_with('/users?page=2&sort=name', ['page' => 3])   ->  '/users?page=3&sort=name'
 *   url_with('/users?page=2', ['page' => null])           ->  '/users'
 *   url_with('/search', ['q' => 'a b&c'])                 ->  '/search?q=a+b%26c'
 */
function url_with($url, $params)
{
    // your code here
}

// ---------------------------------------------- MEDIUM ------

/**
 * Router
 *
 *   get($pattern, $handler)  /  post($pattern, $handler)  /  add($method, $pattern, $handler)
 *   dispatch(Request $request): Response
 *
 * A pattern is a path with {name} placeholders: '/users/{id}/posts/{slug}'.
 * A placeholder matches one path segment (no slashes). The handler is
 * called as $handler($request, $params) where $params is
 * ['id' => '7', 'slug' => 'hello'] (strings), and must return a Response.
 *
 * No pattern matches the path: a 404 Response with body "Not Found".
 * A pattern matches the path but not the method: a 405 Response with
 * body "Method Not Allowed". Routes are tried in the order added; the
 * first match wins.
 *
 * Examples:
 *   $r = new Router();
 *   $r->get('/', fn($req, $p) => Response::text('home'));
 *   $r->get('/users/{id}', fn($req, $p) => Response::text("user $p[id]"));
 *   $r->post('/users', fn($req, $p) => Response::text('created', 201));
 *   $r->dispatch(new Request('GET', '/users/7?x=1'))->body()   ->  'user 7'
 *   $r->dispatch(new Request('GET', '/nope'))->status()        ->  404
 *   $r->dispatch(new Request('DELETE', '/users'))->status()    ->  405
 *
 * Hint: turn a pattern into a regex once: replace each {name} with
 * (?P<name>[^/]+), anchor it with ^ and $, and use preg_match with
 * named groups.
 */
class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        // your code here
    }

    public function get(string $pattern, callable $handler): void
    {
        // your code here
    }

    public function post(string $pattern, callable $handler): void
    {
        // your code here
    }

    public function dispatch(Request $request): Response
    {
        // your code here
    }
}

/**
 * Flash
 *
 * One-time messages that survive exactly one redirect. They live in
 * the session array, under the key 'flash', as a list of
 * ['type' => 'success', 'message' => 'Saved'].
 *
 *   Flash::add(&$session, $type, $message)
 *   Flash::pull(&$session)   returns every message and removes them
 *
 * Examples:
 *   $s = [];
 *   Flash::add($s, 'success', 'Saved');
 *   Flash::add($s, 'error', 'Oops');
 *   Flash::pull($s)   ->  [['type' => 'success', 'message' => 'Saved'], ['type' => 'error', 'message' => 'Oops']]
 *   Flash::pull($s)   ->  []
 */
class Flash
{
    public static function add(array &$session, string $type, string $message): void
    {
        // your code here
    }

    public static function pull(array &$session): array
    {
        // your code here
    }
}

// ---------------------------------------------- HARD --------

/**
 * Pipeline
 *
 * Middleware: functions that wrap the application. Each one receives
 * the request and a $next function; it may change the request, call
 * $next($request) to continue, and change the response that comes
 * back, or return its own response without calling $next at all.
 *
 *   pipe(callable $middleware)          adds one (returns $this, so calls chain)
 *   handle(Request $request, callable $core): Response
 *       runs the request through the middleware in the order they were
 *       added, and finally through $core($request), which returns the
 *       Response.
 *
 * Examples:
 *   $log = [];
 *   $p = (new Pipeline())
 *       ->pipe(function ($req, $next) use (&$log) { $log[] = 'a-in'; $res = $next($req); $log[] = 'a-out'; return $res; })
 *       ->pipe(function ($req, $next) use (&$log) { $log[] = 'b-in'; $res = $next($req); $log[] = 'b-out'; return $res; });
 *   $p->handle(new Request('GET', '/'), fn($req) => Response::text('core'))->body()   ->  'core'
 *   $log   ->  ['a-in', 'b-in', 'b-out', 'a-out']
 *
 *   A middleware that returns early stops the chain:
 *   $auth = fn($req, $next) => $req->cookie('user') ? $next($req) : Response::redirect('/login');
 *
 * Hint: build the chain from the inside out with array_reduce over the
 * reversed middleware list: each step wraps the previous $next.
 */
class Pipeline
{
    private array $middleware = [];

    public function pipe(callable $middleware): Pipeline
    {
        // your code here
    }

    public function handle(Request $request, callable $core): Response
    {
        // your code here
    }
}
