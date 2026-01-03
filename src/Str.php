<?php

declare(strict_types=1);

namespace Guillaumetissier\String;

final class Str implements StrInterface, \JsonSerializable
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function length(): int
    {
        return mb_strlen($this->value, 'UTF-8');
    }

    public function isEmpty(): bool
    {
        return '' === $this->value;
    }

    public function trim(string $characters = " \t\n\r\0\x0B"): self
    {
        $this->value = trim($this->value, $characters);

        return $this;
    }

    public function lower(): self
    {
        $this->value = mb_strtolower($this->value, 'UTF-8');

        return $this;
    }

    public function upper(): self
    {
        $this->value = mb_strtoupper($this->value, 'UTF-8');

        return $this;
    }

    public function lowerFirst(): self
    {
        $this->value = mb_lcfirst($this->value, 'UTF-8');

        return $this;
    }

    public function upperFirst(): self
    {
        $this->value = mb_ucfirst($this->value, 'UTF-8');

        return $this;
    }

    public function startsWith(string $needle): bool
    {
        return '' === $needle || str_starts_with($this->value, $needle);
    }

    public function endsWith(string $needle): bool
    {
        return '' === $needle || str_ends_with($this->value, $needle);
    }

    public function contains(string $needle): bool
    {
        return '' === $needle || str_contains($this->value, $needle);
    }

    public function replace(string $search, string $replace): self
    {
        $this->value = str_replace($search, $replace, $this->value);

        return $this;
    }

    public function slug(string $separator = '-'): self
    {
        $v = iconv('UTF-8', 'ASCII//TRANSLIT', $this->value) ?: $this->value;
        $v = preg_replace('/[^a-zA-Z0-9]+/', $separator, $v);
        $v = strtolower(trim($v, $separator));
        $this->value = $v;

        return $this;
    }

    public function snake(): self
    {
        $v = preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->value);
        $v = preg_replace('/[^a-zA-Z0-9]+/', '_', $v);
        $v = strtolower($v);
        $v = trim($v, '_');
        $this->value = $v ?? '';

        return $this;
    }

    public function camel(): self
    {
        $v = preg_replace('/[^a-zA-Z0-9]+/', ' ', $this->value);
        $v = ucwords(strtolower($v));
        $v = lcfirst(str_replace(' ', '', $v));
        $this->value = $v;

        return $this;
    }

    public function kebab(): self
    {
        $v = preg_replace('/([a-z])([A-Z])/', '$1-$2', $this->value);
        $v = preg_replace('/[^a-zA-Z0-9]+/', '-', $v);
        $v = strtolower($v);
        $v = trim($v, '-');
        $this->value = $v ?? '';

        return $this;
    }

    public function substr(int $start, ?int $length = null): self
    {
        $this->value = mb_substr($this->value, $start, $length, 'UTF-8');

        return $this;
    }

    public function pad(int $length, string $padString = ' ', int $type = STR_PAD_RIGHT): self
    {
        $currentLength = mb_strlen($this->value, 'UTF-8');

        if ($currentLength >= $length) {
            return $this;
        }

        $padLength = $length - $currentLength;
        $pad = str_repeat($padString, (int) ceil($padLength / strlen($padString)));
        $pad = substr($pad, 0, $padLength);

        $this->value = match ($type) {
            STR_PAD_LEFT => $pad.$this->value,
            STR_PAD_BOTH => substr($pad, 0, intdiv($padLength, 2))
                .$this->value
                .substr($pad, intdiv($padLength, 2)),
            default => $this->value.$pad,
        };

        return $this;
    }

    public function equals(string|StrInterface $other): bool
    {
        $otherValue = $other instanceof StrInterface ? $other->value() : $other;

        return $this->value === $otherValue;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
