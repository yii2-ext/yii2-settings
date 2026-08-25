<?php

declare(strict_types=1);

namespace proweb\\settings\events;

use yii\base\Event;

/**
 * Событие перед сохранением настроек.
 *
 * @since 5.0.0
 */
class BeforeSaveEvent extends Event
{
    /** @var string имя модуля */
    public string $module = '';

    /**
     * @var string|array<string, mixed> название настроек
     * @phpstan-ignore-next-line
     */
    public $name = '';

    /** @var mixed значение настроек */
    public mixed $value = null;

    /** @var bool отменить сохранение */
    public bool $cancel = false;
}
