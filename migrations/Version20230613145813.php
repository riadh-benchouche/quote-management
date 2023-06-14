<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613145813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categorie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE facture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE produit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categorie (id INT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, company_name VARCHAR(100) NOT NULL, email VARCHAR(100) DEFAULT NULL, lastname VARCHAR(100) DEFAULT NULL, firstname VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(5) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE devis (id INT NOT NULL, client_id INT NOT NULL, message TEXT DEFAULT NULL, date DATE NOT NULL, quantity INT NOT NULL, prix_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B27C52B19EB6921 ON devis (client_id)');
        $this->addSql('CREATE TABLE facture (id INT NOT NULL, client_id INT NOT NULL, message TEXT DEFAULT NULL, date DATE NOT NULL, quantity INT NOT NULL, echeance DATE NOT NULL, status VARCHAR(50) NOT NULL, prix_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE86641019EB6921 ON facture (client_id)');
        $this->addSql('CREATE TABLE paiement (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE produit (id INT NOT NULL, categorie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, prix_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('CREATE TABLE produit_devis (produit_id INT NOT NULL, devis_id INT NOT NULL, PRIMARY KEY(produit_id, devis_id))');
        $this->addSql('CREATE INDEX IDX_BBBBA2BFF347EFB ON produit_devis (produit_id)');
        $this->addSql('CREATE INDEX IDX_BBBBA2BF41DEFADA ON produit_devis (devis_id)');
        $this->addSql('CREATE TABLE produit_facture (produit_id INT NOT NULL, facture_id INT NOT NULL, PRIMARY KEY(produit_id, facture_id))');
        $this->addSql('CREATE INDEX IDX_387DEF69F347EFB ON produit_facture (produit_id)');
        $this->addSql('CREATE INDEX IDX_387DEF697F2DEE08 ON produit_facture (facture_id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit_devis ADD CONSTRAINT FK_BBBBA2BFF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit_devis ADD CONSTRAINT FK_BBBBA2BF41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit_facture ADD CONSTRAINT FK_387DEF69F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit_facture ADD CONSTRAINT FK_387DEF697F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categorie_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE facture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE produit_id_seq CASCADE');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit_devis DROP CONSTRAINT FK_BBBBA2BFF347EFB');
        $this->addSql('ALTER TABLE produit_devis DROP CONSTRAINT FK_BBBBA2BF41DEFADA');
        $this->addSql('ALTER TABLE produit_facture DROP CONSTRAINT FK_387DEF69F347EFB');
        $this->addSql('ALTER TABLE produit_facture DROP CONSTRAINT FK_387DEF697F2DEE08');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_devis');
        $this->addSql('DROP TABLE produit_facture');
    }
}
