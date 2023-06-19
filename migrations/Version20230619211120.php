<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619211120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clean_photo (id INT AUTO_INCREMENT NOT NULL, clean_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, INDEX IDX_971B3F7AC9C202A6 (clean_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clean_photo ADD CONSTRAINT FK_971B3F7AC9C202A6 FOREIGN KEY (clean_id) REFERENCES clean (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clean_photo DROP FOREIGN KEY FK_971B3F7AC9C202A6');
        $this->addSql('DROP TABLE clean_photo');
    }
}
