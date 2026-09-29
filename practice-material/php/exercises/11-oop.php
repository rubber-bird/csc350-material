<?php
// ============================================================
//  11 - Objects, further
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 11)
//
//  Module 07 covered the basics. This one covers what real code bases
//  are built from:
//
//    interface   a list of methods a class promises to have
//    abstract    a class that cannot be instantiated; subclasses finish it
//    static      a method or property that belongs to the class, not an object
//    exceptions  throw an object when something goes wrong; catch it elsewhere
//    enum        a type with a fixed set of named values (PHP 8.1)
//    readonly    a property that can only be set once (PHP 8.1)
//    Countable, IteratorAggregate, ArrayAccess
//                interfaces that let your object work with count(),
//                foreach and [] like an array
//
//  The class and interface shapes are given; fill in the bodies. Where
//  a shape is missing, the comment says what to write.
//
//  Docs:
//    interfaces        https://www.php.net/manual/en/language.oop5.interfaces.php
//    abstract classes  https://www.php.net/manual/en/language.oop5.abstract.php
//    static            https://www.php.net/manual/en/language.oop5.static.php
//    exceptions        https://www.php.net/manual/en/language.exceptions.php
//    enums             https://www.php.net/manual/en/language.enumerations.php
//    readonly          https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.readonly-properties
//    ArrayAccess       https://www.php.net/manual/en/class.arrayaccess.php
//    IteratorAggregate https://www.php.net/manual/en/class.iteratoraggregate.php
//    Countable         https://www.php.net/manual/en/class.countable.php
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * Figure is an interface: anything that is a Figure has area() and
 * name(). Square and Disc implement it. area() rounds to 2 decimals.
 *
 * Examples:
 *   (new Square(3))->area()    ->  9.0
 *   (new Disc(1))->area()    ->  3.14
 *   (new Disc(1))->name()    ->  "disc"
 *   total_area([new Square(2), new Disc(1)])  ->  7.14
 */
interface Figure
{
    public function area(): float;
    public function name(): string;
}

class Square implements Figure
{
    public function __construct(private float $side) {}

    public function area(): float
    {
        // your code here
    }

    public function name(): string
    {
        // your code here
    }
}

class Disc implements Figure
{
    public function __construct(private float $radius) {}

    public function area(): float
    {
        // your code here
    }

    public function name(): string
    {
        // your code here
    }
}

/**
 * total_area($shapes)
 *
 * The areas of any list of Shapes added up, rounded to 2 decimals.
 * It must not care which concrete classes are in the list.
 */
function total_area(array $shapes): float
{
    // your code here
}

/**
 * Animal is abstract: you cannot `new Animal()`. Each subclass must
 * provide sound(). describe() is written once, here, and uses it.
 *
 * Examples:
 *   (new Dog('Rex'))->describe()     ->  "Rex says Woof"
 *   (new Cat('Tom'))->describe()     ->  "Tom says Meow"
 */
abstract class Animal
{
    public function __construct(protected string $name) {}

    abstract public function sound(): string;

    public function describe(): string
    {
        // your code here
    }
}

class Dog extends Animal
{
    public function sound(): string
    {
        // your code here
    }
}

class Cat extends Animal
{
    public function sound(): string
    {
        // your code here
    }
}

/**
 * Degrees has a private constructor and two static factories, so
 * the reader always sees which unit a number is in.
 *
 * Examples:
 *   Degrees::fromCelsius(100)->celsius()       ->  100.0
 *   Degrees::fromCelsius(100)->fahrenheit()    ->  212.0
 *   Degrees::fromFahrenheit(32)->celsius()     ->  0.0
 *   Degrees::fromFahrenheit(98.6)->celsius()   ->  37.0
 */
class Degrees
{
    private function __construct(private float $celsius) {}

    public static function fromCelsius(float $c): Degrees
    {
        // your code here
    }

    public static function fromFahrenheit(float $f): Degrees
    {
        // your code here
    }

    public function celsius(): float
    {
        // your code here
    }

    public function fahrenheit(): float
    {
        // your code here
    }
}

// ---------------------------------------------- MEDIUM ------

/**
 * InsufficientFunds is an exception. Wallet::withdraw() throws it when
 * the balance is too low, with the message
 *   "Cannot withdraw 50: balance is 20"
 * and leaves the balance unchanged. Amounts are integers.
 *
 * Examples:
 *   $w = new Wallet(20);
 *   $w->withdraw(5);   $w->balance()   ->  15
 *   $w->withdraw(50)   ->  throws InsufficientFunds("Cannot withdraw 50: balance is 15")
 */
class InsufficientFunds extends Exception {}

class Wallet
{
    public function __construct(private int $balance) {}

    public function balance(): int
    {
        // your code here
    }

    public function withdraw(int $amount): void
    {
        // your code here
    }
}

/**
 * Suit is a backed enum with four cases: Hearts 'H', Diamonds 'D',
 * Clubs 'C', Spades 'S'. color() is "red" for hearts and diamonds,
 * "black" for the others. Write the enum yourself, below this comment.
 *
 * Examples:
 *   Suit::Hearts->value        ->  "H"
 *   Suit::from('S')            ->  Suit::Spades
 *   Suit::Hearts->color()      ->  "red"
 *   Suit::Clubs->color()       ->  "black"
 *   count(Suit::cases())       ->  4
 *
 * Docs: https://www.php.net/manual/en/language.enumerations.backed.php
 */

