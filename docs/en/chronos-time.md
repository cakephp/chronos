# ChronosTime

`ChronosTime` represents a time of day without any date component. This is useful
for representing things like business hours, schedules, or any scenario where
you need to work with time independently of a specific date.

## Creating Instances

```php
use Cake\Chronos\ChronosTime;

// Current time
$now = ChronosTime::now();

// Parse from string
$time = ChronosTime::parse('14:30:00');
$time = ChronosTime::parse('2:30 PM');

// From a Chronos or DateTimeInterface
$chronos = new Chronos('2015-12-25 14:30:00');
$time = ChronosTime::parse($chronos);

// Special times
$midnight = ChronosTime::midnight();  // 00:00:00
$noon = ChronosTime::noon();          // 12:00:00
$endOfDay = ChronosTime::endOfDay();  // 23:59:59
```

## Getting and Setting Components

```php
$time = ChronosTime::parse('14:30:45.123456');

// Getters
$time->getHours();        // 14
$time->getMinutes();      // 30
$time->getSeconds();      // 45
$time->getMicroseconds(); // 123456

// Setters (return new instance)
$time = $time->setHours(16);
$time = $time->setMinutes(45);
$time = $time->setSeconds(30);
$time = $time->setMicroseconds(0);

// Set all at once
$time = $time->setTime(16, 45, 30, 0);
```

## Hour Boundaries

Jump to the start or end of the current hour:

```php
$time = ChronosTime::parse('14:35:22');

$time->startOfHour();  // 14:00:00
$time->endOfHour();    // 14:59:59
```

## Arithmetic

`ChronosTime` supports adding and subtracting hours, minutes and seconds.
All operations return a new instance (ChronosTime is immutable) and **wrap
around midnight** — adding 2 hours to 23:00 yields 01:00, and subtracting
2 hours from 00:00 yields 22:00. This matches the existing setter
behavior.

```php
$time = ChronosTime::parse('10:20:30');

$time->addHours(2);     // 12:20:30
$time->subHours(2);     // 08:20:30
$time->addMinutes(15);  // 10:35:30
$time->subMinutes(15);  // 10:05:30
$time->addSeconds(30);  // 10:21:00
$time->subSeconds(30);  // 10:20:00

// Wraps around midnight
ChronosTime::parse('23:00:00')->addHours(2);  // 01:00:00
ChronosTime::parse('00:00:00')->subHours(2);  // 22:00:00
```

## Modifying

`modify()` accepts the same kind of relative string PHP's
`DateTimeImmutable::modify()` does, but **only** for time-of-day units
(`hour`, `minute`, `second`, `microsecond`). Date units like `day`,
`week`, `month` or keywords like `next monday` throw
`InvalidArgumentException` — they don't have a meaning on a date-less
value.

```php
$time = ChronosTime::parse('10:20:30');

$time->modify('+2 hours');    // 12:20:30
$time->modify('-30 minutes'); // 09:50:30
$time->modify('+5 seconds');  // 10:20:35

// Throws InvalidArgumentException
$time->modify('+1 day');
$time->modify('next monday');
```

## Differences

Compute the difference between two times as either a `DateInterval` or an
integer count of hours/minutes/seconds.

`diff()` mirrors `DateTimeInterface::diff()` — the default is a **signed**
interval; pass `$absolute = true` to drop the sign. The `diffIn*()`
methods, matching the Chronos convention, default to **absolute** and
return the sign relative to `$this` (positive when the target is later).

```php
$t1 = ChronosTime::parse('10:00:00');
$t2 = ChronosTime::parse('12:30:45');

$interval = $t1->diff($t2);         // DateInterval: 2h 30m 45s
$interval = $t2->diff($t1);         // DateInterval: 2h 30m 45s, invert=1
$interval = $t2->diff($t1, true);   // DateInterval: 2h 30m 45s, invert=0

$t1->diffInHours($t2);              // 2
$t1->diffInMinutes($t2);            // 150
$t1->diffInSeconds($t2);            // 9045

// Signed difference relative to $this
$t1->diffInHours($t2, absolute: false);   //  2
$t2->diffInHours($t1, absolute: false);   // -2

// $other defaults to now()
$time = ChronosTime::parse('10:00:00');
$time->diffInMinutes();                   // minutes between 10:00:00 and now
```

