<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261111100243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mock_up (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE portfolio (id INT AUTO_INCREMENT NOT NULL, nom_image VARCHAR(50) NOT NULL, chemin VARCHAR(255) NOT NULL, legende VARCHAR(100) DEFAULT NULL, taille VARCHAR(255) DEFAULT NULL, border VARCHAR(255) NOT NULL, category_id INT NOT NULL, mock_up_id INT DEFAULT NULL, INDEX IDX_A9ED106212469DE2 (category_id), INDEX IDX_A9ED1062AD497FA7 (mock_up_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image_lien_sous_cat (portfolio_id INT NOT NULL, sous_categories_id INT NOT NULL, INDEX IDX_614338F7B96B5643 (portfolio_id), INDEX IDX_614338F79F751138 (sous_categories_id), PRIMARY KEY (portfolio_id, sous_categories_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sous_categories (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED106212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED1062AD497FA7 FOREIGN KEY (mock_up_id) REFERENCES mock_up (id)');
        $this->addSql('ALTER TABLE image_lien_sous_cat ADD CONSTRAINT FK_614338F7B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_lien_sous_cat ADD CONSTRAINT FK_614338F79F751138 FOREIGN KEY (sous_categories_id) REFERENCES sous_categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED106212469DE2');
        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED1062AD497FA7');
        $this->addSql('ALTER TABLE image_lien_sous_cat DROP FOREIGN KEY FK_614338F7B96B5643');
        $this->addSql('ALTER TABLE image_lien_sous_cat DROP FOREIGN KEY FK_614338F79F751138');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE mock_up');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE image_lien_sous_cat');
        $this->addSql('DROP TABLE sous_categories');
    }
}
