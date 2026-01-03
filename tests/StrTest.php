<?php

declare(strict_types=1);

namespace Guillaumetissier\String\Tests;

use Guillaumetissier\String\Str;
use Guillaumetissier\String\StrImmutable;
use PHPUnit\Framework\TestCase;

final class StrTest extends TestCase
{
    public function testCreationAndValue(): void
    {
        $str = Str::of('hello');

        $this->assertSame('hello', $str->value());
        $this->assertSame('hello', (string) $str);
    }

    public function testLength(): void
    {
        $this->assertSame(5, Str::of('Hello')->length());
        $this->assertSame(5, Str::of('Héllo')->length());
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Str::of('')->isEmpty());
        $this->assertFalse(Str::of(' ')->isEmpty());
    }

    /**
     * @dataProvider dataTrim
     */
    public function testTrim(string $original, string $expected, ?string $chars = null): void
    {
        $str = Str::of($original);
        if (null !== $chars) {
            $trimmedStr = $str->trim($chars);
        } else {
            $trimmedStr = $str->trim();
        }

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $trimmedStr->value());
    }

    public static function dataTrim(): \Generator
    {
        yield ['  test  ', 'test', null];
        yield ['--test--', 'test', '-'];
        yield ['--test--', '--test--', 'x'];
    }

    public function testLower(): void
    {
        $str = Str::of('HÉLLO Wörld');
        $upperStr = $str->lower();

        $this->assertSame('héllo wörld', $str->value());
        $this->assertSame('héllo wörld', $upperStr->value());
    }

    public function testUpper(): void
    {
        $str = Str::of('héllô wörld');
        $upperStr = $str->upper();

        $this->assertSame('HÉLLÔ WÖRLD', $str->value());
        $this->assertSame('HÉLLÔ WÖRLD', $upperStr->value());
    }

    public function testStartsWith(): void
    {
        $this->assertTrue(Str::of('hello world')->startsWith('hello'));
        $this->assertFalse(Str::of('hello world')->startsWith('world'));
        $this->assertTrue(Str::of('hello')->startsWith(''));
    }

    public function testEndsWith(): void
    {
        $this->assertTrue(Str::of('hello world')->endsWith('world'));
        $this->assertFalse(Str::of('hello world')->endsWith('hello'));
        $this->assertTrue(Str::of('hello')->endsWith(''));
    }

    public function testContains(): void
    {
        $this->assertTrue(Str::of('hello world')->contains('lo wo'));
        $this->assertFalse(Str::of('hello world')->contains('test'));
        $this->assertTrue(Str::of('hello')->contains(''));
    }

    /**
     * @dataProvider dataReplace
     */
    public function testReplace(string $original, string $search, string $replace, string $expected): void
    {
        $str = Str::of($original);
        $replacedStr = $str->replace($search, $replace);

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $replacedStr->value());
    }

    public static function dataReplace(): \Generator
    {
        yield ['hello world', 'world', 'universe', 'hello universe'];
        yield ['hello world', 'dummy', 'universe', 'hello world'];
        yield ['hello world', '', 'universe', 'hello world'];
    }

    /**
     * @dataProvider dataSlug
     */
    public function testSlug(string $original, string $expected): void
    {
        $str = Str::of($original);
        $slugStr = $str->slug();

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $slugStr->value());
    }

    public static function dataSlug(): \Generator
    {
        yield ['Hello World', 'hello-world'];
        yield ['Héllo Wörld!', 'hello-world'];
        yield ['Hello World', 'hello-world'];
        yield ['HELLO!World', 'hello-world'];
    }

    /**
     * @dataProvider dataSnake
     */
    public function testSnake(string $original, string $expected): void
    {
        $str = Str::of($original);
        $snakeStr = $str->snake();

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $snakeStr->value());
    }

    public static function dataSnake(): \Generator
    {
        yield ['HelloWorld', 'hello_world'];
        yield ['hello world-test', 'hello_world_test'];
        yield ['HELLO?!#World-Test', 'hello_world_test'];
    }

    /**
     * @dataProvider dataCamel
     */
    public function testCamel(string $original, string $expected): void
    {
        $str = Str::of($original);
        $camelStr = $str->camel();

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $camelStr->value());
    }

    public static function dataCamel(): \Generator
    {
        yield ['Hello-world', 'helloWorld'];
        yield ['Hello---world', 'helloWorld'];
        yield ['hello_world test', 'helloWorldTest'];
        yield ['HELLO_World? Test', 'helloWorldTest'];
    }

    /**
     * @dataProvider dataKebab
     */
    public function testKebab(string $original, string $expected): void
    {
        $str = Str::of($original);
        $kebabStr = $str->kebab();

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $kebabStr->value());
    }

    public static function dataKebab(): \Generator
    {
        yield ['HelloWorld', 'hello-world'];
        yield ['hello_world test', 'hello-world-test'];
        yield ['hello__world? test', 'hello-world-test'];
        yield ['HELLO__World? Test', 'hello-world-test'];
    }

    /**
     * @dataProvider dataSubstr
     */
    public function testSubstr(string $original, int $start, ?int $length, string $expected): void
    {
        $str = Str::of($original);
        $subStr = null === $length ? $str->substr($start) : $str->substr($start, $length);

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $subStr->value());
    }

    public static function dataSubstr(): \Generator
    {
        yield ['Hello', 2, null, 'llo'];
        yield ['Héllo', 1, null, 'éllo'];
        yield ['Hello world test', 6, 5, 'world'];
    }

    /**
     * @dataProvider dataPad
     */
    public function testPad(string $original, int $length, string $padString, int $type, $expected): void
    {
        $str = Str::of($original);
        $paddedStr = $str->pad($length, $padString, $type);

        $this->assertSame($expected, $str->value());
        $this->assertSame($expected, $paddedStr->value());
    }

    public static function dataPad(): \Generator
    {
        yield ['test', 7, '_', STR_PAD_RIGHT, 'test___'];
        yield ['test', 1, '_', STR_PAD_RIGHT, 'test'];
        yield ['hello world', 14, '#', STR_PAD_LEFT,  '###hello world'];
        yield ['hello world', 8, '#', STR_PAD_LEFT,  'hello world'];
        yield ['test universe', 20, '-', STR_PAD_BOTH,  '---test universe----'];
        yield ['test universe', 10, '-', STR_PAD_BOTH,  'test universe'];
    }

    public function testEquals(): void
    {
        $this->assertTrue(Str::of('test')->equals('test'));
        $this->assertTrue(Str::of('test')->equals(Str::of('test')));
        $this->assertTrue(Str::of('test')->equals(StrImmutable::of('test')));
        $this->assertFalse(Str::of('test')->equals('Test'));
    }

    public function testJsonSerialize(): void
    {
        $this->assertSame('"test"', json_encode(Str::of('test')));
    }
}
