<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409214532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie_matiere ADD cahier_texte_id INT NOT NULL, ADD est_vu_par_directeur TINYINT(1) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE partie_matiere ADD CONSTRAINT FK_D443754C85D2D268 FOREIGN KEY (cahier_texte_id) REFERENCES cahier_texte (id)');
        $this->addSql('CREATE INDEX IDX_D443754C85D2D268 ON partie_matiere (cahier_texte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie_matiere DROP FOREIGN KEY FK_D443754C85D2D268');
        $this->addSql('DROP INDEX IDX_D443754C85D2D268 ON partie_matiere');
        $this->addSql('ALTER TABLE partie_matiere DROP cahier_texte_id, DROP est_vu_par_directeur, DROP description');
    }
}
