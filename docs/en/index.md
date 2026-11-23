# Chronos

Chronos provides a zero-dependency `DateTimeImmutable` extension, Date-only and Time-only classes:

* `Cake\Chronos\Chronos` extends `DateTimeImmutable` and provides many helpers.
* `Cake\Chronos\ChronosDate` represents calendar dates unaffected by time or time zones.
* `Cake\Chronos\ChronosTime` represents clock times independent of date or time zones.
* Only safe, immutable objects.
* A pluggable translation system. Only English translations are included in the
  library. However, `cakephp/i18n` can be used for full language support.

The `Chronos` class extends `DateTimeImmutable` and implements `DateTimeInterface`
which allows users to use type declarations that support either.

`ChronosDate` and `ChronosTime` do not extend `DateTimeImmutable` and do not
share an interface. However, they can be converted to a `DateTimeImmutable` instance
using `toDateTimeImmutable()`.

## Installation

To install Chronos, you should use `composer`. From your
application's ROOT directory (where composer.json file is located) run the
following:

```bash
composer require "cakephp/chronos:^3.0"
```

## Working with Immutable Objects

Chronos provides only *immutable* objects.

If you've used PHP `DateTimeImmutable` and `DateTime` classes, then you understand
the difference between *mutable* and *immutable* objects.

Immutable objects create copies of an object each time a change is made. Because modifier methods
around datetimes are not always easy to identify, data can be modified accidentally
or without the developer knowing. Immutable objects prevent accidental changes
to data, and make code free of order-based dependency issues. Immutability does
mean that you will need to remember to replace variables when using modifiers:

```php
// This code doesn't work with immutable objects
$chronos->addDay(1);
doSomething($chronos);
return $chronos;

// This works like you'd expect
$chronos = $chronos->addDay(1);
$chronos = doSomething($chronos);
return $chronos;
```

By capturing the return value of each modification your code will work as
expected.
