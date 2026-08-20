<?php

declare(strict_types=1);

namespace dicr\tests;

use dicr\settings\stores\DbSettingsStore;
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

        Yii::$app->set('settings', new DbSettingsStore());
    }
}
