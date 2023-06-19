<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619210418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clean (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, scheduled DATETIME DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, notes LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, INDEX IDX_F1B0AD49549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clean ADD CONSTRAINT FK_F1B0AD49549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clean DROP FOREIGN KEY FK_F1B0AD49549213EC');
        $this->addSql('DROP TABLE clean');
    }
}
