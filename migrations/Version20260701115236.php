<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260701115236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, tree_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_5F9E962A78B64A2 (tree_id), INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorites (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorites_tree (favorites_id INT NOT NULL, tree_id INT NOT NULL, INDEX IDX_2CFDC0AE84DDC6B4 (favorites_id), INDEX IDX_2CFDC0AE78B64A2 (tree_id), PRIMARY KEY (favorites_id, tree_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, treeId INT DEFAULT NULL, userId INT NOT NULL, INDEX IDX_49CA4E7D8BE3022C (treeId), INDEX IDX_49CA4E7D64B64DCC (userId), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE regions (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, district VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 7) DEFAULT NULL, longitude NUMERIC(10, 7) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree (id INT AUTO_INCREMENT NOT NULL, scientific_name LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, lifespan_min VARCHAR(255) NOT NULL, lifespan_max VARCHAR(255) NOT NULL, height_min VARCHAR(255) DEFAULT NULL, height_max VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, status TINYINT DEFAULT NULL, family_name VARCHAR(255) DEFAULT NULL, genus VARCHAR(255) DEFAULT NULL, species VARCHAR(255) DEFAULT NULL, tree_images_id INT DEFAULT NULL, INDEX IDX_B73E5EDC13FB61BA (tree_images_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_attributes (id INT AUTO_INCREMENT NOT NULL, attribute_name VARCHAR(255) DEFAULT NULL, attribute_value LONGTEXT DEFAULT NULL, tree_id INT DEFAULT NULL, INDEX IDX_CA88667178B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_category_map (id INT AUTO_INCREMENT NOT NULL, tree_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_1C12B79D78B64A2 (tree_id), INDEX IDX_1C12B79D12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_climates (id INT AUTO_INCREMENT NOT NULL, temperature_min NUMERIC(5, 2) DEFAULT NULL, temperature_max NUMERIC(5, 2) DEFAULT NULL, rainfall_min INT DEFAULT NULL, rainfall_max INT DEFAULT NULL, humidity_min INT DEFAULT NULL, humidity_max INT DEFAULT NULL, soil_type VARCHAR(255) DEFAULT NULL, soil_ph_min NUMERIC(3, 1) DEFAULT NULL, soil_ph_max NUMERIC(3, 1) DEFAULT NULL, tree_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5B21B73B78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_disease_map (id INT AUTO_INCREMENT NOT NULL, tree_id INT DEFAULT NULL, disease_id INT DEFAULT NULL, INDEX IDX_8E86A51378B64A2 (tree_id), INDEX IDX_8E86A513D8355341 (disease_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_diseases (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, symptoms LONGTEXT DEFAULT NULL, treatment LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_images (id INT AUTO_INCREMENT NOT NULL, image_type VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_local_names (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, local_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, tree_id_id INT NOT NULL, INDEX IDX_9A29F62AC746B832 (tree_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_regions (id INT AUTO_INCREMENT NOT NULL, abundance VARCHAR(255) NOT NULL, tree_id INT DEFAULT NULL, region_id INT DEFAULT NULL, INDEX IDX_68E0DBEE78B64A2 (tree_id), INDEX IDX_68E0DBEE98260155 (region_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_uses (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_uses_tree (tree_uses_id INT NOT NULL, tree_id INT NOT NULL, INDEX IDX_C157A5EFD55516ED (tree_uses_id), INDEX IDX_C157A5EF78B64A2 (tree_id), PRIMARY KEY (tree_uses_id, tree_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE84DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D64B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
        $this->addSql('ALTER TABLE tree_attributes ADD CONSTRAINT FK_CA88667178B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D12469DE2 FOREIGN KEY (category_id) REFERENCES tree_categories (id)');
        $this->addSql('ALTER TABLE tree_climates ADD CONSTRAINT FK_5B21B73B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A51378B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A513D8355341 FOREIGN KEY (disease_id) REFERENCES tree_diseases (id)');
        $this->addSql('ALTER TABLE tree_local_names ADD CONSTRAINT FK_9A29F62AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE98260155 FOREIGN KEY (region_id) REFERENCES tree_regions (id)');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EFD55516ED FOREIGN KEY (tree_uses_id) REFERENCES tree_uses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EF78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A78B64A2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE84DDC6B4');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE78B64A2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D8BE3022C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D64B64DCC');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
        $this->addSql('ALTER TABLE tree_attributes DROP FOREIGN KEY FK_CA88667178B64A2');
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D78B64A2');
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D12469DE2');
        $this->addSql('ALTER TABLE tree_climates DROP FOREIGN KEY FK_5B21B73B78B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A51378B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A513D8355341');
        $this->addSql('ALTER TABLE tree_local_names DROP FOREIGN KEY FK_9A29F62AC746B832');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE78B64A2');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE98260155');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EFD55516ED');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EF78B64A2');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE favorites');
        $this->addSql('DROP TABLE favorites_tree');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE tree');
        $this->addSql('DROP TABLE tree_attributes');
        $this->addSql('DROP TABLE tree_categories');
        $this->addSql('DROP TABLE tree_category_map');
        $this->addSql('DROP TABLE tree_climates');
        $this->addSql('DROP TABLE tree_disease_map');
        $this->addSql('DROP TABLE tree_diseases');
        $this->addSql('DROP TABLE tree_images');
        $this->addSql('DROP TABLE tree_local_names');
        $this->addSql('DROP TABLE tree_regions');
        $this->addSql('DROP TABLE tree_uses');
        $this->addSql('DROP TABLE tree_uses_tree');
        $this->addSql('DROP TABLE user');
    }
}
