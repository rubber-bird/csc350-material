<?php
// 11 - Objects, further: reference solution

interface Figure
{
    public function area(): float;
    public function name(): string;
}

class Square implements Figure
{
    public function __construct(private float $side) {}
    public function area(): float { return round($this->side * $this->side, 2); }
    public function name(): string { return 'square'; }
}

class Disc implements Figure
{
    public function __construct(private float $radius) {}
    public function area(): float { return round(M_PI * $this->radius * $this->radius, 2); }
    public function name(): string { return 'disc'; }
}

function total_area(array $shapes): float
{
    $sum = 0.0;
    foreach ($shapes as $shape) $sum += $shape->area();
    return round($sum, 2);
}

abstract class Animal
{
    public function __construct(protected string $name) {}
    abstract public function sound(): string;
    public function describe(): string { return "{$this->name} says {$this->sound()}"; }
}

class Dog extends Animal
{
    public function sound(): string { return 'Woof'; }
}

class Cat extends Animal
{
    public function sound(): string { return 'Meow'; }
}

class Degrees
{
    private function __construct(private float $celsius) {}
    public static function fromCelsius(float $c): Degrees { return new Degrees($c); }
    public static function fromFahrenheit(float $f): Degrees { return new Degrees(($f - 32) * 5 / 9); }
    public function celsius(): float { return round($this->celsius, 1); }
    public function fahrenheit(): float { return round($this->celsius * 9 / 5 + 32, 1); }
}

class InsufficientFunds extends Exception {}

class Wallet
{
    public function __construct(private int $balance) {}
    public function balance(): int { return $this->balance; }
    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            throw new InsufficientFunds("Cannot withdraw {$amount}: balance is {$this->balance}");
        }
        $this->balance -= $amount;
    }
}

enum Suit: string
{
    case Hearts = 'H';
    case Diamonds = 'D';
    case Clubs = 'C';
    case Spades = 'S';

    public function color(): string
    {
        return match ($this) {
            Suit::Hearts, Suit::Diamonds => 'red',
            Suit::Clubs, Suit::Spades => 'black',
        };
    }
}

class Money
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
    ) {}

    public function add(Money $other): Money
    {
        if ($other->currency !== $this->currency) {
            throw new InvalidArgumentException("Cannot add {$other->currency} to {$this->currency}");
        }
        return new Money($this->amount + $other->amount, $this->currency);
    }

    public function multiply(int $factor): Money
    {
        return new Money($this->amount * $factor, $this->currency);
    }

    public function __toString(): string
    {
        return number_format($this->amount / 100, 2, '.', '') . ' ' . $this->currency;
    }
}

class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    public function __construct(private array $items = []) {}
    public function count(): int { return count($this->items); }
    public function getIterator(): Iterator { return new ArrayIterator($this->items); }
    public function offsetExists(mixed $offset): bool { return isset($this->items[$offset]); }
    public function offsetGet(mixed $offset): mixed { return $this->items[$offset]; }
    public function offsetSet(mixed $offset, mixed $value): void { throw new LogicException('Collection is read only'); }
    public function offsetUnset(mixed $offset): void { throw new LogicException('Collection is read only'); }
    public function map(callable $fn): Collection { return new Collection(array_map($fn, $this->items)); }
    public function filter(callable $fn): Collection { return new Collection(array_values(array_filter($this->items, $fn))); }
    public function reduce(callable $fn, mixed $initial): mixed { return array_reduce($this->items, $fn, $initial); }
    public function toArray(): array { return $this->items; }
}

class EventEmitter
{
    private array $listeners = [];

    public function on(string $event, callable $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function emit(string $event, ...$args): int
    {
        $called = 0;
        foreach ($this->listeners[$event] ?? [] as $listener) {
            $listener(...$args);
            $called++;
        }
        return $called;
    }

    public function off(string $event): void
    {
        unset($this->listeners[$event]);
    }
}

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
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $op, mixed $value): QueryBuilder
    {
        $this->wheres[] = "$column $op ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        $this->order = "$column " . strtoupper($direction);
        return $this;
    }

    public function limit(int $n): QueryBuilder
    {
        $this->limit = $n;
        return $this;
    }

    public function toSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if ($this->wheres) $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        if ($this->order !== null) $sql .= ' ORDER BY ' . $this->order;
        if ($this->limit !== null) $sql .= ' LIMIT ' . $this->limit;
        return $sql;
    }

    public function bindings(): array
    {
        return $this->bindings;
    }
}
