# Modifying Values

Chronos objects provide modifier methods that let you modify the value in
a granular way. Remember that all Chronos objects are immutable, so modifier
methods return a new instance.

## Setting Components

You can set individual components of the datetime value:

```php
// Set components of the datetime value
$halloween = Chronos::create()
    ->year(2015)
    ->month(10)
    ->day(31)
    ->hour(20)
    ->minute(30);
```

## Relative Modifications

You can modify parts of the datetime relatively:

```php
$future = Chronos::now()
    ->addYears(1)
    ->subMonths(2)
    ->addDays(15)
    ->addHours(20)
    ->subMinutes(2);
```

Available add/sub methods:

- `addYears()` / `subYears()`
- `addMonths()` / `subMonths()`
- `addWeeks()` / `subWeeks()`
- `addDays()` / `subDays()`
- `addWeekdays()` / `subWeekdays()`
- `addHours()` / `subHours()`
- `addMinutes()` / `subMinutes()`
- `addSeconds()` / `subSeconds()`

### Month Overflow Handling

By default, adding months will clamp the day if it would overflow:

```php
// January 31 + 1 month = February 28 (clamped)
$date = Chronos::create(2015, 1, 31)->addMonths(1);
```

If you want to allow overflow:

```php
// January 31 + 1 month = March 3 (overflowed)
$date = Chronos::create(2015, 1, 31)->addMonthsWithOverflow(1);
```

The same applies to years with `addYearsWithOverflow()` and `subYearsWithOverflow()`.

## Jump to Boundaries

You can jump to defined points in time:

```php
$time = Chronos::now();

// Day boundaries
$time->startOfDay();    // 00:00:00
$time->endOfDay();      // 23:59:59

// Month boundaries
$time->startOfMonth();  // First day of month, 00:00:00
$time->endOfMonth();    // Last day of month, 23:59:59

// Year boundaries
$time->startOfYear();   // January 1st, 00:00:00
$time->endOfYear();     // December 31st, 23:59:59

// Week boundaries
$time->startOfWeek();   // Start of week (configurable)
$time->endOfWeek();     // End of week (configurable)

// Decade boundaries
$time->startOfDecade();
$time->endOfDecade();

// Century boundaries
$time->startOfCentury();
$time->endOfCentury();
```

## Day of Week Navigation

Jump to specific days of the week:

```php
$time = Chronos::now();

// Next/previous occurrence of a day
$time->next(Chronos::TUESDAY);
$time->previous(Chronos::MONDAY);

// First/last of month
$time->firstOfMonth();              // First day of month
$time->firstOfMonth(Chronos::FRIDAY); // First Friday of month
$time->lastOfMonth(Chronos::MONDAY);  // Last Monday of month

// Nth occurrence in month
$time->nthOfMonth(2, Chronos::SATURDAY); // 2nd Saturday of month

// Quarter-based
$time->firstOfQuarter();
$time->lastOfQuarter(Chronos::FRIDAY);
$time->nthOfQuarter(3, Chronos::MONDAY);

// Year-based
$time->firstOfYear();
$time->lastOfYear(Chronos::SUNDAY);
$time->nthOfYear(10, Chronos::WEDNESDAY); // 10th Wednesday of the year
```

## Finding Occurrences

Find the next or previous occurrence of a specific day at a specific time:

```php
$now = Chronos::now();

// Find next Monday at 9:00 AM
$nextMeeting = $now->nextOccurrenceOf(Chronos::MONDAY, 9, 0, 0);

// Find previous Friday at 5:00 PM
$lastFriday = $now->previousOccurrenceOf(Chronos::FRIDAY, 17, 0, 0);
```

If the current day matches and the time hasn't passed yet, `nextOccurrenceOf()`
returns today. Similarly, `previousOccurrenceOf()` returns today if the time
has already passed.

## Timezone Modifications

### Converting Timezone

`setTimezone()` converts the datetime to the new timezone, adjusting the time:

```php
$time = new Chronos('2015-12-25 12:00:00', 'UTC');
$tokyo = $time->setTimezone('Asia/Tokyo');
// 2015-12-25 21:00:00 Asia/Tokyo (9 hours later)
```

### Shifting Timezone

`shiftTimezone()` keeps the same wall clock time but changes the timezone:

```php
$time = new Chronos('2015-12-25 12:00:00', 'UTC');
$tokyo = $time->shiftTimezone('Asia/Tokyo');
// 2015-12-25 12:00:00 Asia/Tokyo (same time, different zone)
```

This is useful when you have a datetime that was stored without timezone
information and you need to assign the correct timezone.

## DST Considerations

When modifying dates/times across DST (Daylight Savings Time) transitions,
your operations may gain/lose an additional hour resulting in values that
don't add up. You can avoid these issues by first changing your timezone to
UTC, modifying the time, then converting back:

```php
// Additional hour gained
$time = new Chronos('2014-03-30 00:00:00', 'Europe/London');
debug($time->modify('+24 hours')); // 2014-03-31 01:00:00

// First switch to UTC, modify, then convert back
$time = $time->setTimezone('UTC')
    ->modify('+24 hours')
    ->setTimezone('Europe/London');
```
