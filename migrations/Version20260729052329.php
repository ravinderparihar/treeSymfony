<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260729052329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE local_names ADD CONSTRAINT FK_EDD5D5448BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE uses ADD CONSTRAINT FK_FA94E6DF8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A8BE3022C');
        $this->addSql('ALTER TABLE local_names DROP FOREIGN KEY FK_EDD5D5448BE3022C');
        $this->addSql('ALTER TABLE uses DROP FOREIGN KEY FK_FA94E6DF8BE3022C');
    }
}
