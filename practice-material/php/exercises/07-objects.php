<?php
// ============================================================
//  07 - Objects and Classes
// ============================================================
//
//  Run the tests:   open http://localhost/php-fundamentals/
//                   (or in a terminal:  php run.php 07)
//
//  A class is a blueprint. An object is one thing built from it.
//
//    class Dog {
//        public $name;                          // a property
//        private $tricks = [];                  // private: only code inside the class can touch it
//
//        public function __construct($name) {   // runs when you write `new Dog("Rex")`
//            $this->name = $name;               // $this is the object being worked on
//        }
//
//        public function learn($trick) {        // a method
//            $this->tricks[] = $trick;
//        }
//
//        public function __toString() {         // used when the object is turned into a string
//            return "Dog named $this->name";
//        }
//    }
//
//    $rex = new Dog("Rex");
//    $rex->learn("sit");
//    echo $rex->name;                           // Rex
//    echo $rex;                                 // Dog named Rex
//
//  In this file you fill in the method bodies. The class shapes are
//  already there.
//
//  Docs:
//    classes and objects      https://www.php.net/manual/en/language.oop5.basic.php
//    properties               https://www.php.net/manual/en/language.oop5.properties.php
//    constructors             https://www.php.net/manual/en/language.oop5.decon.php
//    visibility               https://www.php.net/manual/en/language.oop5.visibility.php
//    inheritance (extends)    https://www.php.net/manual/en/language.oop5.inheritance.php
//    abstract classes         https://www.php.net/manual/en/language.oop5.abstract.php
//    static methods           https://www.php.net/manual/en/language.oop5.static.php
//    __toString               https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
//
// ============================================================

// ---------------------------------------------- EASY --------

/**
 * Person
 *
 * The constructor is done for you. Fill in the two methods.
 *
 *   greet()     -> "Hi, I'm Ada"
 *   is_adult()  -> true when age is 18 or more
 *
 * Examples:
 *   $ada = new Person("Ada", 36);
 *   $ada->name        ->  "Ada"
 *   $ada->greet()     ->  "Hi, I'm Ada"
 *   $ada->is_adult()  ->  true
 *   (new Person("Tim", 9))->is_adult()  ->  false
 */
class Person
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function greet()
    {
        // your code here
    }

    public function is_adult()
    {
        // your code here
    }
}

/**
 * Counter
 *
 * Counts up from 0. The count is private, so the only way to read it
 * is the value() method.
 *
 *   increment()  -> adds 1, returns nothing
 *   value()      -> the current count
 *   reset()      -> back to 0, returns nothing
 *
 * Examples:
 *   $c = new Counter();
 *   $c->value()      ->  0
 *   $c->increment();
 *   $c->increment();
 *   $c->value()      ->  2
 *   $c->reset();
 *   $c->value()      ->  0
 */
class Counter
{
    private $count = 0;

    public function increment()
    {
        // your code here
    }

    public function value()
    {
        // your code here
    }

    public function reset()
    {
        // your code here
    }
}

// ---------------------------------------------- MEDIUM ------

/**
 * BankAccount
 *
 * Starts with a balance of 0.
 *
 *   deposit($amount)   -> adds the amount, returns the new balance
 *   withdraw($amount)  -> takes the amount out and returns true, but if
 *                         there is not enough money, changes nothing and
 *                         returns false
 *   balance()          -> the current balance
 *
 * Examples:
 *   $acct = new BankAccount();
 *   $acct->deposit(100)   ->  100
 *   $acct->deposit(50)    ->  150
 *   $acct->withdraw(30)   ->  true
 *   $acct->balance()      ->  120
 *   $acct->withdraw(500)  ->  false
 *   $acct->balance()      ->  120
 */
class BankAccount
{
    private $balance = 0;

    public function deposit($amount)
    {
        // your code here
    }

    public function withdraw($amount)
    {
        // your code here
    }

    public function balance()
    {
        // your code here
    }
}

/**
 * Playlist
 *
 * A list of songs. Each song is stored as ["title" => ..., "seconds" => ...].
 *
 *   add($title, $seconds)  -> adds a song, returns nothing
 *   count_songs()          -> how many songs
 *   total_seconds()        -> all the durations added up
 *   longest()              -> the title of the longest song, or null when empty
 *
 * Examples:
 *   $p = new Playlist();
 *   $p->longest()          ->  null
 *   $p->add("Intro", 90);
 *   $p->add("Anthem", 240);
 *   $p->add("Outro", 60);
 *   $p->count_songs()      ->  3
 *   $p->total_seconds()    ->  390
 *   $p->longest()          ->  "Anthem"
 */
class Playlist
{
    private $songs = [];

    public function add($title, $seconds)
    {
        // your code here
    }

    public function count_songs()
    {
        // your code here
    }

    public function total_seconds()
    {
        // your code here
    }

    public function longest()
    {
        // your code here
    }
}

// ---------------------------------------------- HARD --------

/**
 * Shape, Circle, Rectangle
 *
 * Shape is abstract: you never write `new Shape()`. It promises that
 * every shape has a name() and an area(), and it gives every shape
 * one shared describe() method that uses them.
 *
 * Fill in:
 *   Shape::describe()     -> "A circle with area 78.54"  (area rounded to 2 decimals)
 *   Circle::name()        -> "circle"
 *   Circle::area()        -> pi times radius squared   (M_PI is pi)
 *   Rectangle::name()     -> "rectangle"
 *   Rectangle::area()     -> width times height
 *
 * Examples:
 *   $c = new Circle(5);
 *   $c->area()       ->  78.5398...
 *   $c->describe()   ->  "A circle with area 78.54"
 *   $r = new Rectangle(3, 4);
 *   $r->area()       ->  12
 *   $r->describe()   ->  "A rectangle with area 12"
 *   $r instanceof Shape  ->  true
 *
 * Docs: https://www.php.net/manual/en/language.oop5.abstract.php
 *       https://www.php.net/round
 */
abstract class Shape
{
    abstract public function name();
    abstract public function area();

    public function describe()
    {
        // your code here
    }
}

class Circle extends Shape
{
    public function __construct(public $radius) {}

    public function name()
    {
        // your code here
    }

    public function area()
    {
        // your code here
    }
}

class Rectangle extends Shape
{
    public function __construct(public $width, public $height) {}

    public function name()
    {
        // your code here
    }

    public function area()
    {
        // your code here
    }
}

/**
 * Temperature
 *
 * Stores a temperature in Celsius.
 *
 *   __construct($celsius)
 *   celsius()                        -> the stored value
 *   fahrenheit()                     -> converted:  C * 9 / 5 + 32
 *   static from_fahrenheit($f)       -> a NEW Temperature built from a Fahrenheit value
 *                                       (C = (F - 32) * 5 / 9)
 *   __toString()                     -> "20°C"
 *
 * Examples:
 *   $t = new Temperature(20);
 *   $t->celsius()       ->  20
 *   $t->fahrenheit()    ->  68
 *   (string) $t         ->  "20°C"
 *   $f = Temperature::from_fahrenheit(212);
 *   $f->celsius()       ->  100
 *
 * A static method is called on the class, not on an object. Inside it,
 * `new self(...)` or `new Temperature(...)` builds the object.
 * Docs: https://www.php.net/manual/en/language.oop5.static.php
 */
class Temperature
{
    private $celsius;

    public function __construct($celsius)
    {
        // your code here
    }

    public function celsius()
    {
        // your code here
    }

    public function fahrenheit()
    {
        // your code here
    }

    public static function from_fahrenheit($f)
    {
        // your code here
    }

    public function __toString(): string
    {
        // your code here
        return '';
    }
}
