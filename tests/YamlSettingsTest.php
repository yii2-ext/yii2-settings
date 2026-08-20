<?php

declare(strict_types=1);

namespace dicr\tests;

use dicr\settings\stores\YamlSettingsStore;
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

        Yii::$app->set('settings', new YamlSettingsStore([
            'filename' => self::$filename
        ]));
    }
}
