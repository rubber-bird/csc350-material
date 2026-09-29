<?php
// Reference solutions - 07 Objects and Classes

class Person
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function greet() { return "Hi, I'm $this->name"; }
    public function is_adult() { return $this->age >= 18; }
}

class Counter
{
    private $count = 0;

    public function increment() { $this->count++; }
    public function value() { return $this->count; }
    public function reset() { $this->count = 0; }
}

class BankAccount
{
    private $balance = 0;

    public function deposit($amount)
    {
        $this->balance += $amount;
        return $this->balance;
    }

    public function withdraw($amount)
    {
        if ($amount > $this->balance) return false;
        $this->balance -= $amount;
        return true;
    }

    public function balance() { return $this->balance; }
}

class Playlist
{
    private $songs = [];

    public function add($title, $seconds)
    {
        $this->songs[] = ['title' => $title, 'seconds' => $seconds];
    }

    public function count_songs() { return count($this->songs); }

    public function total_seconds() { return array_sum(array_column($this->songs, 'seconds')); }

    public function longest()
    {
        if (count($this->songs) === 0) return null;
        $best = $this->songs[0];
        foreach ($this->songs as $song) {
            if ($song['seconds'] > $best['seconds']) $best = $song;
        }
        return $best['title'];
    }
}

abstract class Shape
{
    abstract public function name();
    abstract public function area();

    public function describe()
    {
        return 'A ' . $this->name() . ' with area ' . round($this->area(), 2);
    }
}

class Circle extends Shape
{
    public function __construct(public $radius) {}
    public function name() { return 'circle'; }
    public function area() { return M_PI * $this->radius * $this->radius; }
}

class Rectangle extends Shape
{
    public function __construct(public $width, public $height) {}
    public function name() { return 'rectangle'; }
    public function area() { return $this->width * $this->height; }
}

class Temperature
{
    private $celsius;

    public function __construct($celsius) { $this->celsius = $celsius; }
    public function celsius() { return $this->celsius; }
    public function fahrenheit() { return $this->celsius * 9 / 5 + 32; }
    public static function from_fahrenheit($f) { return new self(($f - 32) * 5 / 9); }
    public function __toString(): string { return $this->celsius . '°C'; }
}
