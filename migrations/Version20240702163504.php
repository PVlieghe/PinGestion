<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702163504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE utilisation_piece_id_seq CASCADE');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT fk_293e7bb0c40fcfa8');
        $this->addSql('ALTER TABLE utilisation_piece DROP CONSTRAINT fk_293e7bb044ac3583');
        $this->addSql('DROP TABLE utilisation_piece');
        $this->addSql('ALTER TABLE piece ALTER intermediaire DROP NOT NULL');
        $this->addSql('ALTER TABLE piece ALTER fabrique DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE utilisation_piece_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE utilisation_piece (id INT NOT NULL, piece_id INT NOT NULL, operation_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_293e7bb044ac3583 ON utilisation_piece (operation_id)');
        $this->addSql('CREATE INDEX idx_293e7bb0c40fcfa8 ON utilisation_piece (piece_id)');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT fk_293e7bb0c40fcfa8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_piece ADD CONSTRAINT fk_293e7bb044ac3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE piece ALTER intermediaire SET NOT NULL');
        $this->addSql('ALTER TABLE piece ALTER fabrique SET NOT NULL');
    }
}
