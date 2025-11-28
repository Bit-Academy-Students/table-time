<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114131901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_entity (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, amount_people INT NOT NULL, Email varchar(255) NOT NULL, RestaurantId INT DEFAULT NULL, INDEX IDX_46D90DF3BE22D475 (CustomerId), INDEX IDX_46D90DF399EB28EB (RestaurantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_entity ADD CONSTRAINT FK_46D90DF399EB28EB FOREIGN KEY (RestaurantId) REFERENCES restaurant_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_entity DROP FOREIGN KEY FK_46D90DF399EB28EB');
        $this->addSql('DROP TABLE reservation_entity');
    }
}
