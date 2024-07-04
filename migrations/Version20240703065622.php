<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703065622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ligne_real_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE realisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utilisation_piece_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ligne_real (id INT NOT NULL, operation_id INT NOT NULL, machine_id INT NOT NULL, poste_id INT NOT NULL, realisation_id INT NOT NULL, duree VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C84F5D744AC3583 ON ligne_real (operation_id)');
        $this->addSql('CREATE INDEX IDX_8C84F5D7F6B75B26 ON ligne_real (machine_id)');
        $this->addSql('CREATE INDEX IDX_8C84F5D7A0905086 ON ligne_real (poste_id)');
        $this->addSql('CREATE INDEX IDX_8C84F5D7B685E551 ON ligne_real (realisation_id)');
        $this->addSql('COMMENT ON COLUMN ligne_real.duree IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE realisation (id INT NOT NULL, gamme_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA5610ED2FD85F1 ON realisation (gamme_id)');
        $this->addSql('CREATE TABLE utilisation_piece (id INT NOT NULL, piece_id INT NOT NULL, operation_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_293E7BB0C40FCFA8 ON utilisation_piece (piece_id)');
        $this->addSql('CREATE INDEX IDX_293E7BB044AC3583 ON utilisation_piece (operation_id)');
        $this->addSql('ALTER TABLE ligne_real ADD CONSTRAINT FK_8C84F5D744AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_real ADD CONSTRAINT FK_8C84F5D7F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_real ADD CONSTRAINT FK_8C84F5D7A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_real ADD CONSTRAINT FK_8C84F5D7B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT FK_293E7BB0C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT FK_293E7BB044AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ligne_real_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE realisation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utilisation_piece_id_seq CASCADE');
        $this->addSql('ALTER TABLE ligne_real DROP CONSTRAINT FK_8C84F5D744AC3583');
        $this->addSql('ALTER TABLE ligne_real DROP CONSTRAINT FK_8C84F5D7F6B75B26');
        $this->addSql('ALTER TABLE ligne_real DROP CONSTRAINT FK_8C84F5D7A0905086');
        $this->addSql('ALTER TABLE ligne_real DROP CONSTRAINT FK_8C84F5D7B685E551');
        $this->addSql('ALTER TABLE realisation DROP CONSTRAINT FK_EAA5610ED2FD85F1');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT FK_293E7BB0C40FCFA8');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT FK_293E7BB044AC3583');
        $this->addSql('DROP TABLE ligne_real');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE utilisation_piece');
    }
}
