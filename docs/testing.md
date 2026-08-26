# Testing Guide

## Setup

```bash
composer install
```

## Running tests

```bash
composer test
```

Run specific suite:

```bash
vendor/bin/phpunit --testsuite DbStore
vendor/bin/phpunit --testsuite PhpStore
vendor/bin/phpunit --testsuite SerializeStore
vendor/bin/phpunit --testsuite YamlStore
```

Run specific file:

```bash
vendor/bin/phpunit tests/DbSettingsTest.php
```

## Test structure

```text
tests/
├── AbstractTestCase.php        # Base class (console app, in-memory SQLite, FileCache)
├── DbSettingsTest.php          # DbSettingsStore
├── FileSettingsTest.php        # FileSettingsStore (abstract)
├── PhpSettingsTest.php         # PhpSettingsStore
├── SerializeSettingsTest.php   # SerializeSettingsStore
├── YamlSettingsTest.php        # YamlSettingsStore (requires ext-yaml)
├── TestModel.php               # Test model for model tests
└── bootstrap.php               # Bootstrap (creates console Application)
```

Each store test inherits `testModel()` from `AbstractTestCase` which tests
`get`, `set`, `delete` through `TestModel`.

## Other checks

```bash
composer cs-check          # Code style (PSR-12 + PER-CS 2.0)
composer cs-fix            # Auto-fix style
composer phpstan           # Static analysis (level 8)
composer rector            # Check for refactoring
composer rector-fix        # Apply refactoring
composer mutation          # Mutation testing (Infection)
composer check-dependencies # Dependency check
```

## Environment notes

- `ext-yaml` may be missing locally:
  `composer install --ignore-platform-req=ext-yaml`
  and skip `tests/YamlSettingsTest.php`
- Default `memory_limit=128M` crashes PHPStan parallel workers.
  Run with `-d memory_limit=-1` or bump the limit.
- PowerShell splits `|` in arguments:
  use `--filter="DbSettingsTest"` (quoted).
