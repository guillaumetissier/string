<?php

declare(strict_types=1);

namespace Guillaumetissier\String;

final class StrImmutable implements StrInterface, \JsonSerializable
{
    private function __construct(private readonly string $value)
    {
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
        return new self(trim($this->value, $characters));
    }

    public function lower(): self
    {
        return new self(mb_strtolower($this->value, 'UTF-8'));
    }

    public function upper(): self
    {
        return new self(mb_strtoupper($this->value, 'UTF-8'));
    }

    public function lowerFirst(): self
    {
        return new self(mb_lcfirst($this->value, 'UTF-8'));
    }

    public function upperFirst(): self
    {
        return new self(mb_ucfirst($this->value, 'UTF-8'));
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
        return new self(str_replace($search, $replace, $this->value));
    }

    /**
     * Creates a URL-friendly slug.
     */
    public function slug(string $separator = '-'): self
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT', $this->value);
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', $separator, $value);
        $value = trim($value, $separator);

        return new self($value ?? '');
    }

    public function snake(): self
    {
        $value = preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->value);
        $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
        $value = strtolower($value);
        $value = trim($value, '_');

        return new self($value ?? '');
    }

    public function camel(): self
    {
        $value = preg_replace('/[^a-zA-Z0-9]+/', ' ', $this->value);
        $value = ucwords(strtolower($value));
        $value = lcfirst(str_replace(' ', '', $value));

        return new self($value);
    }

    public function kebab(): self
    {
        $value = preg_replace('/([a-z])([A-Z])/', '$1-$2', $this->value);
        $value = preg_replace('/[^a-zA-Z0-9]+/', '-', $value);
        $value = strtolower($value);
        $value = trim($value, '-');

        return new self($value ?? '');
    }

    public function substr(int $start, ?int $length = null): self
    {
        $value = mb_substr($this->value, $start, $length, 'UTF-8');

        return new self($value);
    }

    public function pad(int $length, string $padString = ' ', int $type = STR_PAD_RIGHT): self
    {
        $currentLength = mb_strlen($this->value, 'UTF-8');

        if ($currentLength >= $length) {
            return new self($this->value);
        }

        $padLength = $length - $currentLength;
        $pad = str_repeat($padString, (int) ceil($padLength / strlen($padString)));
        $pad = substr($pad, 0, $padLength);

        return match ($type) {
            STR_PAD_LEFT => new self($pad.$this->value),
            STR_PAD_BOTH => new self(
                substr($pad, 0, intdiv($padLength, 2))
                .$this->value
                .substr($pad, intdiv($padLength, 2))
            ),
            default => new self($this->value.$pad),
        };
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
