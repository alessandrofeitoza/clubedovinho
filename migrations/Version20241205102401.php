<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205102401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates/Removes Country Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE country (
                id UUID NOT NULL, 
                name VARCHAR(50) NOT NULL, 
                PRIMARY KEY(id)
            );
        ');

        $this->addSql('COMMENT ON COLUMN country.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE country');
    }
}
