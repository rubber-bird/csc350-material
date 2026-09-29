<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../exercises/10-text.php';

describe('10 - Text and parsing', function () {
    describe('easy', function () {
        it('slugify("Hello, World!") -> "hello-world"', function () {
            expect(slugify('Hello, World!'))->toEqual('hello-world');
            expect(slugify("  PHP 8.3 -- what's new "))->toEqual('php-8-3-what-s-new');
            expect(slugify('---'))->toEqual('');
            expect(slugify('already-a-slug'))->toEqual('already-a-slug');
        });
        it('truncate keeps at most $max characters and ends with …', function () {
            expect(truncate('Hello world', 5))->toEqual('Hell…');
            expect(truncate('Hello', 5))->toEqual('Hello');
            expect(truncate('Ünïcödé text', 4))->toEqual('Ünï…');
            expect(truncate('', 3))->toEqual('');
        });
        it('format_bytes(1536) -> "1.5 KB"', function () {
            expect(format_bytes(0))->toEqual('0 B');
            expect(format_bytes(512))->toEqual('512 B');
            expect(format_bytes(1024))->toEqual('1.0 KB');
            expect(format_bytes(1536))->toEqual('1.5 KB');
            expect(format_bytes(5242880))->toEqual('5.0 MB');
            expect(format_bytes(1099511627776))->toEqual('1.0 TB');
        });
    });

    describe('medium', function () {
        it('parse_csv_line handles quotes, embedded commas and doubled quotes', function () {
            expect(parse_csv_line('a,b,c'))->toEqual(['a', 'b', 'c']);
            expect(parse_csv_line('a,"b,c",d'))->toEqual(['a', 'b,c', 'd']);
            expect(parse_csv_line('"say ""hi""",x'))->toEqual(['say "hi"', 'x']);
            expect(parse_csv_line('a,,c'))->toEqual(['a', '', 'c']);
            expect(parse_csv_line(''))->toEqual(['']);
            expect(parse_csv_line('"",""'))->toEqual(['', '']);
        });
        it('render_template fills {{name}} placeholders and escapes HTML', function () {
            expect(render_template('Hi {{name}}!', ['name' => 'Ada']))->toEqual('Hi Ada!');
            expect(render_template('{{ a }}-{{b}}', ['a' => '<b>', 'b' => 2]))->toEqual('&lt;b&gt;-2');
            expect(render_template('{{missing}}', []))->toEqual('');
            expect(render_template('{{x}}{{x}}', ['x' => 'y']))->toEqual('yy');
            expect(render_template('no placeholders', ['x' => 1]))->toEqual('no placeholders');
        });
        it('extract_emails finds addresses in order without duplicates', function () {
            expect(extract_emails('Write to ada@example.org or alan.t@cs.man.ac.uk today'))->toEqual(['ada@example.org', 'alan.t@cs.man.ac.uk']);
            expect(extract_emails('ada@example.org, ada@example.org'))->toEqual(['ada@example.org']);
            expect(extract_emails('no addresses here, not even a@b'))->toEqual([]);
            expect(extract_emails('(grace+lists@navy.mil)'))->toEqual(['grace+lists@navy.mil']);
        });
    });

    describe('hard', function () {
        it('wrap_text breaks at spaces within the width', function () {
            expect(wrap_text('the quick brown fox jumps', 10))->toEqual("the quick\nbrown fox\njumps");
            expect(wrap_text("a  b\nc", 5))->toEqual('a b c');
            expect(wrap_text('extraordinary cat', 5))->toEqual("extraordinary\ncat");
            expect(wrap_text('', 5))->toEqual('');
            expect(wrap_text('one two three four', 9))->toEqual("one two\nthree\nfour");
        }, [
            'Split into words with preg_split("/\\s+/", ...). Then build lines: add the word if it fits, otherwise start a new line.',
            'A word fits when strlen($line) + 1 + strlen($word) <= $width. The first word of a line always goes on it.',
        ]);
        it('parse_duration("1h 30m 15s") -> 5415, nonsense -> null', function () {
            expect(parse_duration('1h 30m 15s'))->toEqual(5415);
            expect(parse_duration('45m'))->toEqual(2700);
            expect(parse_duration('2h5s'))->toEqual(7205);
            expect(parse_duration('90s'))->toEqual(90);
            expect(parse_duration('15s 1h'))->toEqual(3615);
            expect(parse_duration('soon'))->toEqual(null);
            expect(parse_duration(''))->toEqual(null);
            expect(parse_duration('1h and 2m'))->toEqual(null);
        }, [
            'Two regexes: one to check the whole string is nothing but number-unit pairs, one (preg_match_all) to pull the pairs out.',
            'Pattern for a pair: (\\d+)\\s*([hms]). Multiply by 3600, 60 or 1.',
        ]);
        it('parse_config reads sections, comments, booleans and integers', function () {
            $text = "name = demo\ndebug = true\n\n[db]\nhost = localhost\nport = 3306\n; comment\n# another\n[cache]\nenabled=false\n";
            expect(parse_config($text))->toEqual([
                'name' => 'demo', 'debug' => true,
                'db' => ['host' => 'localhost', 'port' => 3306],
                'cache' => ['enabled' => false],
            ]);
            expect(parse_config(''))->toEqual([]);
            expect(parse_config("[empty]\n"))->toEqual(['empty' => []]);
            expect(parse_config("url = http://x.org/?a=b\n"))->toEqual(['url' => 'http://x.org/?a=b']);
        }, [
            'Loop over the lines. Keep a $section variable that is null until a [section] line appears.',
            'Split key from value with explode("=", $line, 2) so a value may itself contain "=".',
        ]);
        it('highlight wraps matches in <mark> and escapes everything', function () {
            expect(highlight('PHP is fun, php!', 'php'))->toEqual('<mark>PHP</mark> is fun, <mark>php</mark>!');
            expect(highlight('a < b', '<'))->toEqual('a <mark>&lt;</mark> b');
            expect(highlight('a < b', ''))->toEqual('a &lt; b');
            expect(highlight('nothing', 'zzz'))->toEqual('nothing');
            expect(highlight('<b>bold</b>', 'b'))->toEqual('&lt;<mark>b</mark>&gt;<mark>b</mark>old&lt;/<mark>b</mark>&gt;');
        }, [
            'Do not escape first and search second: the term "<" would then never match "&lt;". Search the raw text, escape each piece as you output it.',
            'preg_match_all with PREG_OFFSET_CAPTURE gives every match and where it starts; walk through them, copying the escaped text in between.',
        ]);
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
