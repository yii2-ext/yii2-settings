<?php

declare(strict_types=1);

namespace dicr\tests;

use proweb\\settings\stores\PhpSettingsStore;
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

        /** @var \yii\base\Application $app */
        $app = Yii::$app;
        $app->set('settings', new PhpSettingsStore([
            'filename' => self::$filename,
        ]));
    }
}
