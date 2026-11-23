# Testing Aids

When writing unit tests, it is helpful to fixate the current time. Chronos provides
several methods to mock the current time for testing.

## Setting Test Time

You can fix the current time using `setTestNow()`:

```php
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;

// Fix the current time
Chronos::setTestNow(new Chronos('2015-12-25 00:00:00'));

// Now all "now" calls return the fixed time
$time = Chronos::now();        // 2015-12-25 00:00:00
$time = new Chronos();         // 2015-12-25 00:00:00
$time = new Chronos('1 hour ago'); // 2015-12-24 23:00:00
```

Relative expressions are calculated from the test time:

```php
Chronos::setTestNow(new Chronos('2015-12-25 00:00:00'));

$time = new Chronos('+2 days');  // 2015-12-27 00:00:00
$time = Chronos::parse('yesterday'); // 2015-12-24 00:00:00
```

## Resetting Test Time

To reset the fixation, call `setTestNow()` with no parameter or `null`:

```php
Chronos::setTestNow();
// or
Chronos::setTestNow(null);
```

## Checking Test Mode

You can check if test time is currently set:

```php
if (Chronos::hasTestNow()) {
    // Currently in test mode
}

// Get the current test time (null if not set)
$testTime = Chronos::getTestNow();
```

## Scoped Test Time

For cleaner tests, you can use `withTestNow()` which automatically resets the
test time after your callback completes:

```php
$result = Chronos::withTestNow('2015-12-25 10:00:00', function () {
    // Inside this callback, "now" is fixed to 2015-12-25 10:00:00
    $time = Chronos::now();

    // Do your assertions...
    return $time->hour; // 10
});

// After the callback, test time is automatically reset
// $result contains the return value from the callback
```

This is especially useful in test methods:

```php
public function testChristmasDiscount(): void
{
    Chronos::withTestNow('2015-12-25', function () {
        $order = new Order();
        $this->assertTrue($order->hasChristmasDiscount());
    });

    Chronos::withTestNow('2015-07-04', function () {
        $order = new Order();
        $this->assertFalse($order->hasChristmasDiscount());
    });
}
```

The callback can also accept the test time as a parameter:

```php
Chronos::withTestNow('2015-12-25', function (Chronos $now) {
    echo $now->format('Y-m-d'); // 2015-12-25
});
```

## ChronosDate Test Time

`ChronosDate` has its own separate test time:

```php
use Cake\Chronos\ChronosDate;

ChronosDate::setTestNow(ChronosDate::parse('2015-12-25'));

$today = ChronosDate::today(); // 2015-12-25

// Reset
ChronosDate::setTestNow();
```

## Test Suite Setup

In your test suite's bootstrap process, you might want to set a fixed time
for all tests:

```php
// In tests/bootstrap.php
Chronos::setTestNow(Chronos::now());
ChronosDate::setTestNow(ChronosDate::today());
```

Or use the scoped version in individual test methods or setUp/tearDown:

```php
protected function setUp(): void
{
    parent::setUp();
    Chronos::setTestNow('2015-12-25 12:00:00');
}

protected function tearDown(): void
{
    Chronos::setTestNow();
    parent::tearDown();
}
```
