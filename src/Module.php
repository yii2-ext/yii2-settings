<?php

declare(strict_types=1);

namespace proweb\\settings;

use Yii;
use yii\base\Module as BaseModule;

/**
 * Модуль настроек приложения.
 *
 * @property-read Settings $settings публичный фасад-сервис
 * @property-read stores\SettingsStoreInterface $store внутреннее хранилище
 *
 * @since 5.0.0
 */
class Module extends BaseModule
{
    /** @var string класс фасада-сервиса */
    public string $settingsClass = Settings::class;

    /** @var string класс хранилища */
    public string $storeClass = stores\DbSettingsStore::class;

    /** @var array<string, mixed> конфигурация хранилища */
    public array $storeConfig = [];

    private ?Settings $_settings = null;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        $this->id = 'settings';
        $this->registerComponents();
    }

    /**
     * Регистрирует компоненты (фасад + хранилище) в приложении.
     */
    public function registerComponents(): void
    {
        if (Yii::$app !== null && !Yii::$app->has('settingsStore')) {
            $storeConfig = array_merge(
                ['class' => $this->storeClass],
                $this->storeConfig
            );
            Yii::$app->set('settingsStore', $storeConfig);
        }

        if (Yii::$app !== null && !Yii::$app->has('settings')) {
            /** @var object|array<string, mixed> $store */
            $store = Yii::$app->get('settingsStore');
            Yii::$app->set('settings', [
                'class' => $this->settingsClass,
                'store' => $store,
            ]);
        }
    }

    /**
     * Возвращает фасад-сервис настроек.
     */
    public function getSettings(): Settings
    {
        if (!$this->_settings instanceof Settings) {
            /** @var Settings $settings */
            $settings = Yii::$app !== null ? Yii::$app->get('settings') : new Settings();
            $this->_settings = $settings;
        }

        return $this->_settings;
    }
}
