<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license GPL-3.0-or-later
 * @version 05.01.22 03:27:17
 */

/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types = 1);
namespace dicr\settings;

use yii\base\Exception;

use function is_array;
use function yaml_emit_file;
use function yaml_parse_file;

/**
 * Настройки в Yaml-файле.
 */
class YamlSettingsStore extends FileSettingsStore
{
    /**
     * @inheritDoc
     */
    protected function loadFile(): array
    {
        $settings = [];

        if (file_exists($this->filename)) {
            $settings = yaml_parse_file($this->filename);
            if (! is_array($settings)) {
                throw new Exception('Ошибка загрузки файла: ' . $this->filename);
            }
        }

        return $settings;
    }

    /**
     * @inheritDoc
     */
    protected function saveFile(array $settings): static
    {
        if (! yaml_emit_file($this->filename, $settings, YAML_UTF8_ENCODING, YAML_LN_BREAK)) {
            throw new Exception('ошибка сохранения файла: ' . $this->filename);
        }

        return $this;
    }
}
