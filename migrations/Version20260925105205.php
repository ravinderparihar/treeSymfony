<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260925105205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Hindi/other-language translation tables for trees, uses and categories';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_translation (locale VARCHAR(10) NOT NULL, name VARCHAR(100) DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, INDEX IDX_3F2070412469DE2 (category_id), UNIQUE INDEX uniq_category_translation_locale (category_id, locale), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tree_translation (locale VARCHAR(10) NOT NULL, description LONGTEXT DEFAULT NULL, temperature_range LONGTEXT DEFAULT NULL, rainfall_requirement LONGTEXT DEFAULT NULL, altitude_range LONGTEXT DEFAULT NULL, leaf_type LONGTEXT DEFAULT NULL, flowering_season LONGTEXT DEFAULT NULL, harvest_time LONGTEXT DEFAULT NULL, production_per_tree LONGTEXT DEFAULT NULL, seed_treatment LONGTEXT DEFAULT NULL, nursery_method LONGTEXT DEFAULT NULL, planting_distance LONGTEXT DEFAULT NULL, fertilizer_schedule LONGTEXT DEFAULT NULL, irrigation_schedule LONGTEXT DEFAULT NULL, pruning_guide LONGTEXT DEFAULT NULL, common_diseases LONGTEXT DEFAULT NULL, common_insects LONGTEXT DEFAULT NULL, symptoms LONGTEXT DEFAULT NULL, treatment LONGTEXT DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_C395660178B64A2 (tree_id), UNIQUE INDEX uniq_tree_translation_locale (tree_id, locale), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uses_translation (locale VARCHAR(10) NOT NULL, title VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, uses_id INT NOT NULL, INDEX IDX_C0E4557F1FD2B4F0 (uses_id), UNIQUE INDEX uniq_uses_translation_locale (uses_id, locale), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F2070412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_translation ADD CONSTRAINT FK_C395660178B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uses_translation ADD CONSTRAINT FK_C0E4557F1FD2B4F0 FOREIGN KEY (uses_id) REFERENCES uses (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F2070412469DE2');
        $this->addSql('ALTER TABLE tree_translation DROP FOREIGN KEY FK_C395660178B64A2');
        $this->addSql('ALTER TABLE uses_translation DROP FOREIGN KEY FK_C0E4557F1FD2B4F0');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE tree_translation');
        $this->addSql('DROP TABLE uses_translation');
    }
}
