# Usage Examples

## Basic: Settings facade

```php
use Yii;

// Store
Yii::$app->get('settings')->set('myapp', 'theme', 'dark');
Yii::$app->get('settings')->set('myapp', [
    'lang' => 'ru',
    'timezone' => 'Europe/Moscow',
]);

// Retrieve single value
$theme = Yii::$app->get('settings')->get('myapp', 'theme');

// Retrieve all values for module
$all = Yii::$app->get('settings')->get('myapp');

// Delete single value
Yii::$app->get('settings')->delete('myapp', 'theme');

// Delete all values for module
Yii::$app->get('settings')->delete('myapp');
```

## AbstractSettingsModel (type-safe)

```php
use proweb\settings\models\AbstractSettingsModel;

class SiteSettings extends AbstractSettingsModel
{
    public string $name = 'My Site';
    public string $adminEmail = '';
    public string $timezone = 'UTC';

    public function rules(): array
    {
        return [
            [['name', 'adminEmail'], 'required'],
            ['adminEmail', 'email'],
            ['timezone', 'timezone'],
        ];
    }
}
```

Usage:

```php
// Load (reads from store automatically in init())
$settings = new SiteSettings();

// Modify
$settings->name = 'New Name';
$settings->adminEmail = 'admin@example.com';

// Save (validates + writes to store)
$settings->save();
```

`AbstractSettingsModel::module()` returns the FQCN by default.
`AbstractSettingsModel::store()` resolves `Yii::$app->get('settings')`.

## Module config examples

### Database store (default)

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

### PHP file store

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

### YAML store

```php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => \proweb\settings\Module::class,
            'storeClass' => \proweb\settings\stores\YamlSettingsStore::class,
            'storeConfig' => [
                'filename' => __DIR__ . '/config/settings.yml',
            ],
        ],
    ],
];
```

### Custom store instance

```php
$store = new \proweb\settings\stores\DbSettingsStore([
    'db' => 'db',
    'tableName' => '{{%app_settings}}',
    'format' => 'string',
    'autoCreateTable' => false,
]);

return [
    'components' => [
        'settingsStore' => $store,
    ],
];
```

## Known limitations

- Events (`BeforeSaveEvent`, `AfterSaveEvent`) are defined but not
  dispatched yet (see TODO.md)
- Behaviors (`CacheBehavior`, `LogBehavior`) exist but are not wired
  into the Settings facade
- `SerializeSettingsStore` uses `unserialize()` with
  `allowed_classes = true` (security concern, see TODO.md)
