<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120110022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_entity (id INT AUTO_INCREMENT NOT NULL, RestaurantId INT DEFAULT NULL, UNIQUE INDEX UNIQ_DD6F1CB099EB28EB (RestaurantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_entity_products_entity (products_entity_id INT NOT NULL, ProductsId INT NOT NULL, INDEX IDX_6D37FFD5AE0AFEAC (ProductsId), INDEX IDX_6D37FFD5D3589E7B (products_entity_id), PRIMARY KEY(ProductsId, products_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_entity ADD CONSTRAINT FK_DD6F1CB099EB28EB FOREIGN KEY (RestaurantId) REFERENCES restaurant_entity (id)');
        $this->addSql('ALTER TABLE menu_entity_products_entity ADD CONSTRAINT FK_6D37FFD5AE0AFEAC FOREIGN KEY (ProductsId) REFERENCES menu_entity (id)');
        $this->addSql('ALTER TABLE menu_entity_products_entity ADD CONSTRAINT FK_6D37FFD5D3589E7B FOREIGN KEY (products_entity_id) REFERENCES products_entity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_entity DROP FOREIGN KEY FK_DD6F1CB099EB28EB');
        $this->addSql('ALTER TABLE menu_entity_products_entity DROP FOREIGN KEY FK_6D37FFD5AE0AFEAC');
        $this->addSql('ALTER TABLE menu_entity_products_entity DROP FOREIGN KEY FK_6D37FFD5D3589E7B');
        $this->addSql('DROP TABLE menu_entity');
        $this->addSql('DROP TABLE menu_entity_products_entity');
    }
}
