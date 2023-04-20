<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420121105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizz ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7C77973DB03A8386 ON quizz (created_by_id)');
        $this->addSql('CREATE INDEX IDX_7C77973D896DBBDE ON quizz (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizz DROP CONSTRAINT FK_7C77973DB03A8386');
        $this->addSql('ALTER TABLE quizz DROP CONSTRAINT FK_7C77973D896DBBDE');
        $this->addSql('DROP INDEX IDX_7C77973DB03A8386');
        $this->addSql('DROP INDEX IDX_7C77973D896DBBDE');
        $this->addSql('ALTER TABLE quizz DROP created_by_id');
        $this->addSql('ALTER TABLE quizz DROP updated_by_id');
    }
}
