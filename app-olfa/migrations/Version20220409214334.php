<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409214334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie_matiere DROP FOREIGN KEY FK_D443754C8F5EA509');
        $this->addSql('DROP INDEX IDX_D443754C8F5EA509 ON partie_matiere');
        $this->addSql('ALTER TABLE partie_matiere DROP classe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie_matiere ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partie_matiere ADD CONSTRAINT FK_D443754C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_D443754C8F5EA509 ON partie_matiere (classe_id)');
    }
}
