<?php

declare(strict_types=1);

namespace proweb\tests;

use proweb\settings\stores\DbSettingsStore;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Test DbSettingsStore
 */
class DbSettingsTest extends AbstractTestCase
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidConfigException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        /** @var \yii\base\Application $app */
        $app = Yii::$app;
        $app->set('settings', new DbSettingsStore());
    }
}
