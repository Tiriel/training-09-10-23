<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010083759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Movie entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, released_at DATE NOT NULL --(DC2Type:date_immutable)
        , plot CLOB NOT NULL, price INTEGER DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE movie');
    }
}
