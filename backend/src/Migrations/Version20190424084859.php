<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424084859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE webinars ADD direction_id INT DEFAULT NULL, ADD subject VARCHAR(255) DEFAULT NULL, ADD score INT NOT NULL, ADD confirmation_time1 DATETIME NOT NULL, ADD confirmation_time2 DATETIME NOT NULL, ADD confirmation_time3 DATETIME DEFAULT NULL, ADD email VARCHAR(255) NOT NULL, ADD is_active TINYINT(1) NOT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE webinars ADD CONSTRAINT FK_8DCE851AAF73D997 FOREIGN KEY (direction_id) REFERENCES directions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DCE851AAF73D997 ON webinars (direction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE webinars DROP FOREIGN KEY FK_8DCE851AAF73D997');
        $this->addSql('DROP INDEX UNIQ_8DCE851AAF73D997 ON webinars');
        $this->addSql('ALTER TABLE webinars DROP direction_id, DROP subject, DROP score, DROP confirmation_time1, DROP confirmation_time2, DROP confirmation_time3, DROP email, DROP is_active, CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
