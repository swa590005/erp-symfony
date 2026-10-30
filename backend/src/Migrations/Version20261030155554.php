<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261030155554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supplier CHANGE supplier_number supplier_number VARCHAR(20) NOT NULL, CHANGE phone_number phone_number VARCHAR(30) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B2A6C7E4DF2CD80 ON supplier (supplier_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9B2A6C7E4DF2CD80 ON supplier');
        $this->addSql('ALTER TABLE supplier CHANGE supplier_number supplier_number VARCHAR(255) NOT NULL, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL');
    }
}
