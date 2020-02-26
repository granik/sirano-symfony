<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190806165643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql("INSERT INTO `customer_city` (`kladr_id`, `country`, `name`, `full_name`) VALUES ('4700500100000', 'Россия', 'Всеволожск', 'Ленинградская обл, Всеволожск');");
        
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '7800000000000') WHERE `city_name` = 'Sankt-Petersburg';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '4700500100000') WHERE `city_name` = 'Всеволожск';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '7200000100000') WHERE `id` = 3352;");
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
