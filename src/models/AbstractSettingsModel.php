<?php

declare(strict_types=1);

namespace dicr\settings\models;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Абстрактная модель настроек.
 *
 * Используется как singleton через Model::instance()
 */
abstract class AbstractSettingsModel extends Model
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();

        $this->loadSettings();
    }

    /**
     * Возвращает хранилище настроек.
     * Для переопределения в дочерних реализациях.
     *
     * @return \dicr\settings\Settings
     * @throws InvalidConfigException
     */
    public static function store(): \dicr\settings\Settings
    {
        return Yii::$app->get('settings');
    }

    /**
     * Возвращает название раздела настроек в котором хранятся атрибуты этой модели.
     *
     * @return string
     */
    public static function module(): string
    {
        return static::class;
    }

    /**
     * Загружает настройки из хранилища настроек.
     *
     * @param bool $safeOnly только безопасные атрибуты
     * @return $this
     * @throws Exception
     */
    public function loadSettings(bool $safeOnly = true): static
    {
        $store = static::store();
        $module = static::module();
        $values = $store->get($module);
        $this->setAttributes($values, $safeOnly);

        return $this;
    }

    /**
     * Сохраняет модель в хранилище настроек.
     *
     * @param bool $validate выполнить валидацию
     * @return bool при ошибке валидации возвращает false
     * @throws Exception
     */
    public function save(bool $validate = true): bool
    {
        if ($validate && !$this->validate()) {
            return false;
        }

        $store = static::store();
        $module = static::module();
        $store->set($module, $this->attributes);

        return true;
    }
}
