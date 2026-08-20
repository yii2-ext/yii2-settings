<?php

declare(strict_types=1);

namespace dicr\settings;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Автозагрузка модуля настроек.
 *
 * @since 5.0.0
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap($app): void
    {
        if ($app->hasModule('settings')) {
            /** @var Module $module */
            $module = $app->getModule('settings');
            $module->registerComponents();
        }
    }
}
