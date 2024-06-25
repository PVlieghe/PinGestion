<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240623190805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE compo_gamme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE gamme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE machine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE piece_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE poste_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qualif_machine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qualif_operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qualif_poste_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utilisation_piece_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE compo_gamme (id INT NOT NULL, gamme_id INT NOT NULL, operation_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_60B8C10D2FD85F1 ON compo_gamme (gamme_id)');
        $this->addSql('CREATE INDEX IDX_60B8C1044AC3583 ON compo_gamme (operation_id)');
        $this->addSql('CREATE TABLE gamme (id INT NOT NULL, piece_id INT NOT NULL, referent_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C32E1468C40FCFA8 ON gamme (piece_id)');
        $this->addSql('CREATE INDEX IDX_C32E146835E47E35 ON gamme (referent_id)');
        $this->addSql('CREATE TABLE machine (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE piece (id INT NOT NULL, name VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, prix_vente DOUBLE PRECISION DEFAULT NULL, prix_achat DOUBLE PRECISION NOT NULL, intermediaire BOOLEAN NOT NULL, fabrique BOOLEAN NOT NULL, stock INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE poste (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE qualif_machine (id INT NOT NULL, poste_id INT NOT NULL, machine_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EEDDF6FFA0905086 ON qualif_machine (poste_id)');
        $this->addSql('CREATE INDEX IDX_EEDDF6FFF6B75B26 ON qualif_machine (machine_id)');
        $this->addSql('CREATE TABLE qualif_operation (id INT NOT NULL, machine_id INT NOT NULL, operation_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97F865E4F6B75B26 ON qualif_operation (machine_id)');
        $this->addSql('CREATE INDEX IDX_97F865E444AC3583 ON qualif_operation (operation_id)');
        $this->addSql('CREATE TABLE qualif_poste (id INT NOT NULL, poste_id INT NOT NULL, usr_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AF8C6860A0905086 ON qualif_poste (poste_id)');
        $this->addSql('CREATE INDEX IDX_AF8C6860C69D3FB ON qualif_poste (usr_id)');
        $this->addSql('CREATE TABLE utilisation_piece (id INT NOT NULL, piece_id INT NOT NULL, operation_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_293E7BB0C40FCFA8 ON utilisation_piece (piece_id)');
        $this->addSql('CREATE INDEX IDX_293E7BB044AC3583 ON utilisation_piece (operation_id)');
        $this->addSql('ALTER TABLE compo_gamme ADD CONSTRAINT FK_60B8C10D2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE compo_gamme ADD CONSTRAINT FK_60B8C1044AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE gamme ADD CONSTRAINT FK_C32E1468C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE gamme ADD CONSTRAINT FK_C32E146835E47E35 FOREIGN KEY (referent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_machine ADD CONSTRAINT FK_EEDDF6FFA0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_machine ADD CONSTRAINT FK_EEDDF6FFF6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_operation ADD CONSTRAINT FK_97F865E4F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_operation ADD CONSTRAINT FK_97F865E444AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_poste ADD CONSTRAINT FK_AF8C6860A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualif_poste ADD CONSTRAINT FK_AF8C6860C69D3FB FOREIGN KEY (usr_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT FK_293E7BB0C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT FK_293E7BB044AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE compo_gamme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE gamme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE machine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE piece_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE poste_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qualif_machine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qualif_operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qualif_poste_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utilisation_piece_id_seq CASCADE');
        $this->addSql('ALTER TABLE compo_gamme DROP CONSTRAINT FK_60B8C10D2FD85F1');
        $this->addSql('ALTER TABLE compo_gamme DROP CONSTRAINT FK_60B8C1044AC3583');
        $this->addSql('ALTER TABLE gamme DROP CONSTRAINT FK_C32E1468C40FCFA8');
        $this->addSql('ALTER TABLE gamme DROP CONSTRAINT FK_C32E146835E47E35');
        $this->addSql('ALTER TABLE qualif_machine DROP CONSTRAINT FK_EEDDF6FFA0905086');
        $this->addSql('ALTER TABLE qualif_machine DROP CONSTRAINT FK_EEDDF6FFF6B75B26');
        $this->addSql('ALTER TABLE qualif_operation DROP CONSTRAINT FK_97F865E4F6B75B26');
        $this->addSql('ALTER TABLE qualif_operation DROP CONSTRAINT FK_97F865E444AC3583');
        $this->addSql('ALTER TABLE qualif_poste DROP CONSTRAINT FK_AF8C6860A0905086');
        $this->addSql('ALTER TABLE qualif_poste DROP CONSTRAINT FK_AF8C6860C69D3FB');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT FK_293E7BB0C40FCFA8');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT FK_293E7BB044AC3583');
        $this->addSql('DROP TABLE compo_gamme');
        $this->addSql('DROP TABLE gamme');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE piece');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE qualif_machine');
        $this->addSql('DROP TABLE qualif_operation');
        $this->addSql('DROP TABLE qualif_poste');
        $this->addSql('DROP TABLE utilisation_piece');
    }
}
