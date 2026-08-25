<?php

declare(strict_types=1);

namespace proweb\settings\stores;

/**
 * Интерфейс хранилища настроек.
 *
 * @since 5.0.0
 */
interface SettingsStoreInterface
{
    /**
     * Возвращает значение настройки.
     *
     * @param string $module имя модуля/модели
     * @param string|null $name название настройки
     * @param mixed|null $default значение по умолчанию
     * @throws \yii\base\Exception
     */
    public function get(string $module, ?string $name = null, mixed $default = null): mixed;

    /**
     * Сохраняет значение настройки.
     *
     * @param string $module название модуля/модели
     * @param array<string, mixed>|string $name название параметра или ассоциативный массив
     * @param mixed|null $value значение если name как скаляр
     * @throws \yii\base\Exception
     */
    public function set(string $module, array|string $name, mixed $value = null): static;

    /**
     * Удаляет настройку или все настройки модуля.
     *
     * @param string $module название модуля/модели
     * @param string|null $name название настройки
     * @throws \yii\base\Exception
     */
    public function delete(string $module, ?string $name = null): static;
}
