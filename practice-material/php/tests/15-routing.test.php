<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/15-routing.php';

describe('15 - Requests, routing and responses', function () {
    describe('easy', function () {
        it('Request parses method, path, query, input, headers and cookies', function () {
            $r = new Request('post', '/login?next=%2Fdash&tab=a', ['email' => 'a@b.c'], ['Accept' => 'text/html'], ['sid' => 'xyz']);
            expect($r->method())->toEqual('POST');
            expect($r->path())->toEqual('/login');
            expect($r->query('next'))->toEqual('/dash');
            expect($r->query())->toEqual(['next' => '/dash', 'tab' => 'a']);
            expect($r->query('missing', 'x'))->toEqual('x');
            expect($r->input('email'))->toEqual('a@b.c');
            expect($r->input())->toEqual(['email' => 'a@b.c']);
            expect($r->input('nope'))->toEqual(null);
            expect($r->header('accept'))->toEqual('text/html');
            expect($r->header('X-Nope'))->toEqual(null);
            expect($r->cookie('sid'))->toEqual('xyz');
            expect($r->cookie('nope'))->toEqual(null);
            expect($r->isPost())->toEqual(true);
            expect((new Request('GET', ''))->path())->toEqual('/');
            expect((new Request('GET', '/'))->isPost())->toEqual(false);
            expect((new Request('GET', '/x'))->query())->toEqual([]);
        });
        it('Response factories set status, body and Content-Type; withHeader returns a copy', function () {
            expect(Response::json(['ok' => true])->body())->toEqual('{"ok":true}');
            expect(Response::json([], 201)->status())->toEqual(201);
            expect(Response::json([])->header('content-type'))->toEqual('application/json');
            expect(Response::html('<p>hi</p>')->header('Content-Type'))->toEqual('text/html; charset=utf-8');
            expect(Response::text('hi')->header('Content-Type'))->toEqual('text/plain; charset=utf-8');
            expect(Response::text('hi', 404)->status())->toEqual(404);
            expect(Response::redirect('/login')->status())->toEqual(302);
            expect(Response::redirect('/login')->header('location'))->toEqual('/login');
            expect(Response::redirect('/x', 301)->status())->toEqual(301);
            expect(Response::redirect('/x')->body())->toEqual('');
            $a = Response::text('hi');
            $b = $a->withHeader('X-Test', '1');
            expect($b->header('x-test'))->toEqual('1');
            expect($a->header('x-test'))->toEqual(null);
            expect($b->body())->toEqual('hi');
            expect((new Response('b', 200, ['A' => '1']))->headers())->toEqual(['A' => '1']);
        });
        it('url_with adds, replaces and removes query parameters', function () {
            expect(url_with('/users', ['page' => 2]))->toEqual('/users?page=2');
            expect(url_with('/users?page=2&sort=name', ['page' => 3]))->toEqual('/users?page=3&sort=name');
            expect(url_with('/users?page=2', ['page' => null]))->toEqual('/users');
            expect(url_with('/search', ['q' => 'a b&c']))->toEqual('/search?q=a+b%26c');
            expect(url_with('/users?a=1', ['b' => 2, 'a' => null]))->toEqual('/users?b=2');
            expect(url_with('/users', []))->toEqual('/users');
        });
    });

    describe('medium', function () {
        it('Router matches static routes, and returns 404 / 405 responses', function () {
            $r = new Router();
            $r->get('/', fn($req, $p) => Response::text('home'));
            $r->post('/users', fn($req, $p) => Response::text('created', 201));
            expect($r->dispatch(new Request('GET', '/'))->body())->toEqual('home');
            expect($r->dispatch(new Request('POST', '/users'))->status())->toEqual(201);
            $nf = $r->dispatch(new Request('GET', '/nope'));
            expect($nf->status())->toEqual(404);
            expect($nf->body())->toEqual('Not Found');
            $na = $r->dispatch(new Request('DELETE', '/users'));
            expect($na->status())->toEqual(405);
            expect($na->body())->toEqual('Method Not Allowed');
            expect($r->dispatch(new Request('GET', '/users/'))->status())->toEqual(404);
        });
        it('Router passes {placeholders} to the handler as params', function () {
            $r = new Router();
            $r->get('/users/{id}', fn($req, $p) => Response::text("user $p[id]"));
            $r->get('/users/{id}/posts/{slug}', fn($req, $p) => Response::json($p));
            $r->add('PUT', '/users/{id}', fn($req, $p) => Response::text('updated ' . $p['id']));
            expect($r->dispatch(new Request('GET', '/users/7?x=1'))->body())->toEqual('user 7');
            expect($r->dispatch(new Request('GET', '/users/7/posts/hello-world'))->body())->toEqual('{"id":"7","slug":"hello-world"}');
            expect($r->dispatch(new Request('PUT', '/users/3'))->body())->toEqual('updated 3');
            expect($r->dispatch(new Request('GET', '/users/7/posts'))->status())->toEqual(404);
            expect($r->dispatch(new Request('GET', '/users/a/b'))->status())->toEqual(404);
            $seen = null;
            $r->get('/echo', function ($req, $p) use (&$seen) { $seen = $req->query('q'); return Response::text('ok'); });
            $r->dispatch(new Request('GET', '/echo?q=42'));
            expect($seen)->toEqual('42');
        });
        it('Flash messages survive until pulled, then disappear', function () {
            $s = [];
            expect(Flash::pull($s))->toEqual([]);
            Flash::add($s, 'success', 'Saved');
            Flash::add($s, 'error', 'Oops');
            expect(isset($s['flash']))->toEqual(true);
            expect(Flash::pull($s))->toEqual([['type' => 'success', 'message' => 'Saved'], ['type' => 'error', 'message' => 'Oops']]);
            expect(Flash::pull($s))->toEqual([]);
            expect(isset($s['flash']))->toEqual(false);
        });
    });

    describe('hard', function () {
        it('Pipeline runs middleware in order, around the core', function () {
            $log = [];
            $p = (new Pipeline())
                ->pipe(function ($req, $next) use (&$log) { $log[] = 'a-in'; $res = $next($req); $log[] = 'a-out'; return $res; })
                ->pipe(function ($req, $next) use (&$log) { $log[] = 'b-in'; $res = $next($req); $log[] = 'b-out'; return $res; });
            $res = $p->handle(new Request('GET', '/'), function ($req) use (&$log) { $log[] = 'core'; return Response::text('core'); });
            expect($res->body())->toEqual('core');
            expect($log)->toEqual(['a-in', 'b-in', 'core', 'b-out', 'a-out']);
            expect((new Pipeline())->handle(new Request('GET', '/'), fn($req) => Response::text('bare'))->body())->toEqual('bare');
        }, [
            'handle() must call the first middleware with ($request, $next) where $next runs the second, whose $next runs the third, ... whose $next is $core.',
            'array_reduce(array_reverse($this->middleware), fn($next, $mw) => fn($req) => $mw($req, $next), $core) builds exactly that, then call it with $request.',
        ]);
        it('Pipeline middleware can change the request, the response, or stop the chain', function () {
            $p = (new Pipeline())
                ->pipe(fn($req, $next) => $req->cookie('user') ? $next($req) : Response::redirect('/login'))
                ->pipe(fn($req, $next) => $next($req)->withHeader('X-Powered-By', 'me'));
            $core = fn($req) => Response::text('secret');
            $denied = $p->handle(new Request('GET', '/dash'), $core);
            expect($denied->status())->toEqual(302);
            expect($denied->header('X-Powered-By'))->toEqual(null);
            $ok = $p->handle(new Request('GET', '/dash', [], [], ['user' => '1']), $core);
            expect($ok->body())->toEqual('secret');
            expect($ok->header('x-powered-by'))->toEqual('me');
            $rewrite = (new Pipeline())->pipe(fn($req, $next) => $next(new Request('GET', '/rewritten')));
            expect($rewrite->handle(new Request('GET', '/x'), fn($req) => Response::text($req->path()))->body())->toEqual('/rewritten');
        });
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
