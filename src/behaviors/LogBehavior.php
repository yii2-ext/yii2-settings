<?php

declare(strict_types=1);

namespace dicr\settings\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Поведение логирования настроек.
 *
 * @since 5.0.0
 */
class LogBehavior extends Behavior
{
    /** @var string категория логов */
    public string $logCategory = 'dicr.settings';

    /** @var bool логировать ли чтение */
    public bool $logRead = false;

    /** @var bool логировать ли запись */
    public bool $logWrite = true;

    /** @var bool логировать ли удаление */
    public bool $logDelete = true;

    /**
     * Логирует чтение настройки.
     *
     * @param string $module
     * @param string|null $name
     */
    public function logRead(string $module, ?string $name): void
    {
        if (!$this->logRead) {
            return;
        }

        Yii::info(
            "Reading setting: module={$module}, name={$name}",
            $this->logCategory
        );
    }

    /**
     * Логирует запись настройки.
     *
     * @param string $module
     * @param string|array $name
     * @param mixed $value
     */
    public function logWrite(string $module, string|array $name, mixed $value): void
    {
        if (!$this->logWrite) {
            return;
        }

        Yii::info(
            "Writing setting: module={$module}, name=" . (is_array($name) ? implode(',', $name) : $name),
            $this->logCategory
        );
    }

    /**
     * Логирует удаление настройки.
     *
     * @param string $module
     * @param string|null $name
     */
    public function logDelete(string $module, ?string $name): void
    {
        if (!$this->logDelete) {
            return;
        }

        Yii::info(
            "Deleting setting: module={$module}, name={$name}",
            $this->logCategory
        );
    }
}
