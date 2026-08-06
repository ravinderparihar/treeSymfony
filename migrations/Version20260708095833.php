<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260708095833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tree_id INT NOT NULL, user_id INT NOT NULL, tree_comments_id INT DEFAULT NULL, INDEX IDX_5F9E962A78B64A2 (tree_id), INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A14CBB6E8 (tree_comments_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorites (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, tree_favorite_id INT DEFAULT NULL, INDEX IDX_E46960F5DDEB6B73 (tree_favorite_id), INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorites_tree (favorites_id INT NOT NULL, tree_id INT NOT NULL, INDEX IDX_2CFDC0AE84DDC6B4 (favorites_id), INDEX IDX_2CFDC0AE78B64A2 (tree_id), PRIMARY KEY (favorites_id, tree_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, treeId INT DEFAULT NULL, userId INT NOT NULL, tree_likes_id INT DEFAULT NULL, INDEX IDX_49CA4E7D4CEF9C24 (tree_likes_id), INDEX IDX_49CA4E7D8BE3022C (treeId), INDEX IDX_49CA4E7D64B64DCC (userId), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE regions (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, state VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, district VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, latitude NUMERIC(10, 7) DEFAULT NULL, longitude NUMERIC(10, 7) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree (id INT AUTO_INCREMENT NOT NULL, scientific_name LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, lifespan_min VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, lifespan_max VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, height_min VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, height_max VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status TINYINT DEFAULT NULL, family_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, genus VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, species VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, growth_rate VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_attributes (id INT AUTO_INCREMENT NOT NULL, attribute_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, attribute_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, tree_id INT DEFAULT NULL, INDEX IDX_CA88667178B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_category_map (id INT AUTO_INCREMENT NOT NULL, tree_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_1C12B79D78B64A2 (tree_id), INDEX IDX_1C12B79D12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_climates (id INT AUTO_INCREMENT NOT NULL, temperature_min NUMERIC(5, 2) DEFAULT NULL, temperature_max NUMERIC(5, 2) DEFAULT NULL, rainfall_min INT DEFAULT NULL, rainfall_max INT DEFAULT NULL, humidity_min INT DEFAULT NULL, humidity_max INT DEFAULT NULL, soil_type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, soil_ph_min NUMERIC(3, 1) DEFAULT NULL, soil_ph_max NUMERIC(3, 1) DEFAULT NULL, tree_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5B21B73B78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_disease_map (id INT AUTO_INCREMENT NOT NULL, tree_id INT DEFAULT NULL, disease_id INT DEFAULT NULL, INDEX IDX_8E86A51378B64A2 (tree_id), INDEX IDX_8E86A513D8355341 (disease_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_diseases (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, symptoms LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, treatment LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_images (id INT AUTO_INCREMENT NOT NULL, image_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, image_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tree_id INT NOT NULL, INDEX IDX_DD7F299B78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_local_names (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, local_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tree_id INT NOT NULL, INDEX IDX_9A29F62A78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_regions (id INT AUTO_INCREMENT NOT NULL, abundance VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tree_id INT DEFAULT NULL, region_id INT DEFAULT NULL, INDEX IDX_68E0DBEE78B64A2 (tree_id), INDEX IDX_68E0DBEE98260155 (region_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_uses (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status TINYINT NOT NULL, tree_id INT NOT NULL, INDEX IDX_45178CE878B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, roles VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, profile_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
    }
}
