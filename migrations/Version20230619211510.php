<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619211510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clean_supply (id INT AUTO_INCREMENT NOT NULL, clean_id INT DEFAULT NULL, supply_id INT DEFAULT NULL, units DOUBLE PRECISION DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, INDEX IDX_71263843C9C202A6 (clean_id), INDEX IDX_71263843FF28C0D8 (supply_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clean_supply ADD CONSTRAINT FK_71263843C9C202A6 FOREIGN KEY (clean_id) REFERENCES clean (id)');
        $this->addSql('ALTER TABLE clean_supply ADD CONSTRAINT FK_71263843FF28C0D8 FOREIGN KEY (supply_id) REFERENCES supply (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clean_supply DROP FOREIGN KEY FK_71263843C9C202A6');
        $this->addSql('ALTER TABLE clean_supply DROP FOREIGN KEY FK_71263843FF28C0D8');
        $this->addSql('DROP TABLE clean_supply');
    }
}
