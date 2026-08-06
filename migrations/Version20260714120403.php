<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260714120403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (image_type VARCHAR(255) DEFAULT NULL, image_url VARCHAR(500) DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_E01FBE6A78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE uses (title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_FA94E6DF78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uses ADD CONSTRAINT FK_FA94E6DF78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tree_images');
        $this->addSql('DROP TABLE tree_uses');
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D54478B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tree_images (image_type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, image_url VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_DD7F299B78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tree_uses (title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status TINYINT DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_45178CE878B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A78B64A2');
        $this->addSql('ALTER TABLE uses DROP FOREIGN KEY FK_FA94E6DF78B64A2');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE uses');
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D54478B64A2');
    }
}
