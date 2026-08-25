<?php

declare(strict_types=1);

namespace proweb\settings\stores;

use yii\base\Exception;

/**
 * Хранилище настроек в YAML-файле.
 *
 * @since 5.0.0
 */
class YamlSettingsStore extends FileSettingsStore
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, mixed>>
     */
    protected function loadFile(): array
    {
        $settings = [];

        if (file_exists($this->filename)) {
            $settings = \yaml_parse_file($this->filename);

            if (!is_array($settings)) {
                throw new Exception("Failed to load settings file: {$this->filename}");
            }
        }

        /** @var array<string, array<string, mixed>> $settings */
        return $settings;
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, array<string, mixed>> $settings
     */
    protected function saveFile(array $settings): static
    {
        if (!\yaml_emit_file($this->filename, $settings, YAML_UTF8_ENCODING, YAML_LN_BREAK)) {
            throw new Exception("Failed to save settings file: {$this->filename}");
        }

        return $this;
    }
}
