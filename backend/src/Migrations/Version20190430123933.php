<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430123933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conferences (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, city_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL, score INT NOT NULL, confirmation_time1 DATETIME NOT NULL, confirmation_time2 DATETIME NOT NULL, confirmation_time3 DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_8E2090BAAF73D997 (direction_id), INDEX IDX_8E2090BA8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conferences ADD CONSTRAINT FK_8E2090BAAF73D997 FOREIGN KEY (direction_id) REFERENCES directions (id)');
        $this->addSql('ALTER TABLE conferences ADD CONSTRAINT FK_8E2090BA8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE conferences');
    }
}
