# Development Guide

## Setup

```bash
git clone https://github.com/yii2-ext/yii2-settings.git
cd yii2-settings
composer install
```

## Project structure

```text
src/
├── Bootstrap.php              # BootstrapInterface implementation
├── Module.php                 # Yii2 module (registers components)
├── Settings.php               # Public facade (implements SettingsStoreInterface)
├── behaviors/
│   ├── CacheBehavior.php      # Cache behavior (not wired in yet)
│   └── LogBehavior.php        # Log behavior (not wired in yet)
├── events/
│   ├── BeforeSaveEvent.php    # Before save event (not dispatched yet)
│   └── AfterSaveEvent.php     # After save event (not dispatched yet)
├── migrations/
│   └── m240101_000001_create_settings_table.php
├── models/
│   └── AbstractSettingsModel.php  # Base model for type-safe settings
└── stores/
    ├── SettingsStoreInterface.php # 3-method contract: get/set/delete
    ├── DbSettingsStore.php        # Database (default)
    ├── FileSettingsStore.php      # Abstract file-based
    ├── PhpSettingsStore.php       # PHP array file
    ├── SerializeSettingsStore.php # serialize() file
    └── YamlSettingsStore.php      # YAML file (ext-yaml)
```

## Code style

- PSR-12 + PER-CS 2.0
- PHP 8.3 migration rules (strict_types, short arrays, alphabetical imports)
- Typed constants (`const string`, `const array`)

```bash
composer cs-check    # Check
composer cs-fix      # Fix
```

## Static analysis

PHPStan level 8 with generics docblocks.

```bash
composer phpstan
```

## Mutation testing

Infection with MSI threshold.

```bash
composer mutation
```

## CI/CD

GitHub Actions workflows:

- **build.yml** -- PHPUnit on PHP 8.3/8.4, Ubuntu + Windows
- **quality.yml** -- CS, PHPStan, Rector, EditorConfig, Prettier, Markdown lint
- **static.yml** -- PHPStan standalone
- **mutation.yml** -- Infection
- **security.yml** -- Gitleaks (Zizmor disabled, upstream vulnerability)
- **dependency-check.yml** -- composer-require-checker

## Adding a new store

1. Create class in `src/stores/`
2. Implement `SettingsStoreInterface` (3 methods)
3. Add test in `tests/`
4. Add to `phpunit.xml` testsuite if needed

Interface signature:

```php
public function get(string $module, ?string $name = null, mixed $default = null): mixed;
public function set(string $module, array|string $name, mixed $value = null): static;
public function delete(string $module, ?string $name = null): static;
```
