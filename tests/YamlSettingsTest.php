<?php

declare(strict_types=1);

namespace proweb\tests;

use proweb\settings\stores\YamlSettingsStore;
use Yii;
use yii\base\Exception;

/**
 * Test YamlSettingsStore
 */
class YamlSettingsTest extends AbstractTestCase
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        /** @var \yii\base\Application $app */
        $app = Yii::$app;
        $app->set('settings', new YamlSettingsStore([
            'filename' => self::$filename,
        ]));
    }
}
