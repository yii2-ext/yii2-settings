# Configuration Reference

This document describes all configuration options available for the yii2-settings extension.

## Module Configuration

### Basic Options

```php
return [
    'modules' => [
        'settings' => [
            'class' => 'proweb\settings\Module',
            'db' => 'db',                    // Database connection ID
            'cache' => 'cache',              // Cache component ID (optional)
            'settingsTable' => 'settings',   // Settings table name
        ],
    ],
];
```

### Advanced Options

```php
return [
    'modules' => [
        'settings' => [
            'class' => 'proweb\settings\Module',
            'db' => 'db',
            'cache' => 'cache',
            'settingsTable' => 'settings',
            'enableLogging' => true,         // Enable logging
            'enableCaching' => true,         // Enable caching
            'cacheDuration' => 3600,         // Cache duration in seconds
        ],
    ],
];
```

## Settings Stores

### Database Store

The default store uses the database to store settings:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\DbSettingsStore',
            'db' => 'db',
            'table' => 'settings',
        ],
    ],
];
```

### File Store

Store settings in files:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\FileSettingsStore',
            'path' => __DIR__ . '/runtime/settings',
            'filePermission' => 0644,
        ],
    ],
];
```

### PHP Store

Store settings in PHP files:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\PhpSettingsStore',
            'path' => __DIR__ . '/runtime/settings.php',
        ],
    ],
];
```

### Serialize Store

Store settings using PHP serialization:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\SerializeSettingsStore',
            'path' => __DIR__ . '/runtime/settings.dat',
        ],
    ],
];
```

### YAML Store

Store settings in YAML files (requires ext-yaml):

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\YamlSettingsStore',
            'path' => __DIR__ . '/runtime/settings.yml',
        ],
    ],
];
```

## Behaviors

### Cache Behavior

Enable caching for settings:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\DbSettingsStore',
            'behaviors' => [
                'cache' => [
                    'class' => 'proweb\settings\behaviors\CacheBehavior',
                    'cache' => 'cache',
                    'duration' => 3600,
                ],
            ],
        ],
    ],
];
```

### Log Behavior

Enable logging for settings changes:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\DbSettingsStore',
            'behaviors' => [
                'log' => [
                    'class' => 'proweb\settings\behaviors\LogBehavior',
                    'logCategory' => 'settings',
                ],
            ],
        ],
    ],
];
```

## Events

### Before Save Event

Listen to before save events:

```php
use proweb\settings\events\BeforeSaveEvent;
use yii\base\Event;

Event::on(
    \proweb\settings\models\AbstractSettingsModel::class,
    AbstractSettingsModel::EVENT_BEFORE_SAVE,
    function (BeforeSaveEvent $event) {
        // Handle before save
    }
);
```

### After Save Event

Listen to after save events:

```php
use proweb\settings\events\AfterSaveEvent;
use yii\base\Event;

Event::on(
    \proweb\settings\models\AbstractSettingsModel::class,
    AbstractSettingsModel::EVENT_AFTER_SAVE,
    function (AfterSaveEvent $event) {
        // Handle after save
    }
);
```

## Environment Variables

You can also configure settings through environment variables:

```env
# Database settings
DB_DSN=sqlite:/path/to/database.db
DB_USERNAME=root
DB_PASSWORD=

# Cache settings
CACHE_DRIVER=file
CACHE_DURATION=3600

# Settings specific
SETTINGS_TABLE=settings
SETTINGS_CACHE_ENABLED=true
```

## Performance Considerations

### Caching

For production environments, enable caching to reduce database queries:

```php
return [
    'components' => [
        'settings' => [
            'class' => 'proweb\settings\stores\DbSettingsStore',
            'enableCaching' => true,
            'cacheDuration' => 3600,
        ],
    ],
];
```

### Connection Pooling

For high-traffic applications, consider connection pooling:

```php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=settings',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_PERSISTENT => true,
            ],
        ],
    ],
];
```

## Security Considerations

### Input Validation

Always validate settings values before saving:

```php
$model = new SettingsModel();
$model->load(Yii::$app->request->post());
if ($model->validate()) {
    $model->save();
}
```

### Access Control

Restrict access to settings management:

```php
return [
    'modules' => [
        'settings' => [
            'class' => 'proweb\settings\Module',
            'as access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ],
    ],
];
```

### Sensitive Data

For sensitive settings, consider encryption:

```php
$sensitiveValue = 'my-secret-value';
$encrypted = Yii::$app->security->encryptByKey($sensitiveValue, 'encryption-key');
$settings->set('sensitive_key', $encrypted);
```
