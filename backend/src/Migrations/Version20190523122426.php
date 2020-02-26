<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190523122426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conferences ADD series_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conferences ADD CONSTRAINT FK_8E2090BA5278319C FOREIGN KEY (series_id) REFERENCES conference_series (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_8E2090BA5278319C ON conferences (series_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conferences DROP FOREIGN KEY FK_8E2090BA5278319C');
        $this->addSql('DROP INDEX IDX_8E2090BA5278319C ON conferences');
        $this->addSql('ALTER TABLE conferences DROP series_id');
    }
}
