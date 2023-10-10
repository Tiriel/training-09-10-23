<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010082103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Book entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, isbn VARCHAR(20) NOT NULL, plot CLOB DEFAULT NULL, editor VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE book');
    }
}
