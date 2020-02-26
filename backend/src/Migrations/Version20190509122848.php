<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509122848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinical_analisis_slides (id INT AUTO_INCREMENT NOT NULL, clinical_analisis_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_B52FA17D20219A7D (clinical_analisis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinical_analyzes_articles (clinical_analysis_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F41D3D5D47FF06B3 (clinical_analysis_id), INDEX IDX_F41D3D5D7294869C (article_id), PRIMARY KEY(clinical_analysis_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinical_analisis_slides ADD CONSTRAINT FK_B52FA17D20219A7D FOREIGN KEY (clinical_analisis_id) REFERENCES clinical_analyzes (id)');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D47FF06B3 FOREIGN KEY (clinical_analysis_id) REFERENCES clinical_analyzes (id)');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE clinical_analisis_slides');
        $this->addSql('DROP TABLE clinical_analyzes_articles');
    }
}
