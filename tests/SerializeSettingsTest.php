<?php

declare(strict_types=1);

namespace proweb\tests;

use proweb\settings\stores\SerializeSettingsStore;
use Yii;
use yii\base\Exception;

/**
 * Test SerializeSettingsStore
 */
class SerializeSettingsTest extends AbstractTestCase
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
        $app->set('settings', new SerializeSettingsStore([
            'filename' => self::$filename,
        ]));
    }
}
