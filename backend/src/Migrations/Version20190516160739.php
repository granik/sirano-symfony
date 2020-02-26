<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516160739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conference_programs DROP FOREIGN KEY FK_3408A364604B8382');
        $this->addSql('ALTER TABLE conference_programs ADD CONSTRAINT FK_3408A364604B8382 FOREIGN KEY (conference_id) REFERENCES conferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference_subscribers DROP FOREIGN KEY conference_subscribers_customer_id');
        $this->addSql('ALTER TABLE conference_subscribers DROP FOREIGN KEY FK_8A393B70604B8382');
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT FK_8A393B709395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT FK_8A393B70604B8382 FOREIGN KEY (conference_id) REFERENCES conferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_slides DROP FOREIGN KEY FK_12C360BEAFC2B591');
        $this->addSql('ALTER TABLE module_slides ADD CONSTRAINT FK_12C360BEAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_tests DROP FOREIGN KEY FK_1B6C8C30AFC2B591');
        $this->addSql('ALTER TABLE module_tests ADD CONSTRAINT FK_1B6C8C30AFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_test_questions DROP FOREIGN KEY FK_D01F33051E5D0459');
        $this->addSql('ALTER TABLE module_test_questions ADD CONSTRAINT FK_D01F33051E5D0459 FOREIGN KEY (test_id) REFERENCES module_tests (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_test_results DROP FOREIGN KEY module_test_results_customer_id');
        $this->addSql('ALTER TABLE module_test_results DROP FOREIGN KEY FK_2BB1A82EAFC2B591');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT FK_2BB1A82E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT FK_2BB1A82EAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinical_analisis_slides DROP FOREIGN KEY FK_B52FA17D20219A7D');
        $this->addSql('ALTER TABLE clinical_analisis_slides ADD CONSTRAINT FK_B52FA17D20219A7D FOREIGN KEY (clinical_analisis_id) REFERENCES clinical_analyzes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE presidum_members ADD number INT NOT NULL');
        $this->addSql('ALTER TABLE webinar_reports DROP FOREIGN KEY FK_9B03AEEBA391D86E');
        $this->addSql('ALTER TABLE webinar_reports ADD CONSTRAINT FK_9B03AEEBA391D86E FOREIGN KEY (webinar_id) REFERENCES webinars (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_subscribers DROP FOREIGN KEY webinar_subscribers_customer_id');
        $this->addSql('ALTER TABLE webinar_subscribers DROP FOREIGN KEY FK_C6B8960EA391D86E');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT FK_C6B8960E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT FK_C6B8960EA391D86E FOREIGN KEY (webinar_id) REFERENCES webinars (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinical_analyzes_articles DROP FOREIGN KEY FK_F41D3D5D47FF06B3');
        $this->addSql('ALTER TABLE clinical_analyzes_articles DROP FOREIGN KEY FK_F41D3D5D7294869C');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D47FF06B3 FOREIGN KEY (clinical_analysis_id) REFERENCES clinical_analyzes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modules_articles DROP FOREIGN KEY modules_articles_article_id');
        $this->addSql('ALTER TABLE modules_articles DROP FOREIGN KEY modules_articles_module_id');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT FK_9E1D889EAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT FK_9E1D889E7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clinical_analisis_slides DROP FOREIGN KEY FK_B52FA17D20219A7D');
        $this->addSql('ALTER TABLE clinical_analisis_slides ADD CONSTRAINT FK_B52FA17D20219A7D FOREIGN KEY (clinical_analisis_id) REFERENCES clinical_analyzes (id)');
        $this->addSql('ALTER TABLE clinical_analyzes_articles DROP FOREIGN KEY FK_F41D3D5D47FF06B3');
        $this->addSql('ALTER TABLE clinical_analyzes_articles DROP FOREIGN KEY FK_F41D3D5D7294869C');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D47FF06B3 FOREIGN KEY (clinical_analysis_id) REFERENCES clinical_analyzes (id)');
        $this->addSql('ALTER TABLE clinical_analyzes_articles ADD CONSTRAINT FK_F41D3D5D7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE conference_programs DROP FOREIGN KEY FK_3408A364604B8382');
        $this->addSql('ALTER TABLE conference_programs ADD CONSTRAINT FK_3408A364604B8382 FOREIGN KEY (conference_id) REFERENCES conferences (id)');
        $this->addSql('ALTER TABLE conference_subscribers DROP FOREIGN KEY FK_8A393B709395C3F3');
        $this->addSql('ALTER TABLE conference_subscribers DROP FOREIGN KEY FK_8A393B70604B8382');
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT conference_subscribers_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference_subscribers ADD CONSTRAINT FK_8A393B70604B8382 FOREIGN KEY (conference_id) REFERENCES conferences (id)');
        $this->addSql('ALTER TABLE module_slides DROP FOREIGN KEY FK_12C360BEAFC2B591');
        $this->addSql('ALTER TABLE module_slides ADD CONSTRAINT FK_12C360BEAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE module_test_questions DROP FOREIGN KEY FK_D01F33051E5D0459');
        $this->addSql('ALTER TABLE module_test_questions ADD CONSTRAINT FK_D01F33051E5D0459 FOREIGN KEY (test_id) REFERENCES module_tests (id)');
        $this->addSql('ALTER TABLE module_test_results DROP FOREIGN KEY FK_2BB1A82E9395C3F3');
        $this->addSql('ALTER TABLE module_test_results DROP FOREIGN KEY FK_2BB1A82EAFC2B591');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT module_test_results_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_test_results ADD CONSTRAINT FK_2BB1A82EAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE module_tests DROP FOREIGN KEY FK_1B6C8C30AFC2B591');
        $this->addSql('ALTER TABLE module_tests ADD CONSTRAINT FK_1B6C8C30AFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE modules_articles DROP FOREIGN KEY FK_9E1D889EAFC2B591');
        $this->addSql('ALTER TABLE modules_articles DROP FOREIGN KEY FK_9E1D889E7294869C');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT modules_articles_article_id FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modules_articles ADD CONSTRAINT modules_articles_module_id FOREIGN KEY (module_id) REFERENCES modules (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE presidum_members DROP number');
        $this->addSql('ALTER TABLE webinar_reports DROP FOREIGN KEY FK_9B03AEEBA391D86E');
        $this->addSql('ALTER TABLE webinar_reports ADD CONSTRAINT FK_9B03AEEBA391D86E FOREIGN KEY (webinar_id) REFERENCES webinars (id)');
        $this->addSql('ALTER TABLE webinar_subscribers DROP FOREIGN KEY FK_C6B8960E9395C3F3');
        $this->addSql('ALTER TABLE webinar_subscribers DROP FOREIGN KEY FK_C6B8960EA391D86E');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT webinar_subscribers_customer_id FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE webinar_subscribers ADD CONSTRAINT FK_C6B8960EA391D86E FOREIGN KEY (webinar_id) REFERENCES webinars (id)');
    }
}