// your code here (enum Suit)


/**
 * Money is a value object: once made it never changes. add() and
 * multiply() return NEW Money objects. Amounts are in cents (integers)
 * so there is no rounding. Adding two currencies throws
 * InvalidArgumentException. __toString formats as "12.34 USD".
 *
 * Examples:
 *   $a = new Money(1250, 'USD');
 *   $b = $a->add(new Money(50, 'USD'));
 *   $b->amount           ->  1300
 *   $a->amount           ->  1250            (unchanged)
 *   (string) $b          ->  "13.00 USD"
 *   $a->multiply(3)->amount   ->  3750
 *   $a->add(new Money(1, 'EUR'))   ->  throws InvalidArgumentException
 *   $a->amount = 5       ->  throws Error (readonly)
 */
class Money
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
    ) {}

    public function add(Money $other): Money
    {
        // your code here
    }

    public function multiply(int $factor): Money
    {
        // your code here
    }

    public function __toString(): string
    {
        // your code here
    }
}

// ---------------------------------------------- HARD --------

/**
 * Collection wraps a list and behaves like an array where it matters:
 *   count($c)            via Countable
 *   foreach ($c as $x)   via IteratorAggregate
 *   $c[0], isset($c[0])  via ArrayAccess (read only: setting or
 *                        unsetting throws LogicException)
 * map(), filter() return NEW Collections; filter() renumbers from 0.
 * reduce() returns a plain value. toArray() returns the list.
 *
 * Examples:
 *   $c = new Collection([1, 2, 3, 4]);
 *   count($c)                                    ->  4
 *   $c[1]                                        ->  2
 *   $c->map(fn($n) => $n * 10)->toArray()        ->  [10, 20, 30, 40]
 *   $c->filter(fn($n) => $n % 2 === 0)->toArray() ->  [2, 4]
 *   $c->reduce(fn($sum, $n) => $sum + $n, 0)     ->  10
 *   $c->filter(fn($n) => $n > 2)[0]              ->  3
 */
class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    public function __construct(private array $items = []) {}

    public function count(): int
    {
        // your code here
    }

    public function getIterator(): Iterator
    {
        // your code here (ArrayIterator is handy)
    }

    public function offsetExists(mixed $offset): bool
    {
        // your code here
    }

    public function offsetGet(mixed $offset): mixed
    {
        // your code here
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // your code here
    }

    public function offsetUnset(mixed $offset): void
    {
        // your code here
    }

    public function map(callable $fn): Collection
    {
        // your code here
    }

    public function filter(callable $fn): Collection
    {
        // your code here
    }

    public function reduce(callable $fn, mixed $initial): mixed
    {
        // your code here
    }

    public function toArray(): array
    {
        // your code here
    }
}

/**
 * EventEmitter: register listeners for named events, fire them.
 *   on($event, $listener)   adds a listener (many per event, kept in order)
 *   emit($event, ...$args)  calls every listener for that event with the
 *                           args; returns how many were called
 *   off($event)             removes all listeners for that event
 *
 * Examples:
 *   $e = new EventEmitter();
 *   $log = [];
 *   $e->on('save', function ($id) use (&$log) { $log[] = "saved $id"; });
 *   $e->emit('save', 7)     ->  1     and $log is ["saved 7"]
 *   $e->emit('nothing')     ->  0
 */
class EventEmitter
{
    private array $listeners = [];

    public function on(string $event, callable $listener): void
    {
        // your code here
    }

    public function emit(string $event, ...$args): int
    {
        // your code here
    }

    public function off(string $event): void
    {
        // your code here
    }
}

/**
 * QueryBuilder builds a SELECT with placeholders, the way a database
 * layer does. Every method returns $this so calls chain. toSql() gives
 * the SQL; bindings() gives the values for the placeholders, in order.
 * With no where(), no WHERE clause. orderBy defaults to ASC.
 *
 * Examples:
 *   $q = (new QueryBuilder('users'))->where('age', '>', 18)->where('city', '=', 'Leeds')->orderBy('name')->limit(10);
 *   $q->toSql()      ->  "SELECT * FROM users WHERE age > ? AND city = ? ORDER BY name ASC LIMIT 10"
 *   $q->bindings()   ->  [18, 'Leeds']
 *   (new QueryBuilder('posts'))->select('id', 'title')->orderBy('id', 'DESC')->toSql()
 *                    ->  "SELECT id, title FROM posts ORDER BY id DESC"
 *   (new QueryBuilder('posts'))->toSql()   ->  "SELECT * FROM posts"
 */
class QueryBuilder
{
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private ?string $order = null;
    private ?int $limit = null;

    public function __construct(private string $table) {}

    public function select(string ...$columns): QueryBuilder
    {
        // your code here
    }

    public function where(string $column, string $op, mixed $value): QueryBuilder
    {
        // your code here
    }

    public function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        // your code here
    }

    public function limit(int $n): QueryBuilder
    {
        // your code here
    }

    public function toSql(): string
    {
        // your code here
    }

    public function bindings(): array
    {
        // your code here
    }
}
