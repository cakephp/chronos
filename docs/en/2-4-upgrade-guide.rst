2.4 Upgrade Guide
#################

Chronos 2.4 introduces a number of deprecations that will help you prepare your
application for the upcoming Chronos 3.x release. This guide covers the
deprecations introduced in 2.4 and gives a preview of what to expect in 3.0.

No more mutable objects
=======================

Chronos was an early adopter of PHP's immutable datetime objects. With PHP
moving away from mutable datetime objects both ``Cake\Chronos\MutableDate`` and
``Cake\Chronos\MutaleDateTime`` are deprecated and will be removed in 3.0.0.

To upgrade, replace usage of ``MutableDate`` with ``ChronosDate`` and
``MutableDateTime`` with ``Chronos``. When modifying datetimes be sure to
always re-assign the variable with the datetime::

    // Mutate in place
    $datetime->modify('+1 days');

    // Immutable objects must re-assign
    $datetime = $datetime->modify('+1 days');

ChronosInterface deprecated
===========================

Having a consistent interface between date and datetime objects has proven to be
problematic. It created an illusion of compatibility between mutable and
immutable objects and date and datetime objects. Because the
``ChronosInterface`` didn't and can't really deliver on the goals of interfaces
it is deprecated, and will be removed in 3.0. To update your code replace
references to ``ChronosInterface`` with either a reference to
``Cake\Chronos\Chronos`` for datetime instances or ``Cake\Chronos\ChronosDate``
for date instances.

Fewer mutation methods
======================

For historical reasons the chronos classes included many redundant methods. For
example ``addYear()`` and ``addYears()``. In 2.4.0, all of the singular methods
e.g. ``addYear()`` are deprecated. Instead use the plural versions of the
methods e.g. ``addYears()``.

Simpler Date class
==================

When date abstractions were introduced they shared an interfaces with DateTime
classes. This resulted in many no-op methods on dates. For example calling
``setTime()`` on a date would have no effect. In 2.4, all time related methods
(including timezones) are deprecated on date instances. If your application
needs to use the time component of a date, you should use ``Chronos`` instead.

Upcoming removals in 3.0
========================

The following changes will arrive in 3.0, and don't have a simple deprecation
path. Unfortunately these changes will result in hard breaks in 3.0.

Carbon aliases removed
----------------------

When Chronos was started Carbon had no active maintainers. We included
compatiblity aliases in Chronos to help users migrate from the unmaintained
Carbon library to Chronos. Presently, Carbon has active maintainers and we no
longer feel the need to provide shims.

No longer extending DateTime
----------------------------

Historically Chronos has extended PHP's ``DateTime`` classes. This has proven to
be problematic especially for date classes. While Chronos will not extend
PHP's ``DateTime`` classes or implements the ``DateTimeInterface``, if a method does
not emit a deprecation in 2.4.0 it will continue to work in 3.0.

To adapt to this change before upgrading to 3.0 replace references to PHP's
``DateTime`` and ``DateTimeInterface`` and use ``Chronos`` or ``ChronosDate``
instead.
