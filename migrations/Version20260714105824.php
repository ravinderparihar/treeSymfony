<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260714105824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE local_names (language VARCHAR(100) DEFAULT NULL, local_name VARCHAR(255) DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_EDD5D54478B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D54478B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tree_local_names');
        $this->addSql('ALTER TABLE tree_images ADD CONSTRAINT FK_DD7F299B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_uses ADD CONSTRAINT FK_45178CE878B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tree_local_names (language VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, local_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_9A29F62A78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D54478B64A2');
        $this->addSql('DROP TABLE local_names');
        $this->addSql('ALTER TABLE tree_images DROP FOREIGN KEY FK_DD7F299B78B64A2');
        $this->addSql('ALTER TABLE tree_uses DROP FOREIGN KEY FK_45178CE878B64A2');
    }
}
