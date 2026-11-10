<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261106151715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, invoice_number VARCHAR(20) NOT NULL, invoice_date DATE NOT NULL, net_total NUMERIC(10, 2) NOT NULL, discount_amount NUMERIC(10, 2) NOT NULL, vat_rate NUMERIC(5, 2) NOT NULL, vat_amount NUMERIC(10, 2) NOT NULL, gross_total NUMERIC(10, 2) NOT NULL, purchase_order_id INT NOT NULL, UNIQUE INDEX UNIQ_906517442DA68207 (invoice_number), UNIQUE INDEX UNIQ_90651744A45D7E6A (purchase_order_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744A45D7E6A');
        $this->addSql('DROP TABLE invoice');
    }
}
