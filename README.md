
# guillaumetissier/string

PHP string utilities providing both **mutable** and **immutable** string objects with UTF-8 safe helpers.

This library offers two complementary string abstractions:

* `StrImmutable` — an immutable string value object
* `Str` — a mutable string helper with fluent API

Both share the same transformation methods and behavior.

## Requirements

* PHP 8.1 or higher
* `ext-iconv`

## Installation

```bash
composer require guillaumetissier/string
```

## Which class should I use?

### StrImmutable (recommended)

Use `StrImmutable` when you want:

* predictability
* value object semantics
* no side effects

```php
use Guillaumetissier\String\StrImmutable;

$original = StrImmutable::of('Hello World');

$slug = $original->slug();

echo $original; // Hello World
echo $slug;     // hello-world
```

Each method returns a **new instance**.

### Str (mutable)

Use `Str` when you want:

* in-place transformations
* fluent chaining without creating new objects
* scripting or performance-oriented usage

```php
use Guillaumetissier\String\Str;

$str = Str::of('Hello World')->lower()->replace(' ', '-');

echo $str; // hello-world
```

All methods modify the internal value and return `$this`.

## Common API

Both classes expose the same public methods.

### Creation & access

```php
::of(string $value)
->value(): string
(string) $str
```

### Length & state

```php
->length(): int          // UTF-8 safe
->isEmpty(): bool
->isNotEmpty(): bool
```

### Case & trimming

```php
->trim()
->lower()
->upper()
->lowerFirst()
->upperFirst()
->snake()   // snake_case
->camel()   // camelCase
->kebab()   // kebab-case
```

### Search

```php
->startsWith(string $needle): bool
->endsWith(string $needle): bool
->contains(string $needle): bool
```

### Replace

```php
->replace(string $search, string $replace)
```

### Substrings

```php
->substr(int $start, ?int $length = null)
```

### Padding

```php
->pad(int $length, string $pad = ' ', int $type = STR_PAD_RIGHT)
```

Supports `STR_PAD_LEFT`, `STR_PAD_RIGHT`, `STR_PAD_BOTH`.

### Slug

```php
->slug(string $separator = '-')
```

Creates a URL-friendly ASCII slug.

### Comparison

```php
->equals(string|StrInterface $other): bool
```

### JSON serialization

Both classes implement `JsonSerializable`.

```php
json_encode(StrImmutable::of('test')); // "test"
json_encode(Str::of('test'));          // "test"
```

## Design principles

* No global helper functions
* UTF-8 safe where applicable
* No framework dependency
* Explicit mutability
* Small, focused API

## License

MIT © Guillaume Tissier