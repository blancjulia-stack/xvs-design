<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261028151627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portfolio ADD nom_image VARCHAR(255) NOT NULL, ADD chemin VARCHAR(255) NOT NULL, ADD categorie VARCHAR(255) NOT NULL, ADD sous_categorie VARCHAR(255) NOT NULL, ADD mock_up VARCHAR(255) NOT NULL, ADD legende VARCHAR(255) DEFAULT NULL, ADD taille VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portfolio DROP nom_image, DROP chemin, DROP categorie, DROP sous_categorie, DROP mock_up, DROP legende, DROP taille');
    }
}
