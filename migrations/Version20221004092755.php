<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004092755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie ADD état_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2A8433C3 FOREIGN KEY (état_id) REFERENCES etat (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2A8433C3 ON sortie (état_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2A8433C3');
        $this->addSql('DROP INDEX IDX_3C3FD3F2A8433C3 ON sortie');
        $this->addSql('ALTER TABLE sortie DROP état_id');
    }
}
