<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110103722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE operatii (id INT AUTO_INCREMENT NOT NULL, data DATE NOT NULL, stoc_act TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operatii_produs (operatii_id INT NOT NULL, produs_id INT NOT NULL, INDEX IDX_4B49AE23501DBD9D (operatii_id), INDEX IDX_4B49AE23C7FC4C39 (produs_id), PRIMARY KEY(operatii_id, produs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operatii_produs ADD CONSTRAINT FK_4B49AE23501DBD9D FOREIGN KEY (operatii_id) REFERENCES operatii (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operatii_produs ADD CONSTRAINT FK_4B49AE23C7FC4C39 FOREIGN KEY (produs_id) REFERENCES produs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operatii_produs DROP FOREIGN KEY FK_4B49AE23501DBD9D');
        $this->addSql('ALTER TABLE operatii_produs DROP FOREIGN KEY FK_4B49AE23C7FC4C39');
        $this->addSql('DROP TABLE operatii');
        $this->addSql('DROP TABLE operatii_produs');
    }
}
