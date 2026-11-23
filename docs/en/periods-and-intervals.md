# Periods and Intervals

Chronos provides wrapper classes for working with date periods and intervals
that return Chronos instances instead of native PHP DateTime objects.

## ChronosPeriod

`ChronosPeriod` wraps a `DatePeriod` and yields `Chronos` instances when iterating:

```php
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosPeriod;
use DateInterval;
use DatePeriod;

// Create a DatePeriod
$start = new Chronos('2024-01-01');
$end = new Chronos('2024-01-10');
$interval = new DateInterval('P1D'); // 1 day

$datePeriod = new DatePeriod($start, $interval, $end);

// Wrap it with ChronosPeriod
$period = new ChronosPeriod($datePeriod);

// Iterate - each item is a Chronos instance
foreach ($period as $date) {
    echo $date->format('Y-m-d') . "\n";
    // $date is a Chronos instance with all helper methods available
}
```

### Safety Check

ChronosPeriod validates that the interval is not zero, which would cause an
infinite loop:

```php
// This throws InvalidArgumentException
$period = new ChronosPeriod(
    new DatePeriod($start, new DateInterval('PT0S'), $end)
);
```

## ChronosDatePeriod

`ChronosDatePeriod` works the same way but yields `ChronosDate` instances:

```php
use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosDatePeriod;
use DateInterval;
use DatePeriod;

$start = new Chronos('2024-01-01');
$end = new Chronos('2024-01-10');
$interval = new DateInterval('P1D');

$datePeriod = new DatePeriod($start, $interval, $end);
$period = new ChronosDatePeriod($datePeriod);

foreach ($period as $date) {
    // $date is a ChronosDate instance
    echo $date->format('Y-m-d') . "\n";
}
```

## ChronosInterval

`ChronosInterval` is a wrapper around `DateInterval` that provides additional
convenience methods including ISO 8601 duration string formatting.

### Creating Intervals

```php
use Cake\Chronos\ChronosInterval;

// From ISO 8601 duration string
$interval = ChronosInterval::create('P1Y2M3D');
$interval = ChronosInterval::create('PT4H30M');

// From individual components
$interval = ChronosInterval::createFromValues(
    years: 1,
    months: 2,
    days: 3,
    hours: 4,
    minutes: 30,
);

// From relative date string
$interval = ChronosInterval::createFromDateString('1 year + 2 months');
$interval = ChronosInterval::createFromDateString('3 days');

// From existing DateInterval
$native = new DateInterval('P1D');
$interval = ChronosInterval::instance($native);
```

### Formatting

```php
$interval = ChronosInterval::createFromValues(
    years: 1,
    months: 2,
    days: 3,
    hours: 4,
    minutes: 30,
    seconds: 15,
);

// ISO 8601 duration string
echo $interval->toIso8601String();  // "P1Y2M3DT4H30M15S"
echo (string)$interval;             // Same as above

// strtotime-compatible string
echo $interval->toDateString();  // "1 year 2 months 3 days 4 hours 30 minutes 15 seconds"

// Standard DateInterval format
echo $interval->format('%y years, %m months, %d days');
```

### Calculations

```php
$interval = ChronosInterval::createFromValues(days: 5, hours: 12);

// Total calculations (approximate for months/years)
echo $interval->totalDays();     // 5
echo $interval->totalSeconds();  // 475200

// Check properties
$interval->isZero();      // false
$interval->isNegative();  // false
```

### Arithmetic

```php
$interval1 = ChronosInterval::createFromValues(days: 5);
$interval2 = ChronosInterval::createFromValues(days: 3);

// Add intervals
$sum = $interval1->add($interval2);  // 8 days

// Subtract intervals
$diff = $interval1->sub($interval2);  // 2 days
```

::: warning
Arithmetic operations perform simple component addition/subtraction without
normalization. For example, 70 minutes stays as 70 minutes rather than being
converted to 1 hour and 10 minutes.
:::

### Accessing Components

You can access the underlying DateInterval properties:

```php
$interval = ChronosInterval::createFromValues(years: 1, months: 6, days: 15);

echo $interval->y;  // 1
echo $interval->m;  // 6
echo $interval->d;  // 15

// Get the underlying DateInterval
$native = $interval->toNative();
```

### Creating Intervals with Chronos

The `Chronos` class also has a helper for creating intervals:

```php
use Cake\Chronos\Chronos;

$interval = Chronos::createInterval(
    years: 1,
    months: 2,
    weeks: 1,  // Converted to days
    days: 3,
    hours: 4,
);
```
