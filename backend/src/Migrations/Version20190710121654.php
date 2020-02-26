<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710121654 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE main_specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (1, 'Акушер')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (2, 'Акушер-гинеколог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (3, 'Аллерголог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (4, 'Анестезиолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (5, 'Анестезиолог-реаниматолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (6, 'Врач выездной бригады скорой медицинской помощи')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (7, 'Врач функциональной диагностики')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (8, 'Гастроэнтеролог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (9, 'Гематолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (10, 'Гинеколог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (11, 'Дерматолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (12, 'Иммунолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (13, 'Инфекционист')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (14, 'Кардиолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (15, 'Клиническая лабораторная диагностика')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (16, 'Клинический фармаколог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (17, 'Медицинская сестра')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (18, 'Научный сотрудник')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (19, 'Нефролог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (20, 'Онколог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (21, 'Организация здравоохранения')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (22, 'Оториноларинголог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (23, 'Офтальмолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (24, 'Провизор')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (25, 'Психиатр')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (26, 'Пульмонолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (27, 'Реаниматолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (28, 'Ревматолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (29, 'Рентгенолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (30, 'Терапевт')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (31, 'Фармацевт')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (32, 'Фельдшер')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (33, 'Физиотерапевт')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (34, 'Хирург')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (35, 'Эндокринолог')");
        $this->addSql("INSERT INTO `main_specialty` (`id`, `name`) VALUES (36, 'Другое')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE main_specialty');
    }
}
