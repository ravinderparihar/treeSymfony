<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260826053000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Prevent duplicate likes from the same user on a tree';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE duplicate FROM likes duplicate INNER JOIN likes original ON original.treeId = duplicate.treeId AND original.userId = duplicate.userId AND original.id < duplicate.id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_LIKES_TREE_USER ON likes (treeId, userId)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_LIKES_TREE_USER ON likes');
    }
}
