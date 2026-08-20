<?php

declare(strict_types=1);

namespace dicr\settings;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Фасад-сервис для работы с настройками.
 *
 * Публичная точка доступа к хранилищу настроек.
 * Регистрируется как компонент 'settings' в приложении.
 *
 * @since 5.0.0
 */
class Settings extends Component implements \dicr\settings\stores\SettingsStoreInterface
{
    /** @var SettingsStoreInterface|string внутреннее хранилище */
    public SettingsStoreInterface|string $store = DbSettingsStore::class;

    private ?SettingsStoreInterface $_store = null;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        $this->_store = Instance::ensure($this->store, SettingsStoreInterface::class);
    }

    /**
     * Возвращает внутреннее хранилище.
     *
     * @return SettingsStoreInterface
     */
    public function getInnerStore(): SettingsStoreInterface
    {
        return $this->_store;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $module, string $name = null, mixed $default = null): mixed
    {
        return $this->_store->get($module, $name, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        $this->_store->set($module, $name, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $module, ?string $name = null): static
    {
        $this->_store->delete($module, $name);

        return $this;
    }
}
