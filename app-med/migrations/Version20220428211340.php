<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428211340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, dossier_id INT NOT NULL, medecin_id INT DEFAULT NULL, labo_id INT DEFAULT NULL, pharmacie_id INT DEFAULT NULL, centre_imagerie_id INT DEFAULT NULL, description LONGTEXT NOT NULL, INDEX IDX_433224D2611C0C56 (dossier_id), INDEX IDX_433224D24F31A84 (medecin_id), INDEX IDX_433224D2B65FA4A (labo_id), INDEX IDX_433224D2BC6D351B (pharmacie_id), INDEX IDX_433224D22EC4A459 (centre_imagerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D2611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier_medical (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D24F31A84 FOREIGN KEY (medecin_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D2B65FA4A FOREIGN KEY (labo_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D2BC6D351B FOREIGN KEY (pharmacie_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D22EC4A459 FOREIGN KEY (centre_imagerie_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recommendation');
    }
}
