# Installation Guide

This guide explains how to install the yii2-settings extension in your Yii2 application.

## Requirements

- PHP 8.3 or higher
- Yii2 2.0.54+ or 22.x
- Composer

## Installation

### Via Composer

```bash
composer require proweb/yii2-settings
```

### Post-Installation Setup

After installing the package, Yii2 will automatically detect the extension
through its `composer.json` configuration. The module will be registered
automatically when you call `Yii::$app->getModule('settings')`.

## Configuration

### Basic Configuration

Add the following to your `config/web.php` (or `config/main.php`):

```php
return [
    'modules' => [
        'settings' => [
            'class' => 'proweb\settings\Module',
            // Configuration options...
        ],
    ],
];
```

### Bootstrap Configuration

To automatically load settings on application startup, add the bootstrap class:

```php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => 'proweb\settings\Module',
        ],
    ],
];
```

### Database Configuration

Make sure you have a database connection configured:

```php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:' . __DIR__ . '/../runtime/settings.db',
            'charset' => 'utf8',
        ],
    ],
];
```

## Database Migration

After configuration, run the database migration to create the settings table:

```bash
php yii migrate --interactive=0
```

Or run all migrations:

```bash
php yii migrate
```

## Verification

To verify the installation is working correctly, you can check if the module is loaded:

```php
$module = Yii::$app->getModule('settings');
if ($module !== null) {
    echo "Settings module is installed and configured!";
}
```

## Troubleshooting

### Module Not Found

If you get a "module not found" error:

1. Check that the package is installed: `composer show proweb/yii2-settings`
2. Verify the module configuration in your config file
3. Ensure the autoload configuration is correct

### Database Errors

If you encounter database errors:

1. Run the migration: `php yii migrate`
2. Check your database configuration
3. Verify the database connection is working

### Permission Issues

If you have permission issues:

1. Ensure the runtime directory is writable
2. Check file permissions for database files
3. Verify your web server has appropriate permissions
