<?php

declare(strict_types=1);

namespace dicr\settings;

use Yii;
use yii\base\Module;

/**
 * Модуль настроек приложения.
 *
 * @property-read Settings $settings публичный фасад-сервис
 * @property-read stores\SettingsStoreInterface $store внутреннее хранилище
 *
 * @since 5.0.0
 */
class Module extends Module
{
    /** @var string ID модуля по умолчанию */
    public string $id = 'settings';

    /** @var string класс фасада-сервиса */
    public string $settingsClass = Settings::class;

    /** @var string класс хранилища */
    public string $storeClass = stores\DbSettingsStore::class;

    /** @var array конфигурация хранилища */
    public array $storeConfig = [];

    private ?Settings $_settings = null;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        $this->registerComponents();
    }

    /**
     * Регистрирует компоненты (фасад + хранилище) в приложении.
     */
    public function registerComponents(): void
    {
        if (!Yii::$app->has('settingsStore')) {
            $storeConfig = array_merge(
                ['class' => $this->storeClass],
                $this->storeConfig
            );
            Yii::$app->set('settingsStore', $storeConfig);
        }

        if (!Yii::$app->has('settings')) {
            Yii::$app->set('settings', [
                'class' => $this->settingsClass,
                'store' => Yii::$app->get('settingsStore'),
            ]);
        }
    }

    /**
     * Возвращает фасад-сервис настроек.
     *
     * @return Settings
     */
    public function getSettings(): Settings
    {
        if ($this->_settings === null) {
            $this->_settings = Yii::$app->get('settings');
        }

        return $this->_settings;
    }
}
