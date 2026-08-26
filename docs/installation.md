# Installation Guide

## Requirements

- PHP >= 8.3
- Yii2 >= 2.0.54

## Install

```bash
composer require proweb/yii2-settings
```

## Configure

### Option 1: Bootstrap (recommended)

```php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => \proweb\settings\Module::class,
        ],
    ],
];
```

Module registers two components automatically:
- `settingsStore` -- internal store (`DbSettingsStore` by default)
- `settings` -- public facade (`Settings` class)

### Option 2: Custom store class

```php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => \proweb\settings\Module::class,
            'storeClass' => \proweb\settings\stores\PhpSettingsStore::class,
            'storeConfig' => [
                'filename' => __DIR__ . '/runtime/settings.php',
            ],
        ],
    ],
];
```

### Option 3: Manual component registration

```php
return [
    'components' => [
        'settingsStore' => [
            'class' => \proweb\settings\stores\DbSettingsStore::class,
            'db' => 'db',
            'tableName' => '{{%settings}}',
            'format' => 'json',
            'autoCreateTable' => true,
        ],
        'settings' => [
            'class' => \proweb\settings\Settings::class,
            'store' => 'settingsStore',
        ],
    ],
];
```

## Database migration

If using `DbSettingsStore`, either:

1. Set `autoCreateTable = true` (default) -- table created automatically
2. Or run the migration:

```bash
php yii migrate --migrationPath=@vendor/proweb/yii2-settings/src/migrations
```

## Verify

```php
$settings = Yii::$app->get('settings');
$settings->set('myapp', 'theme', 'dark');
$value = $settings->get('myapp', 'theme');
// $value === 'dark'
```
