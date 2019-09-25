<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190918114142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cloture (id INT AUTO_INCREMENT NOT NULL, decision_litige_id INT DEFAULT NULL, niveau_decision_id INT DEFAULT NULL, nature_decision_id INT DEFAULT NULL, devise_id INT DEFAULT NULL, date_cloture DATE DEFAULT NULL, juridiction VARCHAR(30) DEFAULT NULL, sens_decision VARCHAR(50) DEFAULT NULL, risque VARCHAR(50) DEFAULT NULL, type_cloture VARCHAR(50) DEFAULT NULL, gain_condamnation VARCHAR(50) DEFAULT NULL, montant_gain_condamn INT DEFAULT NULL, montant_initial INT DEFAULT NULL, montant_intervenant INT DEFAULT NULL, montant_auxiliaires INT DEFAULT NULL, INDEX IDX_D5D0B568DB20060E (decision_litige_id), INDEX IDX_D5D0B568586F47B2 (niveau_decision_id), INDEX IDX_D5D0B568D3690D6D (nature_decision_id), INDEX IDX_D5D0B568F4445056 (devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloture ADD CONSTRAINT FK_D5D0B568DB20060E FOREIGN KEY (decision_litige_id) REFERENCES decision_cloture (id)');
        $this->addSql('ALTER TABLE cloture ADD CONSTRAINT FK_D5D0B568586F47B2 FOREIGN KEY (niveau_decision_id) REFERENCES niveau_decision (id)');
        $this->addSql('ALTER TABLE cloture ADD CONSTRAINT FK_D5D0B568D3690D6D FOREIGN KEY (nature_decision_id) REFERENCES nature_decision (id)');
        $this->addSql('ALTER TABLE cloture ADD CONSTRAINT FK_D5D0B568F4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cloture');
    }
}
