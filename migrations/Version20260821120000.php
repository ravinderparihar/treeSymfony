<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260821120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique username to users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD username VARCHAR(191) DEFAULT NULL, ADD UNIQUE INDEX UNIQ_8D93D649F85E0677 (username)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D649F85E0677, DROP username');
    }
}