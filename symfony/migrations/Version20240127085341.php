<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240127085341 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS domain_messages (
                id CHAR(36) NOT NULL,
                type VARCHAR(255) NOT NULL,
                playhead INT UNSIGNED NOT NULL,
                metadata JSON NOT NULL,
                payload JSON NOT NULL,
                recorded_at BIGINT NOT NULL,
                PRIMARY KEY(id, type, playhead)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE domain_messages');
    }
}
