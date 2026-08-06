<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260624072618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE84DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX IDX_B73E5EDC12469DE2 ON tree');
        $this->addSql('ALTER TABLE tree ADD tree_categories_id INT DEFAULT NULL, DROP category_id');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDCFB0B5410 FOREIGN KEY (tree_categories_id) REFERENCES tree_categories (id)');
        $this->addSql('CREATE INDEX IDX_B73E5EDCFB0B5410 ON tree (tree_categories_id)');
        $this->addSql('ALTER TABLE tree_attributes ADD CONSTRAINT FK_CA88667178B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_climates ADD CONSTRAINT FK_5B21B73B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A51378B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A513D8355341 FOREIGN KEY (disease_id) REFERENCES tree_diseases (id)');
        $this->addSql('ALTER TABLE tree_local_names ADD CONSTRAINT FK_9A29F62AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE98260155 FOREIGN KEY (region_id) REFERENCES tree_regions (id)');
        $this->addSql('ALTER TABLE tree_usages_user ADD CONSTRAINT FK_5DEA238F303EF3C6 FOREIGN KEY (tree_usages_id) REFERENCES tree_usages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_usages_user ADD CONSTRAINT FK_5DEA238FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_usages_tree ADD CONSTRAINT FK_6747AB1A303EF3C6 FOREIGN KEY (tree_usages_id) REFERENCES tree_usages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_usages_tree ADD CONSTRAINT FK_6747AB1A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC746B832');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE84DDC6B4');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE78B64A2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DC746B832');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9D86650F');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDCFB0B5410');
        $this->addSql('DROP INDEX IDX_B73E5EDCFB0B5410 ON tree');
        $this->addSql('ALTER TABLE tree ADD category_id INT NOT NULL, DROP tree_categories_id');
        $this->addSql('CREATE INDEX IDX_B73E5EDC12469DE2 ON tree (category_id)');
        $this->addSql('ALTER TABLE tree_attributes DROP FOREIGN KEY FK_CA88667178B64A2');
        $this->addSql('ALTER TABLE tree_climates DROP FOREIGN KEY FK_5B21B73B78B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A51378B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A513D8355341');
        $this->addSql('ALTER TABLE tree_local_names DROP FOREIGN KEY FK_9A29F62AC746B832');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE78B64A2');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE98260155');
        $this->addSql('ALTER TABLE tree_usages_tree DROP FOREIGN KEY FK_6747AB1A303EF3C6');
        $this->addSql('ALTER TABLE tree_usages_tree DROP FOREIGN KEY FK_6747AB1A78B64A2');
        $this->addSql('ALTER TABLE tree_usages_user DROP FOREIGN KEY FK_5DEA238F303EF3C6');
        $this->addSql('ALTER TABLE tree_usages_user DROP FOREIGN KEY FK_5DEA238FA76ED395');
    }
}
