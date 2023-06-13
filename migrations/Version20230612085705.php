<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612085705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER adress DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER zipcode DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER adress SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER zipcode SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city SET NOT NULL');
    }
}
