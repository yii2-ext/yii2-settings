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
    public function get(string $module, string $name = null, mixed $default = null): mixed
    {
        $settings = $this->data();

        if ($name !== null) {
            return $settings[$module][$name] ?? $default;
        }

        return array_merge((array) ($default ?: []), $settings[$module] ?? []);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        $settings = $this->data();
        $changed = false;

        foreach (is_array($name) ? $name : [$name => $value] as $key => $val) {
            if ($val !== null && $val !== '') {
                $settings[$module][$key] = $val;
                $changed = true;
            } elseif (isset($settings[$module][$key])) {
                unset($settings[$module][$key]);
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
     * @return array
     * @throws Exception
     */
    abstract protected function loadFile(): array;

    /**
     * Сохраняет настройки в файл.
     *
     * @param array $settings
     * @return static
     * @throws Exception
     */
    abstract protected function saveFile(array $settings): static;

    /**
     * Получает/устанавливает данные настроек.
     *
     * @param array|null $settings
     * @return array
     * @throws Exception
     */
    protected function data(?array $settings = null): array
    {
        if ($settings !== null) {
            $this->_data = $settings;
            $this->saveFile($settings);
        } elseif (empty($this->_data)) {
            $this->_data = $this->loadFile();
        }

        return $this->_data;
    }
}
