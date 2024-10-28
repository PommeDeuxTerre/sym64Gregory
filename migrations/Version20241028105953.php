<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028105953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE section ADD section_slug VARCHAR(105) NOT NULL, ADD section_detail VARCHAR(500) DEFAULT NULL, DROP section_description');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEF1D237769 ON section (section_slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2D737AEF1D237769 ON section');
        $this->addSql('ALTER TABLE section ADD section_description VARCHAR(600) DEFAULT NULL, DROP section_slug, DROP section_detail');
    }
}
