<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623112620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A9D86650F ON comments (user_id_id)');
        $this->addSql('ALTER TABLE likes ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D9D86650F ON likes (user_id_id)');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC746B832');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('DROP INDEX IDX_5F9E962A9D86650F ON comments');
        $this->addSql('ALTER TABLE comments DROP user_id_id');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DC746B832');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9D86650F');
        $this->addSql('DROP INDEX IDX_49CA4E7D9D86650F ON likes');
        $this->addSql('ALTER TABLE likes DROP user_id_id');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
    }
}
