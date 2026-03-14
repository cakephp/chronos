# Contributing to Chronos

Thank you for your interest in contributing to Chronos! This document provides guidelines and instructions for contributing.

## Getting Started

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/chronos.git
   cd chronos
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

## Running Tests

Run the test suite using PHPUnit:

```bash
composer test
```

Or directly:

```bash
vendor/bin/phpunit
```

## Code Style

This project follows the [CakePHP coding standards](https://book.cakephp.org/5/en/contributing/cakephp-coding-conventions.html).

Check for coding standard violations:

```bash
composer cs-check
```

Automatically fix coding standard violations:

```bash
composer cs-fix
```

## Static Analysis

PHPStan is used for static analysis. First, install the tools:

```bash
composer stan-setup
```

Then run the analysis:

```bash
composer stan
```

## Running All Checks

To run tests, coding standards, and static analysis together:

```bash
composer check
```

## Submitting Changes

1. Create a new branch for your changes
2. Make your changes and commit them with clear, descriptive messages
3. Ensure all checks pass (`composer check`)
4. Push to your fork and submit a pull request

## Reporting Issues

- Search existing issues before creating a new one
- Include PHP version, Chronos version, and a minimal reproduction case
- Use clear, descriptive titles

## Documentation

Documentation source files are located in the `docs/` directory. The documentation is published at [book.cakephp.org/chronos](https://book.cakephp.org/chronos/3/en/).
