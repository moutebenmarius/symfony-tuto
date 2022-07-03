<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516132436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, coach_id INT NOT NULL, date_seance DATETIME NOT NULL, type_seance VARCHAR(255) NOT NULL, limit_adherent INT NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_DF7DFD0E3C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E3C105691 FOREIGN KEY (coach_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD type_sport_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B361879B04 FOREIGN KEY (type_sport_id) REFERENCES type_sport (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B361879B04 ON utilisateur (type_sport_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE seance');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B361879B04');
        $this->addSql('DROP INDEX IDX_1D1C63B361879B04 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP type_sport_id');
    }
}
