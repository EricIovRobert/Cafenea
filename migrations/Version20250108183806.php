<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108183806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE cafea_covim cafea_covim DOUBLE PRECISION DEFAULT NULL, CHANGE lapte lapte DOUBLE PRECISION DEFAULT NULL, CHANGE ceai ceai DOUBLE PRECISION DEFAULT NULL, CHANGE solubil solubil DOUBLE PRECISION DEFAULT NULL, CHANGE pahare_plastic pahare_plastic DOUBLE PRECISION DEFAULT NULL, CHANGE pahare_carton pahare_carton DOUBLE PRECISION DEFAULT NULL, CHANGE palete palete DOUBLE PRECISION DEFAULT NULL, CHANGE ciocolata ciocolata DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE cafea_covim cafea_covim INT DEFAULT NULL, CHANGE lapte lapte INT DEFAULT NULL, CHANGE ceai ceai INT DEFAULT NULL, CHANGE solubil solubil INT DEFAULT NULL, CHANGE pahare_plastic pahare_plastic INT DEFAULT NULL, CHANGE pahare_carton pahare_carton INT DEFAULT NULL, CHANGE palete palete INT DEFAULT NULL, CHANGE ciocolata ciocolata INT DEFAULT NULL');
    }
}
