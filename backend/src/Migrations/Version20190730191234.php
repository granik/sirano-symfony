<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730191234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('TRUNCATE TABLE `additional_specialty`;');
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (1, 'Ассистент')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (2, 'Ведущий научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (3, 'Главный научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (4, 'Декан')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (5, 'Доктор медицинских наук')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (6, 'Доцент')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (7, 'Доцент (учёное звание)')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (8, 'Заведующий кафедрой')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (9, 'Заведующий научно-исследовательским отделом (лабораторией, сектором)')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (10, 'Кандидат медицинских наук')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (11, 'Младший научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (12, 'Научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (13, 'Преподаватель')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (14, 'Проректор')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (15, 'Профессор')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (16, 'Профессор (учёное звание)')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (17, 'Ректор')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (18, 'Старший научный сотрудник')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (19, 'Старший преподаватель')");
        $this->addSql("INSERT INTO `additional_specialty` (`id`, `name`) VALUES (20, 'Ученый секретарь')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
