<?php

declare(strict_types=1);

namespace dicr\settings\stores;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

/**
 * Абстрактное хранилище настроек в файле.
 *
 * @since 5.0.0
 */
abstract class FileSettingsStore extends Component implements SettingsStoreInterface
{
    /** @var string имя файла для сохранения настроек */
    public string $filename;

    /** @var array<string, array<string, mixed>> */
    private array $_data = [];

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        if (isset($this->filename)) {
            $this->filename = Yii::getAlias($this->filename);
        }

        if (empty($this->filename)) {
            throw new InvalidConfigException('filename must not be empty');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $module, ?string $name = null, mixed $default = null): mixed
    {
        $settings = $this->data();

        if ($name !== null) {
            return $settings[$module][$name] ?? $default;
        }

        return array_merge((array) ($default ?: []), $settings[$module] ?? []);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed>|string $name
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        $settings = $this->data();
        $changed = false;

        foreach (is_array($name) ? $name : [$name => $value] as $key => $val) {
            $keyStr = (string) $key;
            if ($val !== null && $val !== '') {
                $settings[$module][$keyStr] = $val;
                $changed = true;
            } elseif (isset($settings[$module][$keyStr])) {
                unset($settings[$module][$keyStr]);
                $changed = true;
            }
        }

        if ($changed) {
            $this->data($settings);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $module, ?string $name = null): static
    {
        $settings = $this->data();
        $changed = false;

        if (isset($settings[$module])) {
            if ($name === null) {
                unset($settings[$module]);
                $changed = true;
            } elseif (isset($settings[$module][$name])) {
                unset($settings[$module][$name]);
                $changed = true;
            }
        }

        if ($changed) {
            $this->data($settings);
        }

        return $this;
    }

    /**
     * Загружает настройки из файла.
     *
     * @return array<string, array<string, mixed>>
     * @throws Exception
     */
    abstract protected function loadFile(): array;

    /**
     * Сохраняет настройки в файл.
     *
     * @param array<string, array<string, mixed>> $settings
     * @throws Exception
     */
    abstract protected function saveFile(array $settings): static;

    /**
     * Получает/устанавливает данные настроек.
     *
     * @param array<string, array<string, mixed>>|null $settings
     * @return array<string, array<string, mixed>>
     * @throws Exception
     */
    protected function data(?array $settings = null): array
    {
        if ($settings !== null) {
            $this->_data = $settings;
            $this->saveFile($settings);
        } elseif ($this->_data === []) {
            $this->_data = $this->loadFile();
        }

        return $this->_data;
    }
}
