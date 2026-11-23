# Formatting and Output

Chronos provides a number of methods for displaying or outputting datetime
objects.

## String Conversion

```php
// Uses the format controlled by setToStringFormat()
echo $date;

// Explicit format
echo $date->format('Y-m-d H:i:s');
```

## Standard Format Methods

```php
echo $time->toAtomString();      // 1975-12-25T14:15:16-05:00
echo $time->toCookieString();    // Thursday, 25-Dec-1975 14:15:16 EST
echo $time->toIso8601String();   // 1975-12-25T14:15:16-05:00
echo $time->toRfc822String();    // Thu, 25 Dec 75 14:15:16 -0500
echo $time->toRfc850String();    // Thursday, 25-Dec-75 14:15:16 EST
echo $time->toRfc1036String();   // Thu, 25 Dec 75 14:15:16 -0500
echo $time->toRfc1123String();   // Thu, 25 Dec 1975 14:15:16 -0500
echo $time->toRfc2822String();   // Thu, 25 Dec 1975 14:15:16 -0500
echo $time->toRfc3339String();   // 1975-12-25T14:15:16-05:00
echo $time->toRfc7231String();   // Thu, 25 Dec 1975 14:15:16 GMT
echo $time->toRssString();       // Thu, 25 Dec 1975 14:15:16 -0500
echo $time->toW3cString();       // 1975-12-25T14:15:16-05:00
```

## Common Format Methods

```php
echo $time->toTimeString();           // 14:15:16
echo $time->toDateString();           // 1975-12-25
echo $time->toDateTimeString();       // 1975-12-25 14:15:16
echo $time->toFormattedDateString();  // Dec 25, 1975
echo $time->toDayDateTimeString();    // Thu, Dec 25, 1975 2:15 PM
```

## Quarter and Week

```php
echo $time->toQuarter();  // 4
echo $time->toWeek();     // 52

// Get the start and end dates of the quarter
[$start, $end] = $time->toQuarterRange();
```

## Converting to Array

You can convert Chronos instances to an array of components:

```php
$time = new Chronos('2015-12-25 14:30:45.123456');
$array = $time->toArray();
// [
//     'year' => 2015,
//     'month' => 12,
//     'day' => 25,
//     'hour' => 14,
//     'minute' => 30,
//     'second' => 45,
//     'microsecond' => 123456,
//     'dayOfWeek' => 5,
//     'dayOfYear' => 359,
//     'timezone' => 'UTC',
// ]
```

This also works with `ChronosDate` and `ChronosTime`:

```php
$date = new ChronosDate('2015-12-25');
$date->toArray();
// ['year' => 2015, 'month' => 12, 'day' => 25, 'dayOfWeek' => 5, 'dayOfYear' => 359]

$time = new ChronosTime('14:30:45.123456');
$time->toArray();
// ['hour' => 14, 'minute' => 30, 'second' => 45, 'microsecond' => 123456]
```

## Extracting Components

You can access parts of a date object directly via properties:

```php
$time = new Chronos('2015-12-31 23:59:58.123456');
$time->year;    // 2015
$time->month;   // 12
$time->day;     // 31
$time->hour;    // 23
$time->minute;  // 59
$time->second;  // 58
$time->micro;   // 123456
```

Other properties that can be accessed:

- `timezone` - The DateTimeZone instance
- `timezoneName` - Timezone name string
- `dayOfWeek` - 1 (Monday) through 7 (Sunday)
- `dayOfMonth` - 1 through 31
- `dayOfYear` - 1 through 366
- `daysInMonth` - 28 through 31
- `timestamp` - Unix timestamp
- `quarter` - 1 through 4
- `half` - 1 or 2

## Customizing Default Format

You can customize the default string format used when casting to string:

```php
Chronos::setToStringFormat('Y-m-d');

echo Chronos::now();  // "2015-12-25"

// Reset to default
Chronos::resetToStringFormat();
```
