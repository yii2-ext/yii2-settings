<?php

declare(strict_types=1);

namespace dicr\tests;

use dicr\settings\stores\SerializeSettingsStore;
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

        Yii::$app->set('settings', new SerializeSettingsStore([
            'filename' => self::$filename
        ]));
    }
}
