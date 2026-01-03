<?php

declare(strict_types=1);

namespace Guillaumetissier\String\Tests;

use Guillaumetissier\String\Str;
use Guillaumetissier\String\StrImmutable;
use PHPUnit\Framework\TestCase;

final class StrImmutableTest extends TestCase
{
    public function testCreationAndValue(): void
    {
        $str = StrImmutable::of('hello');

        $this->assertSame('hello', $str->value());
        $this->assertSame('hello', (string) $str);
    }

    public function testLength(): void
    {
        $this->assertSame(5, StrImmutable::of('Hello')->length());
        $this->assertSame(5, StrImmutable::of('Héllo')->length());
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(StrImmutable::of('')->isEmpty());
        $this->assertFalse(StrImmutable::of(' ')->isEmpty());
    }

    /**
     * @dataProvider dataTrim
     */
    public function testTrim(string $original, string $expected, ?string $chars = null): void
    {
        $str = StrImmutable::of($original);
        if (null !== $chars) {
            $trimmedStr = $str->trim($chars);
        } else {
            $trimmedStr = $str->trim();
        }

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of('HÉLLO');
        $modified = $str->lower();

        $this->assertSame('HÉLLO', $str->value());
        $this->assertSame('héllo', $modified->value());
    }

    public function testUpper(): void
    {
        $str = StrImmutable::of('héllo');
        $modified = $str->upper();

        $this->assertSame('héllo', $str->value());
        $this->assertSame('HÉLLO', $modified->value());
    }

    public function testLowerFirst(): void
    {
        $str = StrImmutable::of('HÉLLO');
        $modified = $str->lowerFirst();

        $this->assertSame('HÉLLO', $str->value());
        $this->assertSame('hÉLLO', $modified->value());
    }

    public function testUpperFirst(): void
    {
        $str = StrImmutable::of('héllo');
        $modified = $str->upperFirst();

        $this->assertSame('héllo', $str->value());
        $this->assertSame('Héllo', $modified->value());
    }

    public function testStartsWith(): void
    {
        $this->assertTrue(StrImmutable::of('hello world')->startsWith('hello'));
        $this->assertFalse(StrImmutable::of('hello world')->startsWith('world'));
        $this->assertTrue(StrImmutable::of('hello')->startsWith(''));
    }

    public function testEndsWith(): void
    {
        $this->assertTrue(StrImmutable::of('hello world')->endsWith('world'));
        $this->assertFalse(StrImmutable::of('hello world')->endsWith('hello'));
        $this->assertTrue(StrImmutable::of('hello')->endsWith(''));
    }

    public function testContains(): void
    {
        $this->assertTrue(StrImmutable::of('hello world')->contains('lo wo'));
        $this->assertFalse(StrImmutable::of('hello world')->contains('test'));
        $this->assertTrue(StrImmutable::of('hello')->contains(''));
    }

    /**
     * @dataProvider dataReplace
     */
    public function testReplace(string $original, string $search, string $replace, string $expected): void
    {
        $str = StrImmutable::of($original);
        $replacedStr = $str->replace($search, $replace);

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $slugStr = $str->slug();

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $snakeStr = $str->snake();

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $camelStr = $str->camel();

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $kebabStr = $str->kebab();

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $subStr = null === $length ? $str->substr($start) : $str->substr($start, $length);

        $this->assertSame($original, $str->value());
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
        $str = StrImmutable::of($original);
        $paddedStr = $str->pad($length, $padString, $type);

        $this->assertSame($original, $str->value());
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
        $this->assertTrue(StrImmutable::of('test')->equals('test'));
        $this->assertTrue(StrImmutable::of('test')->equals(StrImmutable::of('test')));
        $this->assertTrue(StrImmutable::of('test')->equals(Str::of('test')));
        $this->assertFalse(StrImmutable::of('test')->equals('Test'));
    }

    public function testJsonSerialize(): void
    {
        $this->assertSame('"test"', json_encode(StrImmutable::of('test')));
    }

    public function testImmutability(): void
    {
        $str = StrImmutable::of('test');
        $upper = $str->upper();

        $this->assertSame('test', $str->value());
        $this->assertSame('TEST', $upper->value());
    }
}
