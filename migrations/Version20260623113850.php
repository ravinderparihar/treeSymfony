<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623113850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tree_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_local_names (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, local_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, tree_id_id INT NOT NULL, INDEX IDX_9A29F62AC746B832 (tree_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_uses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, tree_id_id INT DEFAULT NULL, INDEX IDX_45178CE8C746B832 (tree_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE tree_local_names ADD CONSTRAINT FK_9A29F62AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_uses ADD CONSTRAINT FK_45178CE8C746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments CHANGE comment comment LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tree CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree_local_names DROP FOREIGN KEY FK_9A29F62AC746B832');
        $this->addSql('ALTER TABLE tree_uses DROP FOREIGN KEY FK_45178CE8C746B832');
        $this->addSql('DROP TABLE tree_categories');
        $this->addSql('DROP TABLE tree_local_names');
        $this->addSql('DROP TABLE tree_uses');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC746B832');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('ALTER TABLE comments CHANGE comment comment VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DC746B832');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9D86650F');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
        $this->addSql('ALTER TABLE tree CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
