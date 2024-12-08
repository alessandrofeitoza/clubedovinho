<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241208195047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE customer (
                id UUID NOT NULL, 
                user_id UUID NOT NULL, 
                phone VARCHAR(20) NOT NULL, 
                addresses JSON NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USER_ID ON customer (user_id)');
        $this->addSql('COMMENT ON COLUMN customer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN customer.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
            ALTER TABLE customer 
                ADD CONSTRAINT fk_customer_user_user_id 
                FOREIGN KEY (user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_customer_user_user_id');
        $this->addSql('DROP TABLE customer');
    }
}
