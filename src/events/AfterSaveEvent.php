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

    /**
     * @var string|array<string, mixed> название настроек
     * @phpstan-ignore-next-line
     */
    public $name = '';

    /** @var mixed сохраненное значение */
    public mixed $value = null;
}
