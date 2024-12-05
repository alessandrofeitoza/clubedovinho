<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241203180358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates/Removes Category Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE category (
                id UUID NOT NULL, 
                name VARCHAR(50) NOT NULL, 
                description VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            );
        ');

        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE category');
    }
}
