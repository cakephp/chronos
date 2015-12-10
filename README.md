# CakePHP Chronos

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://img.shields.io/travis/cakephp/chronos/master.svg?style=flat-square)](https://travis-ci.org/cakephp/chronos)
[![Coverage Status](https://img.shields.io/coveralls/cakephp/chronos/master.svg?style=flat-square)](https://coveralls.io/r/cakephp/chronos?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/chronos.svg?style=flat-square)](https://packagist.org/packages/cakephp/chronos)

Chronos aims to be a drop-in replacement for `nesbot/carbon`. It focuses on providing
immutable date/datetime objects. Immutable objects help ensure that datetime objects
aren't accidentally modified keeping data more predictable.

# Installation

Installing with composer:

```
$ composer require cakephp/chronos
```

You can then use Chronos:

```php
<?php
require 'vendor/autoload.php';

use Cake\Chronos\Chronos;

printf("Now: %s", Chronos::now());
```

# Migrating from Carbon

First add `cakephp/chronos` to your `composer.json`:

```shell
php composer.phar require cakephp/chronos
```

By default Chronos includes a compatibility script that creates aliases for the
relevant Carbon classes.  This will let most applications upgrade with very
little effort. If you'd like to permanently update your code, you will
need to update imports and typehints. Assuming `src` contains the files you
want to migrate, we could use the following to update files:

```
# Replace imports
find ./src -type f -exec sed -i '' 's/use Carbon\\CarbonInterface/use Cake\\Chronos\\ChronosInterface/g' {} \;
find ./src -type f -exec sed -i '' 's/use Carbon\\Carbon/use Cake\\Chronos\\Chronos/g' {} \;

# Replace typehints and extensions
find ./src -type f -exec sed -i '' 's/CarbonInterface/ChronosInterface/g' {} \;
find ./src -type f -exec sed -i '' 's/Carbon/Chronos/g' {} \;
```

At this point your code should mostly work as it did before. The biggest
different is that Chronos instances are immutable.

## Immutable Object Changes

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

## Getting Mutable Objects

In the case that you need a mutable instance you can get one:

```php
$time = new Chronos('2015-10-21 16:29:00');
$mutable = $time->toMutable();

$date = new Date('2015-10-21');
$mutable = $date->toMutable();
```

## Converting Mutable Objects into Immutable ones.

If you have a mutable object and want an immutable variant you can do the following:

```php
$time = new MutableDateTime('2015-10-21 16:29:00');
$fixed = $time->toImmutable();

$date = new MutableDate('2015-10-21');
$fixed = $date->toImmutable();
```

# Calendar Dates

PHP only offers datetime objects as part of the native extensions. Chronos
adds a number of conveniences to the traditional DateTime object and introduces
a `Date` object. `Date` instances offer compatibility with the `ChronosInterface`, but
have their time & timezone frozen to `00:00:00 UTC`. This makes them ideal when working with
calendar dates as the time components will always match.

```php
use Cake\Chronos\Date;

$today = new Date();
echo $today;
// Outputs '2015-10-21'

echo $today->modify('+3 hours');
// Outputs '2015-10-21'
```

Like instances of `Chronos`, `Date` objects are also *immutable*. The `MutableDate` class provides
a mutable variant of `Date`.

# API Documentation

API documentation can be found on [api.cakephp.org/chronos](http://api.cakephp.org/chronos).
