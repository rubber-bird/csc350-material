<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/07-objects.php';

describe('07 - Objects and Classes', function () {
    describe('easy', function () {
        it('Person: greet() -> "Hi, I\'m Ada", is_adult()', function () {
            $ada = new Person('Ada', 36);
            expect($ada->name)->toEqual('Ada');
            expect($ada->greet())->toEqual("Hi, I'm Ada");
            expect($ada->is_adult())->toEqual(true);
            expect((new Person('Tim', 9))->is_adult())->toEqual(false);
        });
        it('Counter: increment(), value(), reset()', function () {
            $c = new Counter();
            expect($c->value())->toEqual(0);
            $c->increment();
            $c->increment();
            expect($c->value())->toEqual(2);
            $c->reset();
            expect($c->value())->toEqual(0);
        });
    });

    describe('medium', function () {
        it('BankAccount: deposit, withdraw, no overdraft', function () {
            $acct = new BankAccount();
            expect($acct->deposit(100))->toEqual(100);
            expect($acct->deposit(50))->toEqual(150);
            expect($acct->withdraw(30))->toEqual(true);
            expect($acct->balance())->toEqual(120);
            expect($acct->withdraw(500))->toEqual(false);
            expect($acct->balance())->toEqual(120);
        });
        it('Playlist: add, count_songs, total_seconds, longest', function () {
            $p = new Playlist();
            expect($p->longest())->toEqual(null);
            expect($p->count_songs())->toEqual(0);
            $p->add('Intro', 90);
            $p->add('Anthem', 240);
            $p->add('Outro', 60);
            expect($p->count_songs())->toEqual(3);
            expect($p->total_seconds())->toEqual(390);
            expect($p->longest())->toEqual('Anthem');
        });
    });

    describe('hard', function () {
        it('Circle(5)->describe() -> "A circle with area 78.54"', function () {
            $c = new Circle(5);
            expect(round($c->area(), 4))->toEqual(78.5398);
            expect($c->name())->toEqual('circle');
            expect($c->describe())->toEqual('A circle with area 78.54');
            expect($c instanceof Shape)->toEqual(true);
        }, [
            'name() just returns \'circle\'. area() is M_PI * $this->radius * $this->radius.',
            'describe() is written once, in Shape, and works for every shape: call $this->name() and $this->area() there.',
            'return \'A \' . $this->name() . \' with area \' . round($this->area(), 2);',
        ]);
        it('Rectangle(3, 4)->describe() -> "A rectangle with area 12"', function () {
            $r = new Rectangle(3, 4);
            expect($r->area())->toEqual(12);
            expect($r->name())->toEqual('rectangle');
            expect($r->describe())->toEqual('A rectangle with area 12');
            expect($r instanceof Shape)->toEqual(true);
        }, [
            'Same pattern as Circle: name() returns \'rectangle\', area() is $this->width * $this->height.',
            'If Circle passes but this fails, describe() in Shape is probably hard-coding the word circle. Use $this->name().',
            'round(12, 2) is still 12, so \'A rectangle with area 12\' comes out right without extra work.',
        ]);
        it('Temperature: celsius, fahrenheit, from_fahrenheit, __toString', function () {
            $t = new Temperature(20);
            expect($t->celsius())->toEqual(20);
            expect($t->fahrenheit())->toEqual(68);
            expect((string) $t)->toEqual('20°C');
            $f = Temperature::from_fahrenheit(212);
            expect($f instanceof Temperature)->toEqual(true);
            expect($f->celsius())->toEqual(100);
        }, [
            'The constructor stores the value: $this->celsius = $celsius;  celsius() returns it, fahrenheit() returns $this->celsius * 9 / 5 + 32.',
            'from_fahrenheit is static, so there is no $this. Build and return a new object: return new self(($f - 32) * 5 / 9);',
            '__toString returns $this->celsius . \'°C\'. The (string) cast in the test calls it for you.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
