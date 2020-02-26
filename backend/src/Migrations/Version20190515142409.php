<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515142409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `modules_articles` DROP FOREIGN KEY `FK_9E1D889E7294869C`');
        $this->addSql('ALTER TABLE `modules_articles` DROP FOREIGN KEY `FK_9E1D889EAFC2B591`');
        $this->addSql('ALTER TABLE `modules_articles` ADD CONSTRAINT `modules_articles_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE `modules_articles` ADD CONSTRAINT `modules_articles_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `modules_articles` DROP FOREIGN KEY `modules_articles_module_id`');
        $this->addSql('ALTER TABLE `modules_articles` DROP FOREIGN KEY `modules_articles_article_id`');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT FK_9E1D889EAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT FK_9E1D889E7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }
}
