# Configuration

Chronos provides several configuration options for customizing behavior.

## Week Configuration

### Week Start and End

By default, weeks start on Monday (1) and end on Sunday (7). You can customize this:

```php
use Cake\Chronos\Chronos;

// Set week to start on Sunday (7)
Chronos::setWeekStartsAt(Chronos::SUNDAY);

// Set week to end on Saturday (6)
Chronos::setWeekEndsAt(Chronos::SATURDAY);

// Get current settings
$start = Chronos::getWeekStartsAt();  // 7
$end = Chronos::getWeekEndsAt();      // 6
```

This affects methods like `startOfWeek()` and `endOfWeek()`:

```php
Chronos::setWeekStartsAt(Chronos::SUNDAY);

$date = Chronos::parse('2024-01-15'); // Monday
$date->startOfWeek();  // Returns Sunday, January 14
```

### Weekend Days

By default, Saturday and Sunday are considered weekend days. You can customize this:

```php
// Set weekend to Friday and Saturday
Chronos::setWeekendDays([
    Chronos::FRIDAY,
    Chronos::SATURDAY,
]);

// Get current weekend days
$weekendDays = Chronos::getWeekendDays();
```

This affects methods like `isWeekend()` and `isWeekday()`:

```php
Chronos::setWeekendDays([Chronos::FRIDAY, Chronos::SATURDAY]);

$friday = Chronos::parse('2024-01-12'); // Friday
$friday->isWeekend();  // true
$friday->isWeekday();  // false

$sunday = Chronos::parse('2024-01-14'); // Sunday
$sunday->isWeekend();  // false
$sunday->isWeekday();  // true
```

## Day Constants

Chronos provides constants for days of the week:

```php
Chronos::MONDAY;    // 1
Chronos::TUESDAY;   // 2
Chronos::WEDNESDAY; // 3
Chronos::THURSDAY;  // 4
Chronos::FRIDAY;    // 5
Chronos::SATURDAY;  // 6
Chronos::SUNDAY;    // 7
```

## Translation / Localization

Chronos includes a simple translation system for `diffForHumans()` output.
By default, only English translations are included.

### Using with CakePHP I18n

For full localization support, use the `cakephp/i18n` package:

```php
use Cake\Chronos\Chronos;
use Cake\I18n\RelativeTimeFormatter;

// Set up the formatter with CakePHP's i18n
Chronos::diffFormatter(new RelativeTimeFormatter());

// Now diffForHumans() will use localized strings
$date = Chronos::parse('-3 days');
echo $date->diffForHumans();  // Localized output
```

### Custom Formatter

You can implement `DifferenceFormatterInterface` for custom formatting:

```php
use Cake\Chronos\DifferenceFormatterInterface;
use DateTimeInterface;

class MyFormatter implements DifferenceFormatterInterface
{
    public function diffForHumans(
        DateTimeInterface $date,
        ?DateTimeInterface $other = null,
        bool $absolute = false
    ): string {
        // Your custom implementation
    }
}

Chronos::diffFormatter(new MyFormatter());
```

## PSR-20 Clock Interface

Chronos provides `ClockFactory` which implements PSR-20's `ClockInterface`.
This is useful for dependency injection and testing:

```php
use Cake\Chronos\ClockFactory;

$clock = new ClockFactory();
$now = $clock->now();  // Returns DateTimeImmutable
```

The `ClockFactory` respects `Chronos::setTestNow()`, making it useful for testing:

```php
Chronos::setTestNow('2024-01-15 12:00:00');

$clock = new ClockFactory();
$now = $clock->now();  // Returns 2024-01-15 12:00:00
```

### Using in Your Application

```php
use Psr\Clock\ClockInterface;

class MyService
{
    public function __construct(
        private ClockInterface $clock
    ) {}

    public function doSomething(): void
    {
        $now = $this->clock->now();
        // ...
    }
}

// In production
$service = new MyService(new ClockFactory());

// In tests
Chronos::setTestNow('2024-01-15');
$service = new MyService(new ClockFactory());
```
