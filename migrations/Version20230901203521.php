<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230901203521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE honoraire CHANGE client_id client_id INT DEFAULT NULL, CHANGE objet objet VARCHAR(255) DEFAULT NULL, CHANGE montantht montant_ht DOUBLE PRECISION NOT NULL, CHANGE montantttc montant_ttc DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE honoraire CHANGE client_id client_id INT NOT NULL, CHANGE objet objet VARCHAR(255) NOT NULL, CHANGE montant_ht montantht DOUBLE PRECISION NOT NULL, CHANGE montant_ttc montantttc DOUBLE PRECISION DEFAULT NULL');
    }
}
