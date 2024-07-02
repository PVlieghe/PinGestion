<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702085900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE composition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE composition (id INT NOT NULL, piece_fabrique_id INT NOT NULL, piece_inter_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7F4347F569EE39 ON composition (piece_fabrique_id)');
        $this->addSql('CREATE INDEX IDX_C7F4347DC2AFF71 ON composition (piece_inter_id)');
        $this->addSql('ALTER TABLE composition ADD CONSTRAINT FK_C7F4347F569EE39 FOREIGN KEY (piece_fabrique_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE composition ADD CONSTRAINT FK_C7F4347DC2AFF71 FOREIGN KEY (piece_inter_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE composition_id_seq CASCADE');
        $this->addSql('ALTER TABLE composition DROP CONSTRAINT FK_C7F4347F569EE39');
        $this->addSql('ALTER TABLE composition DROP CONSTRAINT FK_C7F4347DC2AFF71');
        $this->addSql('DROP TABLE composition');
    }
}
