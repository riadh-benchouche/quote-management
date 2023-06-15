<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615102520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe86641069678373');
        $this->addSql('DROP INDEX idx_fe86641069678373');
        $this->addSql('ALTER TABLE facture RENAME COLUMN devis_id_id TO devis_id');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641041DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FE86641041DEFADA ON facture (devis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE86641041DEFADA');
        $this->addSql('DROP INDEX IDX_FE86641041DEFADA');
        $this->addSql('ALTER TABLE facture RENAME COLUMN devis_id TO devis_id_id');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe86641069678373 FOREIGN KEY (devis_id_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fe86641069678373 ON facture (devis_id_id)');
    }
}
