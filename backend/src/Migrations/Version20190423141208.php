<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423141208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE directions DROP FOREIGN KEY FK_495867EC3DA5256D');
        $this->addSql('ALTER TABLE directions DROP FOREIGN KEY FK_495867EC54B9D732');
        $this->addSql('DROP INDEX IDX_495867EC3DA5256D ON directions');
        $this->addSql('DROP INDEX IDX_495867EC54B9D732 ON directions');
        $this->addSql('ALTER TABLE directions ADD icon VARCHAR(255) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, DROP icon_id, DROP image_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE directions ADD icon_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, DROP icon, DROP image');
        $this->addSql('ALTER TABLE directions ADD CONSTRAINT FK_495867EC3DA5256D FOREIGN KEY (image_id) REFERENCES files (id)');
        $this->addSql('ALTER TABLE directions ADD CONSTRAINT FK_495867EC54B9D732 FOREIGN KEY (icon_id) REFERENCES files (id)');
        $this->addSql('CREATE INDEX IDX_495867EC3DA5256D ON directions (image_id)');
        $this->addSql('CREATE INDEX IDX_495867EC54B9D732 ON directions (icon_id)');
    }
}
