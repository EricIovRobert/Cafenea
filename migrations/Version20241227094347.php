<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227094347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_ziua (id INT AUTO_INCREMENT NOT NULL, data_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_DBA1705437F5A13C (data_id), INDEX IDX_DBA1705419EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_ziua ADD CONSTRAINT FK_DBA1705437F5A13C FOREIGN KEY (data_id) REFERENCES data (id)');
        $this->addSql('ALTER TABLE client_ziua ADD CONSTRAINT FK_DBA1705419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_ziua DROP FOREIGN KEY FK_DBA1705437F5A13C');
        $this->addSql('ALTER TABLE client_ziua DROP FOREIGN KEY FK_DBA1705419EB6921');
        $this->addSql('DROP TABLE client_ziua');
    }
}
