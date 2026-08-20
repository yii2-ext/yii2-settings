<?php

declare(strict_types=1);

namespace dicr\settings\stores;

/**
 * Интерфейс хранилища настроек.
 *
 * @since 5.0.0
 */
interface SettingsStoreInterface
{
    /**
     * Получает значение настройки/настроек.
     *
     * @param string $module имя модуля/модели
     * @param string|null $name название настройки
     * @param mixed|null $default значение по умолчанию
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function get(string $module, string $name = null, mixed $default = null): mixed;

    /**
     * Сохраняет значение настройки/настроек.
     *
     * @param string $module название модуля/модели
     * @param array|string $name название параметра или ассоциативный массив
     * @param mixed|null $value значение если name как скаляр
     * @return static
     * @throws \yii\base\Exception
     */
    public function set(string $module, array|string $name, mixed $value = null): static;

    /**
     * Удаляет значение.
     *
     * @param string $module название модуля/модели
     * @param string|null $name название настройки
     * @return static
     * @throws \yii\base\Exception
     */
    public function delete(string $module, ?string $name = null): static;
}
