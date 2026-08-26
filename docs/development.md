# Development Guide

This document provides information for developers who want to contribute to or extend the yii2-settings extension.

## Development Setup

### Prerequisites

- PHP 8.3 or higher
- Composer
- Git
- SQLite extension
- YAML extension (optional)

### Getting Started

1. Clone the repository:

```bash
git clone https://github.com/yii2-ext/yii2-settings.git
cd yii2-settings
```

1. Install dependencies:

```bash
composer install
```

1. Set up environment:

```bash
cp .env.example .env
```

1. Run tests to verify setup:

```bash
composer test
```

## Project Structure

```text
yii2-settings/
├── src/                          # Source code
│   ├── Bootstrap.php             # Module bootstrap
│   ├── Module.php                # Main module class
│   ├── Settings.php              # Settings manager
│   ├── behaviors/                # Behaviors
│   │   ├── CacheBehavior.php
│   │   └── LogBehavior.php
│   ├── events/                   # Events
│   │   ├── AfterSaveEvent.php
│   │   └── BeforeSaveEvent.php
│   ├── migrations/               # Database migrations
│   │   └── m240101_000001_create_settings_table.php
│   ├── models/                   # Models
│   │   └── AbstractSettingsModel.php
│   └── stores/                   # Settings stores
│       ├── DbSettingsStore.php
│       ├── FileSettingsStore.php
│       ├── PhpSettingsStore.php
│       ├── SerializeSettingsStore.php
│       ├── SettingsStoreInterface.php
│       └── YamlSettingsStore.php
├── tests/                        # Test files
├── docs/                         # Documentation
├── .github/                      # GitHub configuration
│   ├── dependabot.yml
│   ├── linters/
│   └── workflows/
├── composer.json
├── phpstan.neon
├── phpunit.xml
├── rector.php
└── ecs.php
```

## Code Style

### PHP-CS-Fixer

The project uses PHP-CS-Fixer for code style. Configuration is in `.php-cs-fixer.php`.

Check code style:

```bash
composer cs-check
```

Fix code style:

```bash
composer cs-fix
```

### PHPStan

The project uses PHPStan for static analysis. Configuration is in `phpstan.neon`.

Run static analysis:

```bash
composer phpstan
```

### Rector

The project uses Rector for automated refactoring. Configuration is in `rector.php`.

Check for refactoring opportunities:

```bash
composer rector
```

Apply refactoring:

```bash
composer rector-fix
```

## Adding New Features

### Creating a New Store

1. Create a new class in `src/stores/`:

```php
<?php

declare(strict_types=1);

namespace proweb\settings\stores;

use proweb\settings\Module;

class NewSettingsStore implements SettingsStoreInterface
{
    public function __construct(
        private Module $module
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        // Implementation
    }

    public function set(string $key, mixed $value): void
    {
        // Implementation
    }

    public function delete(string $key): void
    {
        // Implementation
    }

    public function has(string $key): bool
    {
        // Implementation
    }

    public function getAll(): array
    {
        // Implementation
    }

    public function setAll(array $settings): void
    {
        // Implementation
    }
}
```

1. Add tests in `tests/`:

```php
<?php

declare(strict_types=1);

namespace proweb\settings\tests;

class NewSettingsStoreTest extends AbstractTestCase
{
    public function testGetAndSet(): void
    {
        // Test implementation
    }
}
```

1. Update documentation in `docs/configuration.md`.

### Creating a New Behavior

1. Create a new class in `src/behaviors/`:

```php
<?php

declare(strict_types=1);

namespace proweb\settings\behaviors;

use yii\base\Behavior;
use yii\base\Component;

class NewBehavior extends Behavior
{
    public function events(): array
    {
        return [
            Module::EVENT_BEFORE_SAVE => 'beforeSave',
            Module::EVENT_AFTER_SAVE => 'afterSave',
        ];
    }

    public function beforeSave($event): void
    {
        // Implementation
    }

    public function afterSave($event): void
    {
        // Implementation
    }
}
```

1. Add tests in `tests/`.

1. Update documentation.

### Creating a New Model

1. Create a new class extending `AbstractSettingsModel`:

```php
<?php

declare(strict_types=1);

namespace proweb\settings\models;

class NewSettings extends AbstractSettingsModel
{
    public string $setting1 = 'default1';
    public string $setting2 = 'default2';
    public int $setting3 = 0;

    public function rules(): array
    {
        return [
            [['setting1', 'setting2'], 'required'],
            ['setting3', 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'setting1' => 'Setting 1',
            'setting2' => 'Setting 2',
            'setting3' => 'Setting 3',
        ];
    }
}
```

1. Add tests.

1. Update documentation.

## Testing

### Writing Tests

Follow the testing guide in `docs/testing.md`.

### Test Naming Convention

- Test files: `*Test.php`
- Test methods: `test*()`
- Test classes: `*Test extends AbstractTestCase`

### Test Coverage

Aim for high test coverage. Check coverage:

```bash
vendor/bin/phpunit --coverage-html=coverage
```

## Documentation

### Updating Documentation

1. Update relevant files in `docs/`
2. Update `README.md` if needed
3. Update `CHANGELOG.md`

### Documentation Style

- Use clear, concise language
- Provide code examples
- Include both basic and advanced usage
- Keep examples up-to-date

## Pull Requests

### Before Submitting

1. Run all checks:

```bash
composer cs-check
composer phpstan
composer rector
composer test
```

1. Update documentation
1. Update CHANGELOG.md

### PR Title Format

Use conventional commits:

- `feat: add new feature`
- `fix: fix bug`
- `docs: update documentation`
- `test: add tests`
- `refactor: refactor code`

### PR Description

Include:

- Description of changes
- Reason for changes
- Breaking changes (if any)
- Related issues

## Releases

### Versioning

Follow semantic versioning:

- Major: Breaking changes
- Minor: New features (backward compatible)
- Patch: Bug fixes (backward compatible)

### Release Process

1. Update CHANGELOG.md
2. Update version in composer.json
3. Create release tag
4. Publish release

## Code of Conduct

- Be respectful
- Be constructive
- Be inclusive
- Follow project conventions

## Getting Help

- Open an issue
- Join discussions
- Read documentation
- Ask questions
