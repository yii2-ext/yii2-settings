# UPGRADE.md

## Обновление с 4.x на 5.x

### Breaking Changes

1. **Все классы хранилищ перемещены в `proweb\\settings\stores\*`**

   ```php
   // Было (4.x)
   use proweb\\settings\DbSettingsStore;

   // Стало (5.x)
   use proweb\\settings\stores\DbSettingsStore;
   ```

2. **Интерфейс SettingsStore переименован и перемещён**

   ```php
   // Было (4.x)
   class MyStore extends Component implements SettingsStore

   // Стало (5.x)
   class MyStore extends Component implements \proweb\\settings\stores\SettingsStoreInterface
   ```

3. **Модель настроек перемещена в `proweb\\settings\models\*`**

   ```php
   // Было (4.x)
   use proweb\\settings\AbstractSettingsModel;

   // Стало (5.x)
   use proweb\\settings\models\AbstractSettingsModel;
   ```

4. **Behaviors и Events перемещены в нижний регистр**

   ```php
   // Было (4.x)
   use proweb\\settings\Behaviors\CacheBehavior;
   use proweb\\settings\Events\BeforeSaveEvent;

   // Стало (5.x)
   use proweb\\settings\behaviors\CacheBehavior;
   use proweb\\settings\events\BeforeSaveEvent;
   ```

5. **Legacy-интерфейс SettingsStore удалён**

   ```php
   // Было (4.x)
   class MyStore extends Component implements SettingsStore

   // Стало (5.x)
   class MyStore extends Component implements SettingsStoreInterface
   ```

6. **Конфигурация теперь через Module**

   ```php
   // Было (4.x)
   $config = [
       'components' => [
           'settings' => [
               'class' => 'proweb\\settings\DbSettingsStore',
           ],
       ],
   ];

   // Стало (5.x)
   $config = [
       'bootstrap' => ['settings'],
       'modules' => [
           'settings' => [
               'class' => 'proweb\\settings\Module',
               'storeClass' => 'proweb\\settings\stores\DbSettingsStore',
           ],
       ],
   ];
   ```

7. **Требования к PHP и Yii2**

   ```json
   {
     "require": {
       "php": ">=8.3",
       "yiisoft/yii2": "^2.0.54|^22"
     }
   }
   ```

### Новые возможности

1. **Settings.php** - публичный фасад-сервис
2. **Module.php** - интеграция через bootstrap
3. **Events** - BeforeSaveEvent, AfterSaveEvent
4. **Behaviors** - CacheBehavior, LogBehavior
5. **Migrations** - для DbSettingsStore
6. **Улучшенная типизация** - PHP 8.3+ features

### Порядок обновления

1. Обновите `composer.json`:

   ```json
   {
     "require": {
       "php": ">=8.3",
       "yiisoft/yii2": "^2.0.54|^22"
     }
   }
   ```

2. Обновите namespace во всех файлах:
   - `proweb\\settings\DbSettingsStore` → `proweb\\settings\stores\DbSettingsStore`
   - `proweb\\settings\AbstractSettingsModel` → `proweb\\settings\models\AbstractSettingsModel`
   - и т.д.

3. Обновите конфигурацию приложения:

   ```php
   return [
       'bootstrap' => ['settings'],
       'modules' => [
           'settings' => [
               'class' => \proweb\\settings\Module::class,
               'storeClass' => \proweb\\settings\stores\DbSettingsStore::class,
           ],
       ],
   ];
   ```

4. Обновите классы хранилищ (если есть кастомные):
   - Замените `SettingsStore` на `SettingsStoreInterface`
   - Обновите типы возвращаемых значений

5. Примените миграции (если используете DbSettingsStore):

   ```bash
   php yii migrate --migrationPath=@vendor/dicr/yii2-settings/src/migrations
   ```

6. Проверьте работу приложения:

   ```bash
   php yii help
   php yii settings/test
   ```
