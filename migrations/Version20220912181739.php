<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912181739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23979F37AE5');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2391E5FEC79');
        $this->addSql('DROP INDEX IDX_4DA23979F37AE5 ON reservations');
        $this->addSql('DROP INDEX IDX_4DA2391E5FEC79 ON reservations');
        $this->addSql('ALTER TABLE reservations ADD id_user INT NOT NULL, ADD id_location INT NOT NULL, DROP id_user_id, DROP id_location_id');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2396B3CA4B FOREIGN KEY (id_user) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239E45655E FOREIGN KEY (id_location) REFERENCES locations (id)');
        $this->addSql('CREATE INDEX IDX_4DA2396B3CA4B ON reservations (id_user)');
        $this->addSql('CREATE INDEX IDX_4DA239E45655E ON reservations (id_location)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2396B3CA4B');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239E45655E');
        $this->addSql('DROP INDEX IDX_4DA2396B3CA4B ON reservations');
        $this->addSql('DROP INDEX IDX_4DA239E45655E ON reservations');
        $this->addSql('ALTER TABLE reservations ADD id_user_id INT NOT NULL, ADD id_location_id INT NOT NULL, DROP id_user, DROP id_location');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23979F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2391E5FEC79 FOREIGN KEY (id_location_id) REFERENCES locations (id)');
        $this->addSql('CREATE INDEX IDX_4DA23979F37AE5 ON reservations (id_user_id)');
        $this->addSql('CREATE INDEX IDX_4DA2391E5FEC79 ON reservations (id_location_id)');
    }
}
