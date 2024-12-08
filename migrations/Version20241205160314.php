<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205160314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates/Removes Product Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE product (
                id UUID NOT NULL, 
                category_id UUID NOT NULL, 
                country_id UUID NOT NULL, 
                name VARCHAR(50) NOT NULL, 
                weight INT NOT NULL, 
                additional_info VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_product_category_id ON product (category_id)');
        $this->addSql('CREATE INDEX idx_product_country_id ON product (country_id)');
        $this->addSql('COMMENT ON COLUMN product.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN product.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN product.country_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
            ALTER TABLE product 
                ADD CONSTRAINT fk_product_category_id_category 
                FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('
            ALTER TABLE product 
                ADD CONSTRAINT fk_product_country_id_country 
                FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_product_category_id_category');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_product_country_id_country');
        $this->addSql('DROP TABLE product');
    }
}
