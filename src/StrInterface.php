<?php

declare(strict_types=1);

namespace Guillaumetissier\String;

interface StrInterface
{
    public static function of(string $value): self;

    public function value(): string;

    public function __toString(): string;

    public function length(): int;

    public function isEmpty(): bool;

    public function trim(string $characters = " \t\n\r\0\x0B"): self;

    public function lower(): self;

    public function upper(): self;

    public function lowerFirst(): self;

    public function upperFirst(): self;

    public function startsWith(string $needle): bool;

    public function endsWith(string $needle): bool;

    public function contains(string $needle): bool;

    public function replace(string $search, string $replace): self;

    public function slug(string $separator = '-'): self;

    public function snake(): self;

    public function camel(): self;

    public function kebab(): self;

    public function substr(int $start, ?int $length = null): self;

    public function pad(int $length, string $padString = ' ', int $type = STR_PAD_RIGHT): self;

    public function equals(string|StrInterface $other): bool;
}
