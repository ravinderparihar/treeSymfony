<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260701114835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tree_category_map (id INT AUTO_INCREMENT NOT NULL, tree_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_1C12B79D78B64A2 (tree_id), INDEX IDX_1C12B79D12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_category_map ADD CONSTRAINT FK_1C12B79D12469DE2 FOREIGN KEY (category_id) REFERENCES tree_categories (id)');
        $this->addSql('DROP INDEX IDX_5F9E962AC746B832 ON comments');
        $this->addSql('ALTER TABLE comments ADD user_id INT NOT NULL, CHANGE comment comment LONGTEXT NOT NULL, CHANGE tree_id_id tree_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A78B64A2 ON comments (tree_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE84DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_49CA4E7DC746B832 ON likes');
        $this->addSql('ALTER TABLE likes ADD userId INT NOT NULL, CHANGE tree_id_id treeId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D64B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D8BE3022C ON likes (treeId)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D64B64DCC ON likes (userId)');
        $this->addSql('DROP INDEX IDX_B73E5EDCA21214B7 ON tree');
        $this->addSql('ALTER TABLE tree DROP categories_id');
        $this->addSql('ALTER TABLE tree ADD CONSTRAINT FK_B73E5EDC13FB61BA FOREIGN KEY (tree_images_id) REFERENCES tree_images (id)');
        $this->addSql('ALTER TABLE tree_attributes ADD CONSTRAINT FK_CA88667178B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_climates ADD CONSTRAINT FK_5B21B73B78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A51378B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_disease_map ADD CONSTRAINT FK_8E86A513D8355341 FOREIGN KEY (disease_id) REFERENCES tree_diseases (id)');
        $this->addSql('ALTER TABLE tree_local_names ADD CONSTRAINT FK_9A29F62AC746B832 FOREIGN KEY (tree_id_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE tree_regions ADD CONSTRAINT FK_68E0DBEE98260155 FOREIGN KEY (region_id) REFERENCES tree_regions (id)');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EFD55516ED FOREIGN KEY (tree_uses_id) REFERENCES tree_uses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_uses_tree ADD CONSTRAINT FK_C157A5EF78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D78B64A2');
        $this->addSql('ALTER TABLE tree_category_map DROP FOREIGN KEY FK_1C12B79D12469DE2');
        $this->addSql('DROP TABLE tree_category_map');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A78B64A2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('DROP INDEX IDX_5F9E962A78B64A2 ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395 ON comments');
        $this->addSql('ALTER TABLE comments DROP user_id, CHANGE comment comment VARCHAR(255) NOT NULL, CHANGE tree_id tree_id_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_5F9E962AC746B832 ON comments (tree_id_id)');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE84DDC6B4');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE78B64A2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D8BE3022C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D64B64DCC');
        $this->addSql('DROP INDEX IDX_49CA4E7D8BE3022C ON likes');
        $this->addSql('DROP INDEX IDX_49CA4E7D64B64DCC ON likes');
        $this->addSql('ALTER TABLE likes DROP userId, CHANGE treeId tree_id_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_49CA4E7DC746B832 ON likes (tree_id_id)');
        $this->addSql('ALTER TABLE tree DROP FOREIGN KEY FK_B73E5EDC13FB61BA');
        $this->addSql('ALTER TABLE tree ADD categories_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_B73E5EDCA21214B7 ON tree (categories_id)');
        $this->addSql('ALTER TABLE tree_attributes DROP FOREIGN KEY FK_CA88667178B64A2');
        $this->addSql('ALTER TABLE tree_climates DROP FOREIGN KEY FK_5B21B73B78B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A51378B64A2');
        $this->addSql('ALTER TABLE tree_disease_map DROP FOREIGN KEY FK_8E86A513D8355341');
        $this->addSql('ALTER TABLE tree_local_names DROP FOREIGN KEY FK_9A29F62AC746B832');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE78B64A2');
        $this->addSql('ALTER TABLE tree_regions DROP FOREIGN KEY FK_68E0DBEE98260155');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EFD55516ED');
        $this->addSql('ALTER TABLE tree_uses_tree DROP FOREIGN KEY FK_C157A5EF78B64A2');
    }
}
