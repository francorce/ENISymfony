<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004093521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu ADD nom_ville_id INT NOT NULL');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D593275C5BF FOREIGN KEY (nom_ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_2F577D593275C5BF ON lieu (nom_ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D593275C5BF');
        $this->addSql('DROP INDEX IDX_2F577D593275C5BF ON lieu');
        $this->addSql('ALTER TABLE lieu DROP nom_ville_id');
    }
}
