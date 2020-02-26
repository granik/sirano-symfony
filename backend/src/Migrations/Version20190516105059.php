<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516105059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `conference_subscribers` DROP FOREIGN KEY `FK_8A393B709395C3F3`');
        $this->addSql('ALTER TABLE `module_test_results` DROP FOREIGN KEY `FK_2BB1A82E9395C3F3`');
        $this->addSql('ALTER TABLE `webinar_subscribers` DROP FOREIGN KEY `FK_C6B8960E9395C3F3`');
        
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT conference_subscribers_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT module_test_results_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT webinar_subscribers_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `conference_subscribers` DROP FOREIGN KEY `conference_subscribers_customer_id`');
        $this->addSql('ALTER TABLE `module_test_results` DROP FOREIGN KEY `module_test_results_customer_id`');
        $this->addSql('ALTER TABLE `webinar_subscribers` DROP FOREIGN KEY `webinar_subscribers_customer_id`');
    
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT FK_8A393B709395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT FK_2BB1A82E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT FK_C6B8960E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }
}
