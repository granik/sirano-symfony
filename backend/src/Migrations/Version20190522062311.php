<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190522062311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE `customers` CHANGE `direction_id` `direction_id` INT DEFAULT NULL');
        $this->addSql('UPDATE `customers` SET `direction_id` = NULL WHERE `direction_id` = 0');
        $this->addSql('ALTER TABLE `customers` ADD CONSTRAINT `customers_direction_id` FOREIGN KEY (`direction_id`) REFERENCES `directions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE `customers` DROP FOREIGN KEY `customers_direction_id`');
        $this->addSql('ALTER TABLE `customers` CHANGE `direction_id` `direction_id` INT NOT NULL');
    }
}
