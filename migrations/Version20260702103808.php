<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702103808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP created_at');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE84DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D64B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tree DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
        $this->addSql('ALTER TABLE tree_attributes ADD CONSTRAINT FK_CA88667178B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_categories DROP created_at');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D12469DE2 FOREIGN KEY (category_id) REFERENCES tree_categories (id)');
        $this->addSql('ALTER TABLE tree_climates ADD CONSTRAINT FK_5B21B73B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A51378B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A513D8355341 FOREIGN KEY (disease_id) REFERENCES tree_diseases (id)');
        $this->addSql('ALTER TABLE tree_local_names DROP created_at');
        $this->addSql('ALTER TABLE tree_local_names ADD CONSTRAINT FK_9A29F62A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE98260155 FOREIGN KEY (region_id) REFERENCES tree_regions (id)');
        $this->addSql('ALTER TABLE tree_uses DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EFD55516ED FOREIGN KEY (tree_uses_id) REFERENCES tree_uses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EF78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A78B64A2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE comments ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE84DDC6B4');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE78B64A2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D8BE3022C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D64B64DCC');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
        $this->addSql('ALTER TABLE tree ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_attributes DROP FOREIGN KEY FK_CA88667178B64A2');
        $this->addSql('ALTER TABLE tree_categories ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D78B64A2');
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D12469DE2');
        $this->addSql('ALTER TABLE tree_climates DROP FOREIGN KEY FK_5B21B73B78B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A51378B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A513D8355341');
        $this->addSql('ALTER TABLE tree_local_names DROP FOREIGN KEY FK_9A29F62A78B64A2');
        $this->addSql('ALTER TABLE tree_local_names ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE78B64A2');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE98260155');
        $this->addSql('ALTER TABLE tree_uses ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EFD55516ED');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EF78B64A2');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
    }
}
