<?php

declare(strict_types=1);

namespace proweb\settings;

use proweb\settings\stores\DbSettingsStore;
use proweb\settings\stores\SettingsStoreInterface;
use yii\base\Component;
use yii\di\Instance;

/**
 * Фасад-сервис для работы с настройками.
 *
 * Публичная точка доступа к хранилищу настроек.
 * Регистрируется как компонент 'settings' в приложении.
 *
 * @since 5.0.0
 */
class Settings extends Component implements SettingsStoreInterface
{
    /** @var SettingsStoreInterface|string|array<string, mixed> внутреннее хранилище */
    public SettingsStoreInterface|string|array $store = DbSettingsStore::class;

    private ?SettingsStoreInterface $_store = null;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        /** @var SettingsStoreInterface $store */
        $store = Instance::ensure($this->store, SettingsStoreInterface::class);
        $this->_store = $store;
    }

    /**
     * Возвращает внутреннее хранилище.
     */
    public function getInnerStore(): SettingsStoreInterface
    {
        if (!$this->_store instanceof SettingsStoreInterface) {
            /** @var SettingsStoreInterface $store */
            $store = Instance::ensure($this->store, SettingsStoreInterface::class);
            $this->_store = $store;
        }

        return $this->_store;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $module, ?string $name = null, mixed $default = null): mixed
    {
        return $this->getInnerStore()->get($module, $name, $default);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed>|string $name
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        $this->getInnerStore()->set($module, $name, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $module, ?string $name = null): static
    {
        $this->getInnerStore()->delete($module, $name);

        return $this;
    }
}
