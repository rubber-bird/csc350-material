<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/11-oop.php';

// Run a function and return the exception it throws, or null.
function caught(callable $fn): ?Throwable
{
    try { $fn(); return null; } catch (Throwable $e) { return $e; }
}

describe('11 - Objects, further', function () {
    describe('easy', function () {
        it('Square and Disc implement Figure; total_area adds any shapes', function () {
            expect((new Square(3)) instanceof Figure)->toEqual(true);
            expect((new Square(3))->area())->toEqual(9.0);
            expect((new Square(3))->name())->toEqual('square');
            expect((new Disc(1))->area())->toEqual(3.14);
            expect((new Disc(2))->area())->toEqual(12.57);
            expect((new Disc(1))->name())->toEqual('disc');
            expect(total_area([new Square(2), new Disc(1)]))->toEqual(7.14);
            expect(total_area([]))->toEqual(0.0);
        });
        it('Dog and Cat finish the abstract Animal; describe() is inherited', function () {
            expect((new Dog('Rex'))->describe())->toEqual('Rex says Woof');
            expect((new Cat('Tom'))->describe())->toEqual('Tom says Meow');
            expect((new Dog('Rex')) instanceof Animal)->toEqual(true);
            $e = caught(fn() => new Animal('x'));
            expect($e instanceof Error)->toEqual(true);
        });
        it('Degrees::fromCelsius / fromFahrenheit static factories', function () {
            expect(Degrees::fromCelsius(100)->celsius())->toEqual(100.0);
            expect(Degrees::fromCelsius(100)->fahrenheit())->toEqual(212.0);
            expect(Degrees::fromFahrenheit(32)->celsius())->toEqual(0.0);
            expect(Degrees::fromFahrenheit(98.6)->celsius())->toEqual(37.0);
            expect(Degrees::fromCelsius(-40)->fahrenheit())->toEqual(-40.0);
            $e = caught(fn() => new Degrees(1));
            expect($e instanceof Error)->toEqual(true);
        });
    });

    describe('medium', function () {
        it('Wallet::withdraw throws InsufficientFunds with the right message and keeps the balance', function () {
            $w = new Wallet(20);
            $w->withdraw(5);
            expect($w->balance())->toEqual(15);
            $e = caught(fn() => $w->withdraw(50));
            expect($e instanceof InsufficientFunds)->toEqual(true);
            expect($e?->getMessage())->toEqual('Cannot withdraw 50: balance is 15');
            expect($w->balance())->toEqual(15);
            $w->withdraw(15);
            expect($w->balance())->toEqual(0);
        });
        it('Suit is a string-backed enum with a color() method', function () {
            expect(enum_exists('Suit'))->toEqual(true);
            expect(Suit::Hearts->value)->toEqual('H');
            expect(Suit::from('S'))->toEqual(Suit::Spades);
            expect(Suit::tryFrom('X'))->toEqual(null);
            expect(Suit::Hearts->color())->toEqual('red');
            expect(Suit::Diamonds->color())->toEqual('red');
            expect(Suit::Clubs->color())->toEqual('black');
            expect(Suit::Spades->color())->toEqual('black');
            expect(count(Suit::cases()))->toEqual(4);
        });
        it('Money is immutable: add() and multiply() return new objects, __toString formats', function () {
            $a = new Money(1250, 'USD');
            $b = $a->add(new Money(50, 'USD'));
            expect($b->amount)->toEqual(1300);
            expect($a->amount)->toEqual(1250);
            expect((string) $b)->toEqual('13.00 USD');
            expect((string) new Money(5, 'EUR'))->toEqual('0.05 EUR');
            expect($a->multiply(3)->amount)->toEqual(3750);
            expect(caught(fn() => $a->add(new Money(1, 'EUR'))) instanceof InvalidArgumentException)->toEqual(true);
            expect(caught(function () use ($a) { $a->amount = 5; }) instanceof Error)->toEqual(true);
        });
    });

    describe('hard', function () {
        it('Collection works with count(), foreach and [], and map/filter/reduce', function () {
            $c = new Collection([1, 2, 3, 4]);
            expect(count($c))->toEqual(4);
            expect($c[1])->toEqual(2);
            expect(isset($c[3]))->toEqual(true);
            expect(isset($c[9]))->toEqual(false);
            $seen = [];
            foreach ($c as $x) $seen[] = $x;
            expect($seen)->toEqual([1, 2, 3, 4]);
            expect($c->map(fn($n) => $n * 10)->toArray())->toEqual([10, 20, 30, 40]);
            expect($c->filter(fn($n) => $n % 2 === 0)->toArray())->toEqual([2, 4]);
            expect($c->filter(fn($n) => $n > 2)[0])->toEqual(3);
            expect($c->reduce(fn($sum, $n) => $sum + $n, 0))->toEqual(10);
            expect($c->toArray())->toEqual([1, 2, 3, 4]);
            expect(caught(function () use ($c) { $c[0] = 9; }) instanceof LogicException)->toEqual(true);
            expect(caught(function () use ($c) { unset($c[0]); }) instanceof LogicException)->toEqual(true);
            expect($c->map(fn($n) => $n) instanceof Collection)->toEqual(true);
        }, [
            'Each interface method maps to one array function: count(), new ArrayIterator($this->items), isset($this->items[$offset]), $this->items[$offset].',
            'filter() must renumber: new Collection(array_values(array_filter($this->items, $fn))).',
        ]);
        it('EventEmitter: on / emit / off', function () {
            $e = new EventEmitter();
            $log = [];
            $e->on('save', function ($id) use (&$log) { $log[] = "saved $id"; });
            $e->on('save', function ($id) use (&$log) { $log[] = "logged $id"; });
            $e->on('delete', function ($id, $by) use (&$log) { $log[] = "$by deleted $id"; });
            expect($e->emit('save', 7))->toEqual(2);
            expect($e->emit('delete', 3, 'ada'))->toEqual(1);
            expect($e->emit('nothing'))->toEqual(0);
            expect($log)->toEqual(['saved 7', 'logged 7', 'ada deleted 3']);
            $e->off('save');
            expect($e->emit('save', 8))->toEqual(0);
            expect(count($log))->toEqual(3);
        }, [
            'Store listeners as $this->listeners[$event][] = $listener; emit loops over $this->listeners[$event] ?? [].',
            'Call a stored callable with the spread operator: $listener(...$args).',
        ]);
        it('QueryBuilder chains and produces SQL with placeholders plus bindings', function () {
            $q = (new QueryBuilder('users'))->where('age', '>', 18)->where('city', '=', 'Leeds')->orderBy('name')->limit(10);
            expect($q->toSql())->toEqual('SELECT * FROM users WHERE age > ? AND city = ? ORDER BY name ASC LIMIT 10');
            expect($q->bindings())->toEqual([18, 'Leeds']);
            expect((new QueryBuilder('posts'))->select('id', 'title')->orderBy('id', 'DESC')->toSql())->toEqual('SELECT id, title FROM posts ORDER BY id DESC');
            expect((new QueryBuilder('posts'))->toSql())->toEqual('SELECT * FROM posts');
            expect((new QueryBuilder('posts'))->bindings())->toEqual([]);
            expect((new QueryBuilder('posts'))->where('id', '=', 1)->toSql())->toEqual('SELECT * FROM posts WHERE id = ?');
            expect((new QueryBuilder('posts'))->limit(1)->toSql())->toEqual('SELECT * FROM posts LIMIT 1');
            $b = new QueryBuilder('t');
            expect($b->where('a', '=', 1) === $b)->toEqual(true);
        }, [
            'Every builder method records something on $this and ends with return $this;.',
            'toSql() assembles the pieces in order: SELECT columns FROM table, then WHERE (implode " AND "), ORDER BY, LIMIT, each only if set.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
