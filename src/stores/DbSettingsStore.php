<?php

declare(strict_types=1);

namespace dicr\settings\stores;

use Yii;
use yii\base\Component;
use yii\base\Exception;
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
 * @property-read Connection $db
 * @property-read string $tableName
 *
 * @since 5.0.0
 */
class DbSettingsStore extends Component implements SettingsStoreInterface
{
    /** @var string кодирование значения в строку */
    public const FORMAT_STRING = 'string';

    /** @var string кодирование значения в JSON */
    public const FORMAT_JSON = 'json';

    /** @var string кодирование значения через serialize */
    public const FORMAT_SERIALIZE = 'serialize';

    /** @var array форматы кодирования */
    public const FORMATS = [
        self::FORMAT_STRING => 'String',
        self::FORMAT_JSON => 'JSON',
        self::FORMAT_SERIALIZE => 'Serialize',
    ];

    /** @var string формат кодирования поля значения */
    public string $format = self::FORMAT_JSON;

    /** @var Connection|string база данных */
    public Connection|string $db = 'db';

    /** @var string имя таблицы */
    public string $tableName = '{{%settings}}';

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        $this->db = Instance::ensure($this->db, Connection::class);

        if (empty($this->tableName)) {
            throw new InvalidConfigException('tableName must not be empty');
        }

        if (!array_key_exists($this->format, self::FORMATS)) {
            throw new InvalidConfigException("Invalid format: {$this->format}");
        }

        $this->initDatabase();
    }

    /**
     * Инициализирует базу данных (создает таблицу).
     *
     * @throws Exception
     */
    protected function initDatabase(): void
    {
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
    public function get(string $module, string $name = null, mixed $default = null): mixed
    {
        $query = (new Query())
            ->select('value')
            ->from($this->tableName)
            ->where(['module' => $module]);

        if ($name !== null) {
            $value = $query
                ->andWhere(['name' => $name])
                ->limit(1)
                ->scalar($this->db);

            return $value !== null ? $this->decodeValue($value) : $default;
        }

        $query->addSelect('name')
            ->indexBy('name');

        $values = array_map(
            fn(string $val) => $this->decodeValue($val),
            $query->column($this->db)
        );

        if (is_array($default)) {
            $values = ArrayHelper::merge($default, $values);
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $module, array|string $name, mixed $value = null): static
    {
        foreach (is_array($name) ? $name : [$name => $value] as $key => $val) {
            if ($val === null || $val === '') {
                $this->delete($module, $key);
            } else {
                $this->db->createCommand()->delete($this->tableName, [
                    'module' => $module,
                    'name' => $key,
                ])->execute();

                $this->db->createCommand()->insert($this->tableName, [
                    'module' => $module,
                    'name' => $key,
                    'value' => $this->encodeValue($val),
                ])->execute();
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $module, string $name = null): static
    {
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
     * @return string
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
     *
     * @param string|null $value
     * @return mixed
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
