<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514133634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `conference_programs` DROP FOREIGN KEY `FK_3408A364604B8382`');
        $this->addSql('ALTER TABLE `conference_programs` ADD CONSTRAINT `conference_programs_conference_id` FOREIGN KEY (`conference_id`) REFERENCES `conferences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `conference_programs_conference_id` DROP FOREIGN KEY `FK_3408A364604B8382`');
        $this->addSql('ALTER TABLE conference_programs ADD CONSTRAINT FK_3408A364604B8382 FOREIGN KEY (conference_id) REFERENCES conferences (id)');
    }
}
