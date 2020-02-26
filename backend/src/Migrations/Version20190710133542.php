<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710133542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE additional_specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Ассистент')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Ведущий научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Главный научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Декан ')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Доктор медицинских наук')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Доцент')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Доцент (учёное звание)')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Заведующий кафедрой')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Заведующий научно-исследовательским отделом (лабораторией, сектором)')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Кандидат медицинских наук')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Младший научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Преподаватель')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Проректор')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Профессор')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Профессор (учёное звание)')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Ректор ')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Старший научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Старший преподаватель')");
        $this->addSql("INSERT INTO `additional_specialty` (`name`) VALUES ('Ученый секретарь')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE additional_specialty');
    }
}
