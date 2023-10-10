<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010090535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Genre::id to Uuid';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE genre');
        $this->addSql('CREATE TABLE genre (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__genre AS SELECT id, name FROM genre');
        $this->addSql('DROP TABLE genre');
        $this->addSql('CREATE TABLE genre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO genre (id, name) SELECT id, name FROM __temp__genre');
        $this->addSql('DROP TABLE __temp__genre');
    }
}
