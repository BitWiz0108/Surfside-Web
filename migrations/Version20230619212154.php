<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619212154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clean_housekeeper (id INT AUTO_INCREMENT NOT NULL, clean_id INT DEFAULT NULL, housekeeper_id INT DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, INDEX IDX_EB9599C0C9C202A6 (clean_id), INDEX IDX_EB9599C0362EB9BC (housekeeper_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clean_linen (id INT AUTO_INCREMENT NOT NULL, clean_id INT DEFAULT NULL, linen_id INT DEFAULT NULL, units INT DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, INDEX IDX_AFAB5B2DC9C202A6 (clean_id), INDEX IDX_AFAB5B2DDD9D69AE (linen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clean_housekeeper ADD CONSTRAINT FK_EB9599C0C9C202A6 FOREIGN KEY (clean_id) REFERENCES clean (id)');
        $this->addSql('ALTER TABLE clean_housekeeper ADD CONSTRAINT FK_EB9599C0362EB9BC FOREIGN KEY (housekeeper_id) REFERENCES housekeeper (id)');
        $this->addSql('ALTER TABLE clean_linen ADD CONSTRAINT FK_AFAB5B2DC9C202A6 FOREIGN KEY (clean_id) REFERENCES clean (id)');
        $this->addSql('ALTER TABLE clean_linen ADD CONSTRAINT FK_AFAB5B2DDD9D69AE FOREIGN KEY (linen_id) REFERENCES linen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clean_housekeeper DROP FOREIGN KEY FK_EB9599C0C9C202A6');
        $this->addSql('ALTER TABLE clean_housekeeper DROP FOREIGN KEY FK_EB9599C0362EB9BC');
        $this->addSql('ALTER TABLE clean_linen DROP FOREIGN KEY FK_AFAB5B2DC9C202A6');
        $this->addSql('ALTER TABLE clean_linen DROP FOREIGN KEY FK_AFAB5B2DDD9D69AE');
        $this->addSql('DROP TABLE clean_housekeeper');
        $this->addSql('DROP TABLE clean_linen');
    }
}
