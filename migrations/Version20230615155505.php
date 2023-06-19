<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615155505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housekeeper ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE housekeeper ADD CONSTRAINT FK_2D700DF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D700DF0A76ED395 ON housekeeper (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housekeeper DROP FOREIGN KEY FK_2D700DF0A76ED395');
        $this->addSql('DROP INDEX UNIQ_2D700DF0A76ED395 ON housekeeper');
        $this->addSql('ALTER TABLE housekeeper DROP user_id');
    }
}
