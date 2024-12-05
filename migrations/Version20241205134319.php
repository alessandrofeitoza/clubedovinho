<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205134319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Inserts/Deletes Country Data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO country (id, name) VALUES ('4afa1f66-af28-4bcf-be90-fa2f93390f0a', 'Argentina')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('312e4290-4d73-44b2-a538-52e1b9afc143', 'Brasil')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('96a0a688-6a6f-4361-b637-b306ec0c6aa9', 'Chile')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('22f6db3e-e72f-493f-9667-e2eccf5de7ac', 'Escócia')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('e365b702-e715-49d1-9a8e-03f30d522b23', 'Espanha')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('216855f5-8e03-40d8-8e87-b58609eb0534', 'Estados Unidos da América')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('c208e7ee-d6b8-4c53-ba07-3d5336f22abe', 'França')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('d0d9e011-daaa-49ae-abc7-979d0f6b5b87', 'Itália')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('f25b7091-09c7-4b4d-ac0b-69c8e1853425', 'Portugal')");
        $this->addSql("INSERT INTO country (id, name) VALUES ('20e35845-6465-483a-bb9b-45f96bbed78c', 'Russia')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE country');
    }
}
