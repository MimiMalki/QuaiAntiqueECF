<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308204854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergie_user (allergie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C8B12B7A7C86304A (allergie_id), INDEX IDX_C8B12B7AA76ED395 (user_id), PRIMARY KEY(allergie_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergie_user ADD CONSTRAINT FK_C8B12B7A7C86304A FOREIGN KEY (allergie_id) REFERENCES allergie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergie_user ADD CONSTRAINT FK_C8B12B7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergie_user DROP FOREIGN KEY FK_C8B12B7A7C86304A');
        $this->addSql('ALTER TABLE allergie_user DROP FOREIGN KEY FK_C8B12B7AA76ED395');
        $this->addSql('DROP TABLE allergie_user');
    }
}
