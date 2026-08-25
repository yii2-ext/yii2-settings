<?php

declare(strict_types=1);

namespace proweb\settings\stores;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Query;
use yii\db\Schema;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Хранилище настроек в базе данных.
 *
 * @since 5.0.0
 */
class DbSettingsStore extends Component implements SettingsStoreInterface
{
    /** @var string формат значения: строка */
    public const string FORMAT_STRING = 'string';

    /** @var string формат значения: JSON */
    public const string FORMAT_JSON = 'json';

    /** @var string формат значения: serialize */
    public const string FORMAT_SERIALIZE = 'serialize';

    /** @var array<string, string> список поддерживаемых форматов */
    public const array FORMATS = [
        self::FORMAT_STRING => 'String',
        self::FORMAT_JSON => 'JSON',
        self::FORMAT_SERIALIZE => 'Serialize',
    ];

    /** @var Connection|string компонент подключения к БД */
    public Connection|string $db = 'db';

    /** @var string имя таблицы БД */
    public string $tableName = '{{%settings}}';

    /** @var string формат значения (один из FORMATS) */
    public string $format = self::FORMAT_JSON;

    /** @var bool автоматически создавать таблицу при отсутствии */
    public bool $autoCreateTable = true;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        /** @var Connection $db */
        $db = Instance::ensure($this->db, Connection::class);
        $this->db = $db;

        if (!isset(self::FORMATS[$this->format])) {
            throw new InvalidConfigException("Invalid format: {$this->format}");
        }

        if ($this->autoCreateTable) {
            $this->createTable();
        }
    }

    /**
     * Создает таблицу БД если она не существует.
     */
    protected function createTable(): void
    {
        if (!$this->db instanceof Connection) {
            return;
        }

        $schema = $this->db->getSchema();

        if (!in_array($schema->getRawTableName($this->tableName), $schema->tableNames, true)) {
            $this->db->createCommand()
                ->createTable($this->tableName, [
                    'module' => Schema::TYPE_STRING . ' NOT NULL',
                    'name' => Schema::TYPE_STRING . ' NOT NULL',
                    'value' => Schema::TYPE_TEXT,
                ])
                ->execute();

            $this->db->createCommand()
                ->createIndex(
                    'idx-settings-module-name',
                    $this->tableName,
                    ['module', 'name'],
                    true
                )
                ->execute();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $module, ?string $name = null, mixed $default = null): mixed
    {
        if (!$this->db instanceof Connection) {
            return $default;
        }

        $query = (new Query())
            ->select('value')
            ->from($this->tableName)
            ->where(['module' => $module]);

        if ($name !== null) {
            /** @var string|null $value */
            $value = $query
                ->andWhere(['name' => $name])
                ->limit(1)
                ->scalar($this->db);

            return $value !== null ? $this->decodeValue($value) : $default;
        }

        $query->addSelect('name')
            ->indexBy('name');

        /** @var array<string, string|null> $rawValues */
        $rawValues = $query->column($this->db);

        $values = array_map(
            fn(?string $val): mixed => $this->decodeValue($val),
            $rawValues
        );

        if (is_array($default)) {
            return ArrayHelper::merge($default, $values);
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed>|string $name
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        if (!$this->db instanceof Connection) {
            return $this;
        }

        foreach (is_array($name) ? $name : [$name => $value] as $key => $val) {
            $keyStr = (string) $key;
            if ($val === null || $val === '') {
                $this->delete($module, $keyStr);
            } else {
                $this->db->createCommand()->delete($this->tableName, [
                    'module' => $module,
                    'name' => $keyStr,
                ])->execute();

                $this->db->createCommand()->insert($this->tableName, [
                    'module' => $module,
                    'name' => $keyStr,
                    'value' => $this->encodeValue($val),
                ])->execute();
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $module, ?string $name = null): static
    {
        if (!$this->db instanceof Connection) {
            return $this;
        }

        $conds = ['module' => $module];

        if ($name !== null) {
            $conds['name'] = $name;
        }

        $this->db->createCommand()
            ->delete($this->tableName, $conds)
            ->execute();

        return $this;
    }

    /**
     * Кодирует значение для сохранения в базу.
     *
     * @param mixed $value значение
     */
    protected function encodeValue(mixed $value): string
    {
        return match ($this->format) {
            self::FORMAT_STRING => (string) $value,
            self::FORMAT_JSON => Json::encode($value),
            self::FORMAT_SERIALIZE => serialize($value),
            default => throw new InvalidConfigException("Unknown format: {$this->format}"),
        };
    }

    /**
     * Декодирует значение из базы.
     */
    protected function decodeValue(?string $value): mixed
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return match ($this->format) {
            self::FORMAT_STRING => $value,
            self::FORMAT_JSON => Json::decode($value),
            self::FORMAT_SERIALIZE => unserialize($value, ['allowed_classes' => true]),
            default => throw new InvalidConfigException("Unknown format: {$this->format}"),
        };
    }
}
