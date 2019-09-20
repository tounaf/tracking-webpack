<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919120344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auxiliaires DROP FOREIGN KEY FK_6900F1B8A76ED395');
        $this->addSql('DROP INDEX IDX_6900F1B8A76ED395 ON auxiliaires');
        $this->addSql('ALTER TABLE auxiliaires DROP user_id');
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CA76ED395');
        $this->addSql('DROP INDEX IDX_73D0145CA76ED395 ON intervenant');
        $this->addSql('ALTER TABLE intervenant CHANGE user_id dossier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145C611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('CREATE INDEX IDX_73D0145C611C0C56 ON intervenant (dossier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auxiliaires ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auxiliaires ADD CONSTRAINT FK_6900F1B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6900F1B8A76ED395 ON auxiliaires (user_id)');
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145C611C0C56');
        $this->addSql('DROP INDEX IDX_73D0145C611C0C56 ON intervenant');
        $this->addSql('ALTER TABLE intervenant CHANGE dossier_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_73D0145CA76ED395 ON intervenant (user_id)');
    }
}
