# CakePHP Chronos

![Build Status](https://github.com/cakephp/chronos/actions/workflows/ci.yml/badge.svg?branch=master)
[![Latest Stable Version](https://img.shields.io/github/v/release/cakephp/chronos?sort=semver&style=flat-square)](https://packagist.org/packages/cakephp/chronos)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/chronos?style=flat-square)](https://packagist.org/packages/cakephp/chronos/stats)
[![Code Coverage](https://img.shields.io/coveralls/cakephp/chronos/master.svg?style=flat-square)](https://coveralls.io/r/cakephp/chronos?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Chronos focuses on providing immutable date/datetime objects.
Immutable objects help ensure that datetime objects aren't accidentally
 modified, keeping data more predictable.

# Installation

Installing with composer:

```
$ composer require cakephp/chronos
```

For details on the (minimum/maximum) PHP version see [version map](https://github.com/cakephp/chronos/wiki#version-map).

# Usage

```php
<?php
require 'vendor/autoload.php';

use Cake\Chronos\Chronos;

printf("Now: %s", Chronos::now());
```

# Differences with nesbot/carbon

Chronos was originally compatible with Carbon but has diverged and no longer
extends the PHP DateTime and DateTimeImmutable classes.

# Immutable Object Changes

Immutable objects have a number of advantages:

1. Using immutable objects is always free of side-effects.
2. Dates and times don't accidentally change underneath other parts of your code.

With those benefits in mind, there are a few things you need to keep in mind
when modifying immutable objects:

```php
// This will lose modifications
$date = new Chronos('2015-10-21 16:29:00');
$date->modify('+2 hours');

// This will keep modifications
$date = new Chronos('2015-10-21 16:29:00');
$date = $date->modify('+2 hours');
```

# Calendar Dates

PHP only offers datetime objects as part of the native extensions. Chronos adds
a number of conveniences to the traditional DateTime object and introduces
a `ChronosDate` object. `ChronosDate` instances their time frozen to `00:00:00` and the timezone
set to the server default timezone. This makes them ideal when working with
calendar dates as the time components will always match.

```php
use Cake\Chronos\ChronosDate;

$today = new ChronosDate();
echo $today;
// Outputs '2015-10-21'

echo $today->modify('+3 hours');
// Outputs '2015-10-21'
```

Like instances of `Chronos`, `ChronosDate` objects are also *immutable*.

# Time-only Values

When you need to work with just times (without dates), use `ChronosTime`:

```php
use Cake\Chronos\ChronosTime;

$time = new ChronosTime('14:30:00');
echo $time->format('g:i A'); // 2:30 PM

// Create from components
$time = ChronosTime::create(14, 30, 0);

// Arithmetic
$later = $time->addHours(2)->addMinutes(15);
```

`ChronosTime` is useful for recurring schedules, business hours, or any scenario
where the date is irrelevant.

# Testing with Chronos

Chronos provides `setTestNow()` to freeze time during testing:

```php
use Cake\Chronos\Chronos;

// Freeze time for predictable tests
Chronos::setTestNow('2024-01-15 10:00:00');

$now = Chronos::now(); // Always 2024-01-15 10:00:00

// Reset to real time
Chronos::setTestNow(null);
```

# PSR-20 Clock Interface

For dependency injection, use `ClockFactory` which implements PSR-20:

```php
use Cake\Chronos\ClockFactory;

$clock = new ClockFactory('UTC');
$now = $clock->now(); // Returns Chronos instance

// In your service
class OrderService
{
    public function __construct(private ClockInterface $clock) {}

    public function createOrder(): Order
    {
        return new Order(createdAt: $this->clock->now());
    }
}
```

# Documentation

A more descriptive documentation can be found at [book.cakephp.org/chronos/3/](https://book.cakephp.org/chronos/3/).

# API Documentation

API documentation can be found on [api.cakephp.org/chronos](https://api.cakephp.org/chronos).
