<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260714110456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D54478B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_images ADD CONSTRAINT FK_DD7F299B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_uses ADD CONSTRAINT FK_45178CE878B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D54478B64A2');
        $this->addSql('ALTER TABLE tree_images DROP FOREIGN KEY FK_DD7F299B78B64A2');
        $this->addSql('ALTER TABLE tree_uses DROP FOREIGN KEY FK_45178CE878B64A2');
    }
}
