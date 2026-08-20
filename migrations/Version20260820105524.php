<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260820105524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree ADD seed_treatment VARCHAR(255) DEFAULT NULL, ADD nursery_method VARCHAR(255) DEFAULT NULL, ADD planting_distance VARCHAR(255) DEFAULT NULL, ADD fertilizer_schedule VARCHAR(255) DEFAULT NULL, ADD irrigation_schedule VARCHAR(255) DEFAULT NULL, ADD pruning_guide VARCHAR(255) DEFAULT NULL, ADD common_diseases LONGTEXT DEFAULT NULL, ADD common_insects LONGTEXT DEFAULT NULL, ADD symptoms LONGTEXT DEFAULT NULL, ADD treatment LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tree DROP seed_treatment, DROP nursery_method, DROP planting_distance, DROP fertilizer_schedule, DROP irrigation_schedule, DROP pruning_guide, DROP common_diseases, DROP common_insects, DROP symptoms, DROP treatment');
    }
}
