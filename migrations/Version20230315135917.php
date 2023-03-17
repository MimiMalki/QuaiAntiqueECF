<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315135917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resevation_allergie (resevation_id INT NOT NULL, allergie_id INT NOT NULL, INDEX IDX_81A4BB4AC49CA3CD (resevation_id), INDEX IDX_81A4BB4A7C86304A (allergie_id), PRIMARY KEY(resevation_id, allergie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resevation_allergie ADD CONSTRAINT FK_81A4BB4AC49CA3CD FOREIGN KEY (resevation_id) REFERENCES resevation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resevation_allergie ADD CONSTRAINT FK_81A4BB4A7C86304A FOREIGN KEY (allergie_id) REFERENCES allergie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resevation_allergie DROP FOREIGN KEY FK_81A4BB4AC49CA3CD');
        $this->addSql('ALTER TABLE resevation_allergie DROP FOREIGN KEY FK_81A4BB4A7C86304A');
        $this->addSql('DROP TABLE resevation_allergie');
    }
}
