<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004094934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant ADD est_rattache_id INT NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1186E3298B FOREIGN KEY (est_rattache_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_D79F6B1186E3298B ON participant (est_rattache_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1186E3298B');
        $this->addSql('DROP INDEX IDX_D79F6B1186E3298B ON participant');
        $this->addSql('ALTER TABLE participant DROP est_rattache_id');
    }
}
