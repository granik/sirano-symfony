<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618130503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_test_questions CHANGE question question LONGTEXT NOT NULL, CHANGE answer1 answer1 LONGTEXT NOT NULL, CHANGE answer2 answer2 LONGTEXT NOT NULL, CHANGE answer3 answer3 LONGTEXT NOT NULL, CHANGE answer4 answer4 LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_test_questions CHANGE question question VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE answer1 answer1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE answer2 answer2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE answer3 answer3 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE answer4 answer4 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
