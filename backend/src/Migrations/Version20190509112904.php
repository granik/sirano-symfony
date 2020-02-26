<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509112904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinical_analyzes (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, direction_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, is_active TINYINT(1) NOT NULL, company_email VARCHAR(255) DEFAULT NULL, lecturer_email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E34C0A68AFC2B591 (module_id), INDEX IDX_E34C0A68AF73D997 (direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinical_analyzes ADD CONSTRAINT FK_E34C0A68AFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE clinical_analyzes ADD CONSTRAINT FK_E34C0A68AF73D997 FOREIGN KEY (direction_id) REFERENCES directions (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE clinical_analyzes');
    }
}
