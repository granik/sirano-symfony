<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731195156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql("INSERT INTO `customer_city` (`country`, `name`, `full_name`) VALUES ('Словения', 'Любляна', 'Любляна');");
        $this->addSql("INSERT INTO `customer_city` (`kladr_id`, `country`, `name`, `full_name`) VALUES ('9100000700000', 'Россия', 'Симферополь', 'Респ Крым, Симферополь');");
        
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `name` = 'Любляна') WHERE `city_name` = 'Slovenia';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '7800000000000') WHERE `city_name` = 'Тосненск';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '9100000700000') WHERE `city_name` = 'Крым';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '7700000000000') WHERE `city_name` = 'М';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '4000000100000') WHERE `city_name` = 'Калужская область';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '0900900000900') WHERE `city_name` = 'КЧР,  Хабезский р-н, а,Кош-Хабль';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '1300000300000') WHERE `city_name` = ' Рузаевка';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '6400000100000') WHERE `city_name` = 'Саратовская область';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '5800000100000') WHERE `city_name` = 'Пензенская обл.';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '4000000100000') WHERE `city_name` = 'Кузьмина';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '2200000100000') WHERE `city_name` = 'Алтайский край';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '5500000100000') WHERE `city_name` = 'Омская область';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '8600001000000') WHERE `city_name` = 'Сургутский район';");
        $this->addSql("UPDATE `customers` SET `city_id` = (SELECT `id` FROM `customer_city` WHERE `kladr_id` = '8600001000000') WHERE `id` = 3406;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
