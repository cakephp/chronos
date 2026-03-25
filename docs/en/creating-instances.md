# Creating Instances

There are many ways to get an instance of Chronos or ChronosDate. There are a number of
factory methods that work with different argument sets.

## Basic Factory Methods

```php
use Cake\Chronos\Chronos;

$now = Chronos::now();
$today = Chronos::today();
$yesterday = Chronos::yesterday();
$tomorrow = Chronos::tomorrow();

// Parse relative expressions
$date = Chronos::parse('+2 days, +3 hours');

// Date and time integer values
$date = Chronos::create(2015, 12, 25, 4, 32, 58);

// Date or time integer values
$date = Chronos::createFromDate(2015, 12, 25);
$date = Chronos::createFromTime(11, 45, 10);

// Parse formatted values
$date = Chronos::createFromFormat('m/d/Y', '06/15/2015');
```

## Creating from Timestamps

You can create Chronos instances from Unix timestamps:

```php
// From integer timestamp
$date = Chronos::createFromTimestamp(1450000000);

// From timestamp with timezone
$date = Chronos::createFromTimestamp(1450000000, 'America/New_York');
```

## Creating from Arrays

You can create instances from an array of date/time components:

```php
$date = Chronos::createFromArray([
    'year' => 2015,
    'month' => 12,
    'day' => 25,
    'hour' => 14,
    'minute' => 30,
    'second' => 0,
]);
```

## Min and Max Values

You can get the minimum and maximum representable dates:

```php
$min = Chronos::minValue(); // Earliest possible date
$max = Chronos::maxValue(); // Latest possible date
```

## Creating from Existing DateTimeInterface

If you already have a `DateTimeInterface` instance, you can convert it to Chronos:

```php
$native = new DateTimeImmutable('2015-12-25 14:30:00');
$chronos = Chronos::instance($native);
```

## Timezone Handling

All factory methods accept a timezone parameter:

```php
// Create with specific timezone
$date = Chronos::now('Asia/Tokyo');
$date = Chronos::today('Europe/London');
$date = Chronos::create(2015, 12, 25, 14, 30, 0, 'America/New_York');
```
