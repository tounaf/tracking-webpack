<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919104153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auxiliaires ADD nom_prenom VARCHAR(50) NOT NULL, ADD adresse VARCHAR(50) NOT NULL, ADD email VARCHAR(50) NOT NULL, ADD telephone INT NOT NULL');
        $this->addSql('ALTER TABLE intervenant ADD nom_prenom VARCHAR(50) NOT NULL, ADD adresse VARCHAR(50) NOT NULL, ADD email VARCHAR(50) NOT NULL, ADD telephone INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auxiliaires DROP nom_prenom, DROP adresse, DROP email, DROP telephone');
        $this->addSql('ALTER TABLE intervenant DROP nom_prenom, DROP adresse, DROP email, DROP telephone');
    }
}
