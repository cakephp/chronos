# ChronosDate

PHP provides only datetime classes that combine both date and time parts.
Representing calendar dates can be awkward with `DateTimeImmutable` as it includes
time and timezones, which aren't part of a "date".

Chronos provides `ChronosDate` which allows you to represent dates without time.
The time is always fixed to `00:00:00` and is not affected by the server timezone
or modifier methods.

## Creating Instances

```php
use Cake\Chronos\ChronosDate;

$today = ChronosDate::today();
$yesterday = ChronosDate::yesterday();
$tomorrow = ChronosDate::tomorrow();

// Parse from string
$date = ChronosDate::parse('2015-12-25');

// Create from components
$date = ChronosDate::create(2015, 12, 25);

// Parse with format
$date = ChronosDate::createFromFormat('m/d/Y', '12/25/2015');

// From array
$date = ChronosDate::createFromArray([
    'year' => 2015,
    'month' => 12,
    'day' => 25,
]);
```

## Timezone for "Today"

Although `ChronosDate` uses a fixed internal timezone, you can specify which
timezone to use for determining the current date:

```php
// Takes the current date from Asia/Tokyo timezone
$today = ChronosDate::today('Asia/Tokyo');
```

This is useful when you need "today" in a specific timezone that differs from
the server's timezone.

## Time is Ignored

Time modifications have no effect on ChronosDate:

```php
$today = ChronosDate::today();

// Time modifications are ignored
$today->modify('+1 hour');  // Still the same date

// Outputs just the date: '2015-12-20'
echo $today;
```

## Available Methods

ChronosDate includes most of the same modifier and comparison methods as Chronos,
but without time-related operations:

### Modifiers

```php
$date = ChronosDate::today();

// Add/subtract time periods
$date->addDays(5);
$date->subWeeks(2);
$date->addMonths(3);
$date->addYears(1);

// Jump to boundaries
$date->startOfMonth();
$date->endOfMonth();
$date->startOfYear();
$date->endOfYear();
$date->startOfWeek();
$date->endOfWeek();

// Day of week navigation
$date->next(Chronos::MONDAY);
$date->previous(Chronos::FRIDAY);
$date->firstOfMonth(Chronos::TUESDAY);
$date->lastOfMonth(Chronos::SUNDAY);
```

### Comparisons

```php
$date1 = ChronosDate::parse('2015-12-25');
$date2 = ChronosDate::parse('2015-12-31');

$date1->equals($date2);
$date1->lessThan($date2);
$date1->greaterThan($date2);
$date1->between($start, $end);

// Calendar checks
$date->isToday();
$date->isYesterday();
$date->isTomorrow();
$date->isFuture();
$date->isPast();
$date->isWeekend();
$date->isWeekday();
$date->isMonday();
// etc.
```

### Differences

```php
$date1->diffInDays($date2);
$date1->diffInWeeks($date2);
$date1->diffInMonths($date2);
$date1->diffInYears($date2);
$date1->diffInWeekdays($date2);
$date1->diffInWeekendDays($date2);

// Human readable
$date1->diffForHumans($date2);  // "6 days before"
```

## Extracting Components

```php
$date = ChronosDate::parse('2015-12-25');

$date->year;       // 2015
$date->month;      // 12
$date->day;        // 25
$date->dayOfWeek;  // 5 (Friday)
$date->dayOfYear;  // 359
$date->quarter;    // 4
```

## Converting to Array

```php
$date = ChronosDate::parse('2015-12-25');
$array = $date->toArray();
// [
//     'year' => 2015,
//     'month' => 12,
//     'day' => 25,
//     'dayOfWeek' => 5,
//     'dayOfYear' => 359,
// ]
```

## Converting to DateTime

If you need a full datetime, you can convert to `DateTimeImmutable`:

```php
$date = ChronosDate::parse('2015-12-25');

// Convert with optional timezone
$datetime = $date->toDateTimeImmutable();
$datetime = $date->toDateTimeImmutable('America/New_York');

// Alias
$datetime = $date->toNative();
```
