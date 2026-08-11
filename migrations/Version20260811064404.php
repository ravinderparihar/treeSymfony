<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260811064404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (name VARCHAR(100) DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tree_categories (tree_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C1E0BE6978B64A2 (tree_id), INDEX IDX_C1E0BE6912469DE2 (category_id), PRIMARY KEY (tree_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6978B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6978B64A2');
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6912469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE tree_categories');
    }
}
