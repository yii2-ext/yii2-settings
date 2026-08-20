<?php

declare(strict_types=1);

namespace dicr\settings\events;

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

    /** @var string|array название настроек */
    public string|array $name = '';

    /** @var mixed значение настроек */
    public mixed $value = null;

    /** @var bool отменить сохранение */
    public bool $cancel = false;
}
