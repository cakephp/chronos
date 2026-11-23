# Comparing Values

Once you have 2 instances of Chronos date/time objects you can compare them in
a variety of ways.

## Comparison Methods

```php
// Full suite of comparators
$first->equals($second);
$first->notEquals($second);
$first->greaterThan($second);
$first->greaterThanOrEquals($second);
$first->lessThan($second);
$first->lessThanOrEquals($second);

// See if the current object is between two others
$now->between($start, $end);

// Find which argument is closest or farthest
$now->closest($june, $november);
$now->farthest($june, $november);

// Get the min or max compared to another date
$now->min($other);  // Returns earlier of the two
$now->max($other);  // Returns later of the two

// Get the average between two dates
$average = $now->average($other);
```

## Calendar Checks

You can inquire about where a given value falls on the calendar:

```php
$now->isToday();
$now->isYesterday();
$now->isTomorrow();
$now->isFuture();
$now->isPast();

// Week checks
$now->isLastWeek();
$now->isNextWeek();

// Month checks
$now->isLastMonth();
$now->isNextMonth();

// Year checks
$now->isLastYear();
$now->isNextYear();

// Half-year checks
$now->isFirstHalf();   // January - June
$now->isSecondHalf();  // July - December

// Leap year
$now->isLeapYear();
```

## Day of Week Checks

```php
// Check if weekend
$now->isWeekend();
$now->isWeekday();

// Check specific days
$now->isMonday();
$now->isTuesday();
$now->isWednesday();
$now->isThursday();
$now->isFriday();
$now->isSaturday();
$now->isSunday();
```

## Relative Time Checks

You can find out if a value was within a relative time period:

```php
$time->wasWithinLast('3 days');
$time->isWithinNext('3 hours');
```

## Generating Differences

In addition to comparing datetimes, calculating differences or deltas between
two values is a common task:

```php
// Get a DateInterval representing the difference
$first->diff($second);

// Get difference as a count of specific units
$first->diffInHours($second);
$first->diffInDays($second);
$first->diffInWeeks($second);
$first->diffInMonths($second);
$first->diffInYears($second);
```

### Absolute vs Relative Differences

By default, diff methods return absolute values. Pass `false` to get signed values:

```php
$past = Chronos::parse('-3 days');
$now = Chronos::now();

$past->diffInDays($now);        // 3 (absolute)
$past->diffInDays($now, false); // -3 (relative, past is negative)
```

### Filtered Differences

Calculate differences with custom filtering:

```php
// Count weekdays between two dates
$start->diffInWeekdays($end);

// Count weekend days between two dates
$start->diffInWeekendDays($end);

// Custom filter
$start->diffInDaysFiltered(function ($date) {
    return $date->isWeekday();
}, $end);
```

## Human-Readable Differences

You can generate human readable differences suitable for use in a feed or
timeline:

```php
// Difference from now
echo $date->diffForHumans();  // "3 hours ago"

// Difference from another point in time
echo $date->diffForHumans($other);  // "1 hour ago"

// Without "ago"/"from now" suffix
echo $date->diffForHumans(null, true);  // "3 hours"
```
