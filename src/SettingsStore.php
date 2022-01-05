<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license GPL-3.0-or-later
 * @version 05.01.22 03:22:20
 */

declare(strict_types = 1);
namespace dicr\settings;

use yii\base\Exception;

/**
 * Абстрактное хранилище настроек.
 */
interface SettingsStore
{
    /**
     * Получает значение настройки/настроек.
     *
     * @param string $module имя модуля/модели
     *
     * @param string|null $name название настройки
     *  Если пустое, то возвращает ассоциативный массив всех настроек модуля.
     *
     * @param mixed|null $default значение по-умолчанию.
     *  Если name задано, то значение настройки, иначе ассоциативный массив значений по-умолчанию.
     *
     * @return mixed если name задан то значение настройки, иначе ассоциативный массив всех настроек модуля
     * @throws Exception
     *
     */
    public function get(string $module, string $name = null, mixed $default = null): mixed;

    /**
     * Сохраняет значение настройки/настроек.
     * Если значение пустое, то удаляет его.
     *
     * @param string $module название модуля/модели
     * @param array|string $name название параметра или ассоциативный массив параметр => значение
     * @param mixed|null $value значение если name как скаляр
     * @return $this
     * @throws Exception
     */
    public function set(string $module, array|string $name, mixed $value = null): static;

    /**
     * Удалить значение.
     * Значения удаляются методом установки в null.
     *
     * @param string $module название модуля/модели.
     * @param string|null $name название настройки.
     *        Если не задано, то удаляются все настройки модуля.
     * @return $this
     * @throws Exception
     */
    public function delete(string $module, ?string $name = null): static;
}