`secondsSinceMidnight()` returns the whole-second offset from the start
of the day:

```php
ChronosTime::parse('12:30:45')->secondsSinceMidnight();  // 45045
```

## Finding closest / smallest / largest

```php
$base = ChronosTime::parse('10:00:00');

$base->closest(
    ChronosTime::parse('09:50:00'),
    ChronosTime::parse('10:30:00'),
    ChronosTime::parse('11:00:00'),
);  // 09:50:00

$base->farthest(
    ChronosTime::parse('09:50:00'),
    ChronosTime::parse('10:30:00'),
    ChronosTime::parse('12:00:00'),
);  // 12:00:00

$a = ChronosTime::parse('10:00:00');
$b = ChronosTime::parse('12:00:00');

$a->min($b);  // 10:00:00
$a->max($b);  // 12:00:00
```

`min()` and `max()` default their argument to `ChronosTime::now()` when
called without one.

## Comparisons

ChronosTime provides comparison methods similar to Chronos:

```php
$time1 = ChronosTime::parse('09:00:00');
$time2 = ChronosTime::parse('17:00:00');

$time1->equals($time2);              // false
$time1->greaterThan($time2);         // false
$time1->greaterThanOrEquals($time2); // false
$time1->lessThan($time2);            // true
$time1->lessThanOrEquals($time2);    // true

// Check if between two times
$lunchTime = ChronosTime::parse('12:30:00');
$lunchTime->between($time1, $time2); // true
```

## Special Time Checks

```php
$time = ChronosTime::parse('00:00:00');

$time->isMidnight();    // true (00:00:00)
$time->isStartOfDay();  // true (alias for midnight)
$time->isMidday();      // false (checks for 12:00:00)
$time->isEndOfDay();    // false (checks for 23:59:59)
```

## Formatting

```php
$time = ChronosTime::parse('14:30:45');

// Default format (H:i:s)
echo $time;  // "14:30:45"

// Custom format
echo $time->format('g:i A');  // "2:30 PM"
echo $time->format('H:i');    // "14:30"
```

### Custom Default Format

You can change the default string format:

```php
ChronosTime::setToStringFormat('g:i A');
echo ChronosTime::parse('14:30:00');  // "2:30 PM"

// Reset to default
ChronosTime::resetToStringFormat();
```

## Converting to Array

```php
$time = ChronosTime::parse('14:30:45.123456');
$array = $time->toArray();
// [
//     'hour' => 14,
//     'minute' => 30,
//     'second' => 45,
//     'microsecond' => 123456,
// ]
```

## Converting to DateTime

If you need a full datetime, you can convert to `DateTimeImmutable`:

```php
$time = ChronosTime::parse('14:30:00');

// Converts to today at the given time
$datetime = $time->toDateTimeImmutable();

// With specific timezone
$datetime = $time->toDateTimeImmutable('America/New_York');

// Alias
$datetime = $time->toNative();
```

## Use Cases

### Business Hours

```php
$openTime = ChronosTime::parse('09:00:00');
$closeTime = ChronosTime::parse('17:00:00');
$now = ChronosTime::now();

if ($now->between($openTime, $closeTime)) {
    echo "We're open!";
}
```

### Scheduling

```php
$meetingTime = ChronosTime::parse('14:00:00');
$currentTime = ChronosTime::now();

if ($currentTime->lessThan($meetingTime)) {
    echo "Meeting hasn't started yet";
} elseif ($currentTime->equals($meetingTime)) {
    echo "Meeting is starting now!";
} else {
    echo "Meeting has started";
}
```
