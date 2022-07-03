<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521124725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien ADD est_supprime TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE offre_emploi ADD est_supprime TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE postuler ADD est_supprime TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD est_supprime TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien DROP est_supprime');
        $this->addSql('ALTER TABLE offre_emploi DROP est_supprime');
        $this->addSql('ALTER TABLE postuler DROP est_supprime');
        $this->addSql('ALTER TABLE utilisateur DROP est_supprime');
    }
}
