<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416091134 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // Create 'main' table
        $table = $schema->createTable('type');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('text', 'string', ['notnull'=>true, 'length'=>128]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('type');
    }
}
