<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260820103639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree ADD temperature_range VARCHAR(255) DEFAULT NULL, ADD rainfall_requirement VARCHAR(255) DEFAULT NULL, ADD water_requirement VARCHAR(255) DEFAULT NULL, ADD humidity VARCHAR(255) DEFAULT NULL, ADD altitude_range VARCHAR(255) DEFAULT NULL, ADD sandy_soil TINYINT DEFAULT NULL, ADD clay_soil TINYINT DEFAULT NULL, ADD loamy_soil TINYINT DEFAULT NULL, ADD soil_ph VARCHAR(255) DEFAULT NULL, ADD leaf_type VARCHAR(255) DEFAULT NULL, ADD flowering_season VARCHAR(255) DEFAULT NULL, ADD harvest_time VARCHAR(255) DEFAULT NULL, ADD production_per_tree VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6978B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree DROP temperature_range, DROP rainfall_requirement, DROP water_requirement, DROP humidity, DROP altitude_range, DROP sandy_soil, DROP clay_soil, DROP loamy_soil, DROP soil_ph, DROP leaf_type, DROP flowering_season, DROP harvest_time, DROP production_per_tree');
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6978B64A2');
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6912469DE2');
    }
}
