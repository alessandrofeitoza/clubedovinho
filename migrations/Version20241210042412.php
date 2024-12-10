<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241210042412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds/Removes column price from product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD price NUMERIC(10, 2) NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP price');
    }
}
