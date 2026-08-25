# Usage Examples

This document provides practical examples of using the yii2-settings extension.

## Basic Usage

### Storing Simple Settings

```php
use proweb\settings\Module;

// Get the settings module
$settings = Yii::$app->getModule('settings');

// Store a simple value
$settings->set('site.name', 'My Application');
$settings->set('site.description', 'A great application');
$settings->set('site.url', 'https://example.com');

// Retrieve a value
$siteName = $settings->get('site.name');
echo $siteName; // "My Application"
```

### Using Models

Create a settings model for type-safe access:

```php
use proweb\settings\models\AbstractSettingsModel;

class SiteSettings extends AbstractSettingsModel
{
    public string $name = 'My Application';
    public string $description = 'A great application';
    public string $url = 'https://example.com';
    public string $adminEmail = 'admin@example.com';

    public function rules(): array
    {
        return [
            [['name', 'description', 'url', 'adminEmail'], 'required'],
            ['url', 'url'],
            ['adminEmail', 'email'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Site Name',
            'description' => 'Site Description',
            'url' => 'Site URL',
            'adminEmail' => 'Admin Email',
        ];
    }
}
```

### Using the Model

```php
// Load settings
$settings = new SiteSettings();
$settings->load();

// Update settings
$settings->name = 'New Application Name';
$settings->description = 'Updated description';
$settings->save();

// Or load from database
$settings = SiteSettings::findOne(['id' => 1]);
echo $settings->name;
```

## Advanced Usage

### Multiple Stores

Use different stores for different types of settings:

```php
// Database for user settings
$dbSettings = Yii::$app->settings;

// File for application settings
$fileSettings = new \proweb\settings\stores\FileSettingsStore([
    'path' => __DIR__ . '/runtime/app-settings',
]);

// YAML for configuration
$yamlSettings = new \proweb\settings\stores\YamlSettingsStore([
    'path' => __DIR__ . '/config/settings.yml',
]);
```

### Caching

Enable caching for frequently accessed settings:

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

### Logging

Track changes to settings:

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

### Events

Listen to settings changes:

```php
use proweb\settings\events\BeforeSaveEvent;
use proweb\settings\events\AfterSaveEvent;
use yii\base\Event;

// Before save
Event::on(
    \proweb\settings\models\AbstractSettingsModel::class,
    AbstractSettingsModel::EVENT_BEFORE_SAVE,
    function (BeforeSaveEvent $event) {
        $model = $event->sender;
        $model->updated_at = time();
    }
);

// After save
Event::on(
    \proweb\settings\models\AbstractSettingsModel::class,
    AbstractSettingsModel::EVENT_AFTER_SAVE,
    function (AfterSaveEvent $event) {
        $model = $event->sender;
        // Send notification, update cache, etc.
    }
);
```

## Real-World Examples

### Site Configuration

```php
class SiteSettings extends AbstractSettingsModel
{
    public string $name = 'My Site';
    public string $tagline = 'Welcome to my site';
    public string $description = 'A great website';
    public string $keywords = 'php, yii2, web';
    public string $logo = '/images/logo.png';
    public string $favicon = '/images/favicon.ico';
    public string $adminEmail = 'admin@example.com';
    public string $supportEmail = 'support@example.com';
    public string $senderEmail = 'noreply@example.com';
    public string $senderName = 'My Site';
    public string $charset = 'UTF-8';
    public string $timezone = 'UTC';
    public string $language = 'en-US';

    public function rules(): array
    {
        return [
            [['name', 'tagline', 'description', 'adminEmail'], 'required'],
            ['email', 'email'],
            ['url', 'url'],
            ['charset', 'string', 'max' => 10],
            ['timezone', 'timezone'],
            ['language', 'match', 'pattern' => '/^[a-z]{2}-[A-Z]{2}$/'],
        ];
    }
}
```

### Email Settings

