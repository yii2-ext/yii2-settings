# Testing Guide

This document explains how to run tests for the yii2-settings extension.

## Prerequisites

- PHP 8.3 or higher
- Composer
- SQLite extension (for database tests)
- YAML extension (optional, for YAML store tests)

## Setup

### Install Dependencies

```bash
composer install
```

### Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` with your configuration:

```env
YII_ENV=dev
YII_DEBUG=true
DB_DSN=sqlite:runtime/test.db
```

## Running Tests

### PHPUnit

Run all tests:

```bash
composer test
```

Run specific test suite:

```bash
vendor/bin/phpunit --testsuite Database
vendor/bin/phpunit --testsuite FileStore
vendor/bin/phpunit --testsuite PhpStore
vendor/bin/phpunit --testsuite SerializeStore
vendor/bin/phpunit --testsuite YamlStore
```

Run specific test file:

```bash
vendor/bin/phpunit tests/DbSettingsTest.php
vendor/bin/phpunit tests/FileSettingsTest.php
```

Run specific test method:

```bash
vendor/bin/phpunit --filter testSetAndGet
vendor/bin/phpunit --filter testSaveAndLoad
```

### Code Style

Check code style:

```bash
composer cs-check
```

Fix code style:

```bash
composer cs-fix
```

### Static Analysis

Run PHPStan:

```bash
composer phpstan
```

### Refactoring

Check with Rector:

```bash
composer rector
```

Fix with Rector:

```bash
composer rector-fix
```

### Mutation Testing

Run Infection:

```bash
composer mutation
```

Run with static analysis:

```bash
composer mutation-static
```

### Dependency Check

Check for missing dependencies:

```bash
composer check-dependencies
```

## Test Structure

```text
tests/
├── AbstractTestCase.php        # Base test class
├── DbSettingsTest.php          # Database store tests
├── FileSettingsTest.php        # File store tests
├── PhpSettingsTest.php         # PHP store tests
├── SerializeSettingsTest.php   # Serialize store tests
├── YamlSettingsTest.php        # YAML store tests
├── TestModel.php               # Test model
└── bootstrap.php               # Test bootstrap
```

## Writing Tests

### Creating a Test

```php
<?php

declare(strict_types=1);

namespace proweb\settings\tests;

use yii\base\InvalidConfigException;

class MyTest extends AbstractTestCase
{
    public function testSomething(): void
    {
        // Arrange
        $expected = 'test value';

        // Act
        $result = 'test value';

        // Assert
        $this->assertEquals($expected, $result);
    }
}
```

### Database Tests

```php
<?php

declare(strict_types=1);

namespace proweb\settings\tests;

use yii\base\InvalidConfigException;

class DbSettingsTest extends AbstractTestCase
{
    public function testDbConnection(): void
    {
        $module = $this->getModule();
        $this->assertNotNull($module);
    }

    public function testSetAndGet(): void
    {
        $module = $this->getModule();
        $module->set('test.key', 'test.value');

        $value = $module->get('test.key');
        $this->assertEquals('test.value', $value);
    }
}
```

### Mocking

```php
<?php

declare(strict_types=1);

namespace proweb\settings\tests;

use yii\base\InvalidConfigException;
use yii\mockery\MockTrait;

class MyTest extends AbstractTestCase
{
    use MockTrait;

    public function testWithMock(): void
    {
        $mock = $this->createMock(\yii\db\Connection::class);
        $mock->method('createCommand')
            ->willReturn($this->createMock(\yii\db\Command::class));

        // Use mock in your test
    }
}
```

## Test Configuration

### PHPUnit Configuration

The `phpunit.xml` file is already configured:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php"
         colors="true"
         verbose="true"
         stopOnFailure="false">
    <testsuites>
        <testsuite name="All">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory suffix=".php">src</directory>
        </include>
    </coverage>
</phpunit>
```

### PHPStan Configuration

The `phpstan.neon` file is configured for level 8:

```neon
includes:
    - vendor/phpstan/phpstan-phpunit/extension.neon

parameters:
    level: 8
    paths:
        - src
        - tests
    bootstrapFiles:
        - tests/bootstrap.php
```

## Continuous Integration

Tests are automatically run via GitHub Actions on:

- Pull requests
- Pushes to main/master branches

The CI pipeline runs:

1. Code style check (PHP-CS-Fixer)
2. Static analysis (PHPStan)
3. Refactoring check (Rector)
4. Unit tests (PHPUnit)
5. Mutation testing (Infection)

## Coverage

Generate coverage reports:

```bash
vendor/bin/phpunit --coverage-html=coverage
vendor/bin/phpunit --coverage-clover=coverage.xml
vendor/bin/phpunit --coverage-text
```

View HTML coverage report:

```bash
open coverage/index.html
```

## Troubleshooting

### Database Tests Fail

If database tests fail:

1. Check SQLite is installed: `php -m | grep sqlite`
2. Remove test database: `rm runtime/test.db`
3. Run migrations: `php yii migrate`

### YAML Tests Fail

If YAML tests fail:

1. Check YAML extension: `php -m | grep yaml`
2. Install if missing: `composer require ext-yaml`

### Memory Limit

If tests run out of memory:

```bash
php -d memory_limit=-1 vendor/bin/phpunit
```

### Slow Tests

If tests are slow:

1. Run specific test suites instead of all tests
2. Use `--filter` to run specific tests
3. Check for database locking issues
