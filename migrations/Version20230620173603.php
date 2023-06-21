<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620173603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TRIGGER `clean_log_insert` BEFORE INSERT ON `clean` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_log_update` BEFORE UPDATE ON `clean` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_housekeeper_log_insert` BEFORE INSERT ON `clean_housekeeper` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_housekeeper_log_update` BEFORE UPDATE ON `clean_housekeeper` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_linen_log_insert` BEFORE INSERT ON `clean_linen` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_linen_log_update` BEFORE UPDATE ON `clean_linen` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_photo_log_insert` BEFORE INSERT ON `clean_photo` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_photo_log_update` BEFORE UPDATE ON `clean_photo` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;'); 
        $this->addSql('CREATE TRIGGER `clean_supply_log_insert` BEFORE INSERT ON `clean_supply` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `clean_supply_log_update` BEFORE UPDATE ON `clean_supply` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;'); 
        $this->addSql('CREATE TRIGGER `customer_log_insert` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `customer_log_update` BEFORE UPDATE ON `customer` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');   
        $this->addSql('CREATE TRIGGER `housekeeper_log_insert` BEFORE INSERT ON `housekeeper` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `housekeeper_log_update` BEFORE UPDATE ON `housekeeper` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;'); 
        $this->addSql('CREATE TRIGGER `linen_log_insert` BEFORE INSERT ON `linen` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `linen_log_update` BEFORE UPDATE ON `linen` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');  
        $this->addSql('CREATE TRIGGER `property_log_insert` BEFORE INSERT ON `property` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `property_log_update` BEFORE UPDATE ON `property` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');   
        $this->addSql('CREATE TRIGGER `property_photo_log_insert` BEFORE INSERT ON `property_photo` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `property_photo_log_update` BEFORE UPDATE ON `property_photo` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `supply_log_insert` BEFORE INSERT ON `supply` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `supply_log_update` BEFORE UPDATE ON `supply` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');  
        $this->addSql('CREATE TRIGGER `user_log_insert` BEFORE INSERT ON `user` FOR EACH ROW BEGIN SET NEW.created = NOW(); SET NEW.modified = NOW(); END;');
        $this->addSql('CREATE TRIGGER `user_log_update` BEFORE UPDATE ON `user` FOR EACH ROW BEGIN SET NEW.modified = NOW(); END;');                                                           
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TRIGGER `clean_log_insert`;');
        $this->addSql('DROP TRIGGER `clean_log_update`;'); 
        $this->addSql('DROP TRIGGER `clean_housekeeper_log_insert`;');
        $this->addSql('DROP TRIGGER `clean_housekeeper_log_update`;');  
        $this->addSql('DROP TRIGGER `clean_linen_log_insert`;');
        $this->addSql('DROP TRIGGER `clean_linen_log_update`;');
        $this->addSql('DROP TRIGGER `clean_photo_log_insert`;');
        $this->addSql('DROP TRIGGER `clean_photo_log_update`;'); 
        $this->addSql('DROP TRIGGER `clean_supply_log_insert`;');
        $this->addSql('DROP TRIGGER `clean_supply_log_update`;'); 
        $this->addSql('DROP TRIGGER `customer_log_insert`;');
        $this->addSql('DROP TRIGGER `customer_log_update`;');   
        $this->addSql('DROP TRIGGER `housekeeper_log_insert`;');
        $this->addSql('DROP TRIGGER `housekeeper_log_update`;'); 
        $this->addSql('DROP TRIGGER `linen_log_insert`;');
        $this->addSql('DROP TRIGGER `linen_log_update`;');  
        $this->addSql('DROP TRIGGER `property_log_insert`;');
        $this->addSql('DROP TRIGGER `property_log_update`;');   
        $this->addSql('DROP TRIGGER `property_photo_log_insert`;');
        $this->addSql('DROP TRIGGER `property_photo_log_update`;');
        $this->addSql('DROP TRIGGER `supply_log_insert`;');
        $this->addSql('DROP TRIGGER `supply_log_update`;');  
        $this->addSql('DROP TRIGGER `user_log_insert`;');
        $this->addSql('DROP TRIGGER `user_log_update`;');                
    }
}
