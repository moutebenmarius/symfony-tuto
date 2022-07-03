<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404144926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partie_matiere (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, INDEX IDX_D443754CE455FCC0 (enseignant_id), INDEX IDX_D443754CF46CD258 (matiere_id), INDEX IDX_D443754C8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partie_matiere ADD CONSTRAINT FK_D443754CE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE partie_matiere ADD CONSTRAINT FK_D443754CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE partie_matiere ADD CONSTRAINT FK_D443754C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE partie_matiere');
    }
}
