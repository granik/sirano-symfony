<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506121517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE module_test_questions (id INT AUTO_INCREMENT NOT NULL, test_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, answer1 VARCHAR(255) NOT NULL, answer2 VARCHAR(255) NOT NULL, answer3 VARCHAR(255) NOT NULL, answer4 VARCHAR(255) NOT NULL, right_answer INT NOT NULL, INDEX IDX_D01F33051E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_test_questions ADD CONSTRAINT FK_D01F33051E5D0459 FOREIGN KEY (test_id) REFERENCES module_tests (id)');
        $this->addSql('ALTER TABLE module_tests DROP INDEX IDX_1B6C8C30AFC2B591, ADD UNIQUE INDEX UNIQ_1B6C8C30AFC2B591 (module_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE module_test_questions');
        $this->addSql('ALTER TABLE module_tests DROP INDEX UNIQ_1B6C8C30AFC2B591, ADD INDEX IDX_1B6C8C30AFC2B591 (module_id)');
    }
}
