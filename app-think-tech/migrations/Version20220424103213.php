<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424103213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD solution_id INT NOT NULL, ADD qte INT NOT NULL, ADD prix_total VARCHAR(255) NOT NULL, DROP date_commande');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D1C0BE183 FOREIGN KEY (solution_id) REFERENCES solution (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D1C0BE183 ON commande (solution_id)');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB82EA2E54');
        $this->addSql('DROP INDEX IDX_9F3329DB82EA2E54 ON solution');
        $this->addSql('ALTER TABLE solution DROP commande_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D1C0BE183');
        $this->addSql('DROP INDEX IDX_6EEAA67D1C0BE183 ON commande');
        $this->addSql('ALTER TABLE commande ADD date_commande DATE NOT NULL, DROP solution_id, DROP qte, DROP prix_total');
        $this->addSql('ALTER TABLE solution ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_9F3329DB82EA2E54 ON solution (commande_id)');
    }
}
