<?php

declare(strict_types=1);

namespace proweb\settings\behaviors;

use Yii;
use yii\base\Behavior;
use yii\caching\CacheInterface;

/**
 * Поведение кеширования настроек.
 *
 * @since 5.0.0
 */
class CacheBehavior extends Behavior
{
    /** @var string ID компонента кеша */
    public string $cacheComponent = 'cache';

    /** @var int TTL кеша в секундах */
    public int $duration = 3600;

    /** @var string префикс ключей кеша */
    public string $keyPrefix = 'settings_';

    /**
     * Возвращает компонент кеша.
     */
    protected function getCache(): ?CacheInterface
    {
        if (Yii::$app === null) {
            return null;
        }

        /** @var CacheInterface|null $cache */
        $cache = Yii::$app->get($this->cacheComponent, false);

        return $cache;
    }

    /**
     * Формирует ключ кеша.
     */
    protected function getCacheKey(string $module, ?string $name = null): string
    {
        return $this->keyPrefix . $module . ($name !== null ? '_' . $name : '');
    }

    /**
     * Получает значение из кеша.
     */
    public function getFromCache(string $module, ?string $name = null): mixed
    {
        $cache = $this->getCache();
        if (!$cache instanceof CacheInterface) {
            return null;
        }

        $key = $this->getCacheKey($module, $name);
        return $cache->get($key);
    }

    /**
     * Сохраняет значение в кеш.
     *
     * @param array<string, mixed>|string $name
     */
    public function setToCache(string $module, string|array $name, mixed $value): void
    {
        $cache = $this->getCache();
        if (!$cache instanceof CacheInterface) {
            return;
        }

        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $key = $this->getCacheKey($module, (string) $k);
                $cache->set($key, $v, $this->duration);
            }
        } else {
            $key = $this->getCacheKey($module, $name);
            $cache->set($key, $value, $this->duration);
        }
    }

    /**
     * Очищает кеш модуля.
     */
    public function clearCache(string $module): void
    {
        $cache = $this->getCache();
        if (!$cache instanceof CacheInterface) {
            return;
        }

        $key = $this->getCacheKey($module);
        $cache->delete($key);
    }
}
