<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260727065544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uses (title VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT NOT NULL, id INT AUTO_INCREMENT NOT NULL, treeId INT DEFAULT NULL, INDEX IDX_FA94E6DF8BE3022C (treeId), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE uses ADD CONSTRAINT FK_FA94E6DF8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D5448BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uses DROP FOREIGN KEY FK_FA94E6DF8BE3022C');
        $this->addSql('DROP TABLE uses');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A8BE3022C');
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D5448BE3022C');
    }
}
