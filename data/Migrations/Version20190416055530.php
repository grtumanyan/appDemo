<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416055530 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // Create 'main' table
        $table = $schema->createTable('main');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('text', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('title', 'string', ['notnull'=>true, 'length'=>512]);
        $table->addColumn('image', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('file', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('type', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('lang', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('main');
    }
}
