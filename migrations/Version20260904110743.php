<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Every table except tree/images/local_names/uses was still MyISAM, which
 * silently ignores FOREIGN KEY clauses and caps index keys at 1000 bytes.
 * That's why earlier migrations that tried to add FKs never actually
 * created them, and why a unique index on user.email (utf8mb4 varchar(255)
 * = up to 1020 bytes) fails outright. This migration converts the
 * remaining tables to InnoDB, removes tree_categories rows left dangling
 * by trees that were deleted without cascading (no FK ever enforced it),
 * then adds the FK constraints the mapping already expects plus the
 * unique index on user.email.
 */
final class Version20260904110743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert remaining tables to InnoDB, clean up orphaned tree_categories rows, add missing FK constraints and unique index on user.email';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category ENGINE=InnoDB');
        $this->addSql('ALTER TABLE user ENGINE=InnoDB');
        $this->addSql('ALTER TABLE comments ENGINE=InnoDB');
        $this->addSql('ALTER TABLE favorites ENGINE=InnoDB');
        $this->addSql('ALTER TABLE favorites_tree ENGINE=InnoDB');
        $this->addSql('ALTER TABLE likes ENGINE=InnoDB');
        $this->addSql('ALTER TABLE password_reset_token ENGINE=InnoDB');
        $this->addSql('ALTER TABLE tree_categories ENGINE=InnoDB');

        // Dangling rows left by trees deleted before FKs existed (tree ids 1,3,4,5 no longer exist).
        $this->addSql('DELETE tc FROM tree_categories tc LEFT JOIN tree t ON t.id = tc.tree_id WHERE t.id IS NULL');

        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE84DDC6B4 FOREIGN KEY (favorites_id) REFERENCES favorites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_tree ADD CONSTRAINT FK_2CFDC0AE78B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D8BE3022C FOREIGN KEY (treeId) REFERENCES tree (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D64B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6978B64A2 FOREIGN KEY (tree_id) REFERENCES tree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tree_categories ADD CONSTRAINT FK_C1E0BE6912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A78B64A2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE84DDC6B4');
        $this->addSql('ALTER TABLE favorites_tree DROP FOREIGN KEY FK_2CFDC0AE78B64A2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D8BE3022C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D64B64DCC');
        $this->addSql('ALTER TABLE password_reset_token DROP FOREIGN KEY FK_6B7BA4B6A76ED395');
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6978B64A2');
        $this->addSql('ALTER TABLE tree_categories DROP FOREIGN KEY FK_C1E0BE6912469DE2');

        // Note: rows removed from tree_categories in up() are not restorable here.
        $this->addSql('ALTER TABLE category ENGINE=MyISAM');
        $this->addSql('ALTER TABLE user ENGINE=MyISAM');
        $this->addSql('ALTER TABLE comments ENGINE=MyISAM');
        $this->addSql('ALTER TABLE favorites ENGINE=MyISAM');
        $this->addSql('ALTER TABLE favorites_tree ENGINE=MyISAM');
        $this->addSql('ALTER TABLE likes ENGINE=MyISAM');
        $this->addSql('ALTER TABLE password_reset_token ENGINE=MyISAM');
        $this->addSql('ALTER TABLE tree_categories ENGINE=MyISAM');
    }
}
