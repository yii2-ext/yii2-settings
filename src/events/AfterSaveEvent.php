<?php

declare(strict_types=1);

namespace dicr\settings\events;

use yii\base\Event;

/**
 * Событие после сохранения настроек.
 *
 * @since 5.0.0
 */
class AfterSaveEvent extends Event
{
    /** @var string имя модуля */
    public string $module = '';

    /** @var string|array название настроек */
    public string|array $name = '';

    /** @var mixed сохраненное значение */
    public mixed $value = null;
}
