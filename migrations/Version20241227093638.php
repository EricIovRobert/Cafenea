<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227093638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, citire_anterioara INT NOT NULL, citire_actuala INT NOT NULL, probe INT DEFAULT NULL, pret DOUBLE PRECISION NOT NULL, cafea_covim INT DEFAULT NULL, cafea_lavazza INT DEFAULT NULL, zahar INT DEFAULT NULL, lapte INT DEFAULT NULL, ceai INT DEFAULT NULL, solubil INT DEFAULT NULL, pahare_plastic INT DEFAULT NULL, pahare_carton INT DEFAULT NULL, palete INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client');
    }
}
