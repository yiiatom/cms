<?php

declare(strict_types=1);

use DateTimeImmutable;
use Atom\Entity\User;
use Ramsey\Uuid\Uuid;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;
use Yiisoft\Security\PasswordHasher;

final class M260709035701User implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    private const USER_TABLE = '{{%user}}';
    private const USER_AUTHKEY_TABLE = '{{%user_authkey}}';

    public function up(MigrationBuilder $b): void
    {
        $this->createUserTable($b);
        $this->createUserAuthKeyTable($b);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable(self::USER_AUTHKEY_TABLE);
        $b->dropTable(self::USER_TABLE);
    }

    private function createUserTable(MigrationBuilder $b): void
    {
        $b->createTable(self::USER_TABLE, [
            'uuid' => ColumnBuilder::string(36)->notNull(),
            'username' => ColumnBuilder::string(20)->notNull(),
            'email' => ColumnBuilder::string(254),
            'password' => ColumnBuilder::string(64),
            'password_expires_at' => ColumnBuilder::datetime(),
            'is_superadmin' => ColumnBuilder::boolean()->notNull()->defaultValue(false),
            'status' => ColumnBuilder::integer()->notNull(),
            'first_name' => ColumnBuilder::string(100),
            'last_name' => ColumnBuilder::string(100),
            'avatar_url' => ColumnBuilder::string(255),
            'created_at' => ColumnBuilder::datetime()->notNull(),
            'updated_at' => ColumnBuilder::datetime()->notNull(),
            'deleted_at' => ColumnBuilder::datetime(),
        ]);

        $b->addPrimaryKey(self::USER_TABLE, 'uuid', 'uuid');
        $b->createIndex(self::USER_TABLE, 'username', 'username', 'UNIQUE');
        $b->createIndex(self::USER_TABLE, 'email', ['email', 'deleted_at'], 'UNIQUE');
    }

    private function createUserAuthKeyTable(MigrationBuilder $b): void
    {
        $b->createTable(self::USER_AUTHKEY_TABLE, [
            'uuid' => ColumnBuilder::string(36)->notNull(),
            'user_uuid' => ColumnBuilder::string(36)->notNull(),
            'expires_at' => ColumnBuilder::datetime()->notNull(),
        ]);

        $b->addPrimaryKey(self::USER_AUTHKEY_TABLE, 'uuid', 'uuid');
        $b->addForeignKey(self::USER_AUTHKEY_TABLE, 'fk_user_authkey_user', 'user_uuid', self::USER_TABLE, 'uuid', 'CASCADE', 'CASCADE');
        $b->createIndex(self::USER_AUTHKEY_TABLE, 'user_uuid', 'user_uuid');
        $b->createIndex(self::USER_AUTHKEY_TABLE, 'expires_at', 'expires_at');
    }
}
