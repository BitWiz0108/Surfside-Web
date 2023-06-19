<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616135214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, postalcode VARCHAR(50) DEFAULT NULL, latitude VARCHAR(25) DEFAULT NULL, longitude VARCHAR(25) DEFAULT NULL, bedrooms INT DEFAULT NULL, bathrooms INT DEFAULT NULL, square_feet INT DEFAULT NULL, kings INT DEFAULT NULL, queens INT DEFAULT NULL, twins INT DEFAULT NULL, towels INT DEFAULT NULL, hand_towels INT DEFAULT NULL, wash_cloths INT DEFAULT NULL, instructions LONGTEXT DEFAULT NULL, door_code VARCHAR(25) DEFAULT NULL, INDEX IDX_8BF21CDE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE9395C3F3');
        $this->addSql('DROP TABLE property');
    }
}
