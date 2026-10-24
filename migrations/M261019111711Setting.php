<?php

declare(strict_types=1);

use Atom\Entity\SettingType;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M261019111711Setting implements RevertibleMigrationInterface
{
    private const GROUPS_TABLE = '{{%setting_group}}';
    private const SETTINGS_TABLE = '{{%setting}}';

    public function up(MigrationBuilder $b): void
    {
        $this->createGroupsTable($b);
        $this->createSettingsTable($b);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable(self::SETTINGS_TABLE);
        $b->dropTable(self::GROUPS_TABLE);
    }

    private function createGroupsTable(MigrationBuilder $b): void
    {
        $b->createTable(self::GROUPS_TABLE, [
            'uuid' => ColumnBuilder::string(36)->notNull(),
            'name' => ColumnBuilder::string(100)->notNull(),
            'position' => ColumnBuilder::integer()->notNull(),
            'created_at' => ColumnBuilder::datetime()->notNull(),
            'updated_at' => ColumnBuilder::datetime()->notNull(),
        ]);

        $b->addPrimaryKey(self::GROUPS_TABLE, 'uuid', 'uuid');
        $b->createIndex(self::GROUPS_TABLE, 'name', 'name', 'UNIQUE');
    }

    private function createSettingsTable(MigrationBuilder $b): void
    {
        $b->createTable(self::SETTINGS_TABLE, [
            'uuid' => ColumnBuilder::string(36)->notNull(),
            'group_uuid' => ColumnBuilder::string(36)->notNull(),
            'key' => ColumnBuilder::string(64)->notNull(),
            'name' => ColumnBuilder::string(100)->notNull(),
            'position' => ColumnBuilder::integer()->notNull(),
            'type' => ColumnBuilder::string(32)->notNull()->defaultValue(SettingType::STRING->value),
            'value' => ColumnBuilder::text()->notNull(),
            'created_at' => ColumnBuilder::datetime()->notNull(),
            'updated_at' => ColumnBuilder::datetime()->notNull(),
        ]);

        $b->addPrimaryKey(self::SETTINGS_TABLE, 'uuid', 'uuid');
        $b->createIndex(self::SETTINGS_TABLE, 'key', 'key', 'UNIQUE');
        $b->addForeignKey(self::SETTINGS_TABLE, 'fk_settings_group', 'group_uuid', self::GROUPS_TABLE, 'uuid', 'CASCADE', 'CASCADE');
    }
}
