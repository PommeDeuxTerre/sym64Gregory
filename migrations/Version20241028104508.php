<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028104508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD article_date_posted DATETIME DEFAULT NULL, DROP article_description, DROP article_date_created, DROP article_date_published, DROP article_published, DROP article_date_articleed');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD article_description LONGTEXT NOT NULL, ADD article_date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD article_published TINYINT(1) NOT NULL, ADD article_date_articleed DATETIME DEFAULT NULL, CHANGE article_date_posted article_date_published DATETIME DEFAULT NULL');
    }
}