```php
class EmailSettings extends AbstractSettingsModel
{
    public string $transportType = 'smtp';
    public string $host = 'smtp.gmail.com';
    public int $port = 587;
    public string $encryption = 'tls';
    public string $username = '';
    public string $password = '';
    public string $fromEmail = 'noreply@example.com';
    public string $fromName = 'My Application';

    public function rules(): array
    {
        return [
            [['transportType', 'host', 'port', 'fromEmail'], 'required'],
            ['port', 'integer', 'min' => 1, 'max' => 65535],
            ['encryption', 'in', 'range' => ['ssl', 'tls', '']],
            ['fromEmail', 'email'],
            ['host', 'filter', 'filter' => 'trim'],
        ];
    }

    public function getTransportConfig(): array
    {
        return [
            'class' => 'yii\swiftmailer\SmtpTransport',
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
```

### Social Media Settings

```php
class SocialSettings extends AbstractSettingsModel
{
    public string $facebook = '';
    public string $twitter = '';
    public string $instagram = '';
    public string $linkedin = '';
    public string $youtube = '';
    public string $telegram = '';

    public function rules(): array
    {
        return [
            [['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram'], 'url', 'isEmpty' => true],
        ];
    }

    public function getActiveAccounts(): array
    {
        $accounts = [];
        foreach (['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram'] as $network) {
            if (!empty($this->$network)) {
                $accounts[$network] = $this->$network;
            }
        }
        return $accounts;
    }
}
```

### API Keys

```php
class ApiSettings extends AbstractSettingsModel
{
    public string $dadataToken = '';
    public string $googleMapsKey = '';
    public string $sendgridKey = '';
    public string $stripeSecret = '';
    public string $stripePublic = '';

    public function rules(): array
    {
        return [
            [['dadataToken', 'googleMapsKey', 'sendgridKey', 'stripeSecret', 'stripePublic'], 'string'],
        ];
    }

    public function isConfigured(string $service): bool
    {
        return !empty($this->$service);
    }
}
```

## Frontend Integration

### Using in Views

```php
use proweb\settings\models\SiteSettings;

$settings = SiteSettings::findOne(['id' => 1]);

?>
<!DOCTYPE html>
<html lang="<?= $settings->language ?>">
<head>
    <meta charset="<?= $settings->charset ?>">
    <meta name="description" content="<?= $settings->description ?>">
    <meta name="keywords" content="<?= $settings->keywords ?>">
    <title><?= $settings->name ?> - <?= $settings->tagline ?></title>
    <link rel="icon" href="<?= $settings->favicon ?>">
</head>
<body>
    <header>
        <img src="<?= $settings->logo ?>" alt="<?= $settings->name ?>">
    </header>
    <!-- Content -->
</body>
</html>
```

### Using in Email Templates

```php
use proweb\settings\models\EmailSettings;

$emailSettings = EmailSettings::findOne(['id' 1]);

Yii::$app->mailer->compose()
    ->setFrom([$emailSettings->fromEmail => $emailSettings->fromName])
    ->setTo($recipientEmail)
    ->setSubject($subject)
    ->setHtmlBody($htmlContent)
    ->send();
```

## Console Commands

### Migration

```bash
# Run all migrations
php yii migrate

# Create new migration
php yii migrate/create create_settings_table

# Revert last migration
php yii migrate/down 1
```

### Custom Commands

Create a custom command to manage settings:

```php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use proweb\settings\Module;

class SettingsController extends Controller
{
    public function actionList(): int
    {
        $module = Yii::$app->getModule('settings');
        $settings = $module->getAllSettings();

        foreach ($settings as $key => $value) {
            echo "{$key}: {$value}\n";
        }

        return ExitCode::OK;
    }

    public function actionSet(string $key, string $value): int
    {
        $module = Yii::$app->getModule('settings');
        $module->set($key, $value);

        echo "Setting '{$key}' updated to '{$value}'\n";
        return ExitCode::OK;
    }

    public function actionGet(string $key): int
    {
        $module = Yii::$app->getModule('settings');
        $value = $module->get($key);

        echo "{$key}: {$value}\n";
        return ExitCode::OK;
    }
}
```

Usage:

```bash
php yii settings/list
php yii settings/set site.name "New Name"
php yii settings/get site.name
```
