<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511122859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conferences CHANGE description description VARCHAR(600) DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE announce announce VARCHAR(600) NOT NULL, CHANGE text text VARCHAR(600) NOT NULL');
        $this->addSql('ALTER TABLE presidum_members CHANGE description description VARCHAR(600) NOT NULL');
        $this->addSql('ALTER TABLE webinars CHANGE description description VARCHAR(600) DEFAULT NULL');
        $this->addSql('ALTER TABLE webinar_reports CHANGE description description VARCHAR(600) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conferences CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE news CHANGE announce announce VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE text text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE presidum_members CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE webinar_reports CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE webinars CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
