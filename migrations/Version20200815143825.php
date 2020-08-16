<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200815143825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, nickname VARCHAR(100) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, pays_id SMALLINT UNSIGNED NOT NULL, title VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, starting_at DATETIME NOT NULL, closing_at DATETIME NOT NULL, adress VARCHAR(255) NOT NULL, town VARCHAR(100) NOT NULL, zip_code VARCHAR(20) NOT NULL, entrance_fee INT DEFAULT NULL, INDEX IDX_3BAE0AA7A6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_artist (event_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_33C0E1D571F7E88B (event_id), INDEX IDX_33C0E1D5B7970CF8 (artist_id), PRIMARY KEY(event_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, artist_id INT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, music VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CD52224AB7970CF8 (artist_id), INDEX IDX_CD52224A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, code INT NOT NULL, alpha2 VARCHAR(2) NOT NULL, alpha3 VARCHAR(3) NOT NULL, nom_en_gb VARCHAR(45) NOT NULL, nom_fr_fr VARCHAR(45) NOT NULL, UNIQUE INDEX alpha3 (alpha3), UNIQUE INDEX alpha2 (alpha2), UNIQUE INDEX code_unique (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D5B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_artist DROP FOREIGN KEY FK_33C0E1D5B7970CF8');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AB7970CF8');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224A12469DE2');
        $this->addSql('ALTER TABLE event_artist DROP FOREIGN KEY FK_33C0E1D571F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A6E44244');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_artist');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE pays');
    }
}
