<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260716124944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE uses');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D5448BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uses (title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, status TINYINT DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, tree_id INT NOT NULL, INDEX IDX_FA94E6DF78B64A2 (tree_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A8BE3022C');
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D5448BE3022C');
    }
}
