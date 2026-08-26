# yii2-settings

Модуль для хранения настроек приложения в Yii2.

[![PHPUnit][badge-phpunit]][link-build]
[![Mutation Testing][badge-mutation]][link-mutation]
[![PHPStan][badge-phpstan]][link-static]
[![Security][badge-security]][link-security]

<!-- markdownlint-disable MD013 -->
[badge-phpunit]: https://img.shields.io/github/actions/workflow/status/yii2-ext/yii2-settings/build.yml?style=for-the-badge&label=PHPUnit&logo=github
[badge-mutation]: https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyii2-ext%2Fyii2-settings%2Fmain
[badge-phpstan]: https://img.shields.io/github/actions/workflow/status/yii2-ext/yii2-settings/static.yml?style=for-the-badge&label=PHPStan&logo=github
[badge-security]: https://img.shields.io/github/actions/workflow/status/yii2-ext/yii2-settings/security.yml?style=for-the-badge&label=Security&logo=github
<!-- markdownlint-enable MD013 -->
[link-build]: https://github.com/yii2-ext/yii2-settings/actions/workflows/build.yml
[link-mutation]: https://dashboard.stryker-mutator.io/reports/github.com/yii2-ext/yii2-settings/main
[link-static]: https://github.com/yii2-ext/yii2-settings/actions/workflows/static.yml
[link-security]: https://github.com/yii2-ext/yii2-settings/actions/workflows/security.yml

## Features

- Multiple storage backends (Database, File, PHP, Serialize, YAML)
- Type-safe settings models
- Caching support
- Logging support
- Events for before/after save
- Automatic module registration
- Full test coverage

## Documentation

- [Installation Guide](docs/installation.md)
- [Configuration Reference](docs/configuration.md)
- [Usage Examples](docs/examples.md)
- [Testing Guide](docs/testing.md)
- [Development Guide](docs/development.md)

## Requirements

- PHP >= 8.3
- Yii2 >= 2.0.54

## Установка

```bash
composer require proweb/yii2-settings
```

## Быстрый старт

### 1. Конфигурация

```php
// config/web.php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => \proweb\\settings\Module::class,
            'storeClass' => \proweb\\settings\stores\DbSettingsStore::class,
            'storeConfig' => [
                'format' => \proweb\\settings\stores\DbSettingsStore::FORMAT_JSON,
            ],
        ],
    ],
];
```

### 2. Создание модели настроек

```php
use proweb\\settings\models\AbstractSettingsModel;

class SiteSettings extends AbstractSettingsModel
{
    public string $title = '';
    public string $email = '';
    public bool $maintenance = false;

    public function rules(): array
    {
        return [
            [['title', 'email'], 'required'],
            ['email', 'email'],
            ['maintenance', 'boolean'],
        ];
    }
}
```

### 3. Использование

```php
// Загрузка настроек через модель
$settings = new SiteSettings();
echo $settings->title;

// Сохранение настроек
$settings->title = 'New Title';
$settings->save();

// Прямое использование фасада-сервиса
$settings = Yii::$app->get('settings');
$settings->get('app', 'title');
$settings->set('app', ['title' => 'New Title', 'email' => 'admin@example.com']);
```

## Структура модуля

```text
src/
├── Module.php                     # yii\base\Module
├── Bootstrap.php                  # yii\base\BootstrapInterface
├── Settings.php                   # Фасад-сервис (публичный API)
├── stores/
│   ├── SettingsStoreInterface.php # Интерфейс хранилища
│   ├── DbSettingsStore.php        # Хранилище в БД
│   ├── FileSettingsStore.php      # Абстрактное файловое хранилище
│   ├── PhpSettingsStore.php       # Хранилище в PHP-файле
│   ├── SerializeSettingsStore.php # Хранилище через serialize()
│   └── YamlSettingsStore.php      # Хранилище в YAML-файле
├── models/
│   └── AbstractSettingsModel.php  # Абстрактная модель настроек
├── behaviors/
│   ├── CacheBehavior.php          # Кеширование настроек
│   └── LogBehavior.php            # Логирование операций
├── events/
│   ├── BeforeSaveEvent.php        # Событие перед сохранением
│   └── AfterSaveEvent.php         # Событие после сохранения
└── migrations/
    └── m240101_000001_create_settings_table.php
```

## Типы хранилищ

### DbSettingsStore

Настройки хранятся в таблице базы данных.

```php
'storeClass' => \proweb\\settings\stores\DbSettingsStore::class,
'storeConfig' => [
    'format' => \proweb\\settings\stores\DbSettingsStore::FORMAT_JSON,
    'tableName' => '{{%settings}}',
],
```

### PhpSettingsStore

Настройки хранятся в PHP-файле.

```php
'storeClass' => \proweb\\settings\stores\PhpSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/config/settings.php',
],
```

### SerializeSettingsStore

Настройки хранятся в файле через serialize().

```php
'storeClass' => \proweb\\settings\stores\SerializeSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/runtime/settings.ser',
],
```

### YamlSettingsStore

Настройки хранятся в YAML-файле.

```php
'storeClass' => \proweb\\settings\stores\YamlSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/config/settings.yml',
],
```

## События

### BeforeSaveEvent

Событие перед сохранением настроек.

```php
use proweb\\settings\events\BeforeSaveEvent;

Yii::$app->on('settings.beforeSave', function (BeforeSaveEvent $event) {
    if ($event->module === 'app' && $event->name === 'title') {
        $event->value = trim($event->value);
    }
});
```

### AfterSaveEvent

Событие после сохранения настроек.

```php
use proweb\\settings\events\AfterSaveEvent;

Yii::$app->on('settings.afterSave', function (AfterSaveEvent $event) {
    Yii::info("Setting saved: {$event->module}.{$event->name}");
});
```

## Поведения

### CacheBehavior

Кеширование настроек.

```php
'storeConfig' => [
    'behaviors' => [
        'cache' => [
            'class' => \proweb\\settings\behaviors\CacheBehavior::class,
            'cacheComponent' => 'cache',
            'duration' => 3600,
        ],
    ],
],
```

### LogBehavior

Логирование операций с настройками.

```php
'storeConfig' => [
    'behaviors' => [
        'log' => [
            'class' => \proweb\\settings\behaviors\LogBehavior::class,
            'logRead' => false,
            'logWrite' => true,
            'logDelete' => true,
        ],
    ],
],
```

## Миграции

Для использования DbSettingsStore необходимо применить миграции:

```bash
php yii migrate --migrationPath=@vendor/proweb/yii2-settings/src/migrations
```

## Требования

- PHP >= 8.3
- Yii2 >= 2.0.54

## License

[GPL-3.0-or-later](LICENSE)
