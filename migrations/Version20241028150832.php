<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028150832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE article_date_create article_date_create DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE section CHANGE section_title section_title VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE section CHANGE section_title section_title VARCHAR(160) NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE article_date_create article_date_create DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
