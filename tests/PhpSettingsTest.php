<?php

declare(strict_types=1);

namespace dicr\tests;

use dicr\settings\stores\PhpSettingsStore;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Test PhpSettingsStore
 */
class PhpSettingsTest extends AbstractTestCase
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidConfigException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Yii::$app->set('settings', new PhpSettingsStore([
            'filename' => self::$filename
        ]));
    }
}
