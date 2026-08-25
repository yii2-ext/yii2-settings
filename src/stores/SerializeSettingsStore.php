<?php

declare(strict_types=1);

namespace proweb\\settings\stores;

use yii\base\Exception;

/**
 * Хранилище настроек в файле через serialize().
 *
 * @since 5.0.0
 */
class SerializeSettingsStore extends FileSettingsStore
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
            $content = file_get_contents($this->filename);

            if ($content === false) {
                throw new Exception("Failed to load settings file: {$this->filename}");
            }

            $settings = unserialize($content, ['allowed_classes' => true]);

            if (!is_array($settings)) {
                throw new Exception("Failed to decode settings: {$this->filename}");
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
        $content = serialize($settings);

        if (file_put_contents($this->filename, $content, LOCK_EX) === false) {
            throw new Exception("Failed to save settings file: {$this->filename}");
        }

        return $this;
    }
}
