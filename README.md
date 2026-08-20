# dicr/yii2-settings

Модуль для хранения настроек приложения в Yii2.

## Установка

```bash
composer require dicr/yii2-settings
```

## Быстрый старт

### 1. Конфигурация

```php
// config/web.php
return [
    'bootstrap' => ['settings'],
    'modules' => [
        'settings' => [
            'class' => \dicr\settings\Module::class,
            'storeClass' => \dicr\settings\stores\DbSettingsStore::class,
            'storeConfig' => [
                'format' => \dicr\settings\stores\DbSettingsStore::FORMAT_JSON,
            ],
        ],
    ],
];
```

### 2. Создание модели настроек

```php
use dicr\settings\models\AbstractSettingsModel;

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

```
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
'storeClass' => \dicr\settings\stores\DbSettingsStore::class,
'storeConfig' => [
    'format' => \dicr\settings\stores\DbSettingsStore::FORMAT_JSON,
    'tableName' => '{{%settings}}',
],
```

### PhpSettingsStore

Настройки хранятся в PHP-файле.

```php
'storeClass' => \dicr\settings\stores\PhpSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/config/settings.php',
],
```

### SerializeSettingsStore

Настройки хранятся в файле через serialize().

```php
'storeClass' => \dicr\settings\stores\SerializeSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/runtime/settings.ser',
],
```

### YamlSettingsStore

Настройки хранятся в YAML-файле.

```php
'storeClass' => \dicr\settings\stores\YamlSettingsStore::class,
'storeConfig' => [
    'filename' => '@app/config/settings.yml',
],
```

## События

### BeforeSaveEvent

Событие перед сохранением настроек.

```php
use dicr\settings\events\BeforeSaveEvent;

Yii::$app->on('settings.beforeSave', function (BeforeSaveEvent $event) {
    if ($event->module === 'app' && $event->name === 'title') {
        $event->value = trim($event->value);
    }
});
```

### AfterSaveEvent

Событие после сохранения настроек.

```php
use dicr\settings\events\AfterSaveEvent;

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
            'class' => \dicr\settings\behaviors\CacheBehavior::class,
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
            'class' => \dicr\settings\behaviors\LogBehavior::class,
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
php yii migrate --migrationPath=@vendor/dicr/yii2-settings/src/migrations
```

## Требования

- PHP >= 8.3
- Yii2 >= 2.0.54
