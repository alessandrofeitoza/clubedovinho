<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241209175219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates/Removes purchase Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE "purchase" (
                id UUID NOT NULL, 
                customer_id UUID NOT NULL, 
                status VARCHAR(255) NOT NULL, 
                distance NUMERIC(10, 2) NOT NULL, 
                delivery_cost NUMERIC(10, 2) NOT NULL, 
                total_price NUMERIC(10, 2) NOT NULL, 
                address JSON NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX idx_purchase_customer_id ON "purchase" (customer_id)');
        $this->addSql('COMMENT ON COLUMN "purchase".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "purchase".customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "purchase".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "purchase".updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('
            CREATE TABLE purchases_products (
                purchase_id UUID NOT NULL, 
                product_id UUID NOT NULL, 
                PRIMARY KEY(purchase_id, product_id)
            )
        ');
        $this->addSql('CREATE INDEX idx_pruchases_products_purchase_id ON purchases_products (purchase_id)');
        $this->addSql('CREATE INDEX idx_pruchases_products_product_id ON purchases_products (product_id)');
        $this->addSql('COMMENT ON COLUMN purchases_products.purchase_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN purchases_products.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
            ALTER TABLE purchases_products 
                ADD CONSTRAINT fk_purchases_products_purchase_purchase_id 
                FOREIGN KEY (purchase_id) REFERENCES purchase (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE purchases_products 
                ADD CONSTRAINT fk_purchases_products_product_product_id 
                FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "purchase" DROP CONSTRAINT fk_purchase_customer_customer_id');
        $this->addSql('DROP TABLE "purchase"');
        $this->addSql('ALTER TABLE purchases_products DROP CONSTRAINT fk_purchases_products_purchase_purchase_id');
        $this->addSql('ALTER TABLE purchases_products DROP CONSTRAINT fk_purchases_products_product_product_id');
        $this->addSql('DROP TABLE purchases_products');
    }
}
