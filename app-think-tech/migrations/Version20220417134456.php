<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417134456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_formation (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, apprenant_id INT NOT NULL, date_inscription DATETIME NOT NULL, est_accepte TINYINT(1) DEFAULT NULL, INDEX IDX_17284BE35200282E (formation_id), INDEX IDX_17284BE3C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_formation ADD CONSTRAINT FK_17284BE35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE apprenant_formation ADD CONSTRAINT FK_17284BE3C5697D6D FOREIGN KEY (apprenant_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE apprenant_formation');
    }
}
