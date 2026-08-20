<?php

declare(strict_types=1);

namespace dicr\settings\migrations;

use yii\db\Migration;

/**
 * Создание таблицы настроек.
 *
 * @since 5.0.0
 */
class m240101_000001_create_settings_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'module' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'value' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-settings-module-name',
            '{{%settings}}',
            ['module', 'name'],
            true
        );

        $this->createIndex(
            'idx-settings-module',
            '{{%settings}}',
            'module'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%settings}}');
    }
}
