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
