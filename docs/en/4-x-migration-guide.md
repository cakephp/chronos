# 4.x Migration Guide

Chronos 4.x contains breaking changes that could impact your application. This
guide provides an overview of the breaking changes made in 4.x

## diff() and fromNow() return ChronosInterval

`Chronos::diff()`, `ChronosDate::diff()` and `Chronos::fromNow()` now return a
`ChronosInterval` instead of a `DateInterval`. `Chronos::fromNow()` previously
returned `DateInterval|false`; it now always returns a `ChronosInterval`.

`ChronosInterval` decorates the native `DateInterval` and exposes the same
properties (`y`, `m`, `d`, `h`, `i`, `s`, `f`, `invert`, `days`), so most code
keeps working unchanged. However, it does **not** extend `DateInterval`: code
that type-hints `DateInterval` or relies on `instanceof DateInterval` against
the result must call `->toNative()` to get the underlying `DateInterval`:

```php
// Before (3.x)
$interval = $first->diff($second); // DateInterval

// After (4.x), when a native DateInterval is required
$interval = $first->diff($second)->toNative(); // DateInterval
```

In return, the result gains ISO 8601 duration formatting and a number of
convenience methods. See [Working with Intervals](/index#working-with-intervals)
for the full overview.
