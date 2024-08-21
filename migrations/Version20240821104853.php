<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240821104853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A6C8A81A9');
        $this->addSql('DROP INDEX IDX_B3BA5A5A6C8A81A9 ON products');
        $this->addSql('ALTER TABLE products CHANGE products_id producer_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A89B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A89B658FE ON products (producer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A89B658FE');
        $this->addSql('DROP INDEX IDX_B3BA5A5A89B658FE ON products');
        $this->addSql('ALTER TABLE products CHANGE producer_id products_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A6C8A81A9 FOREIGN KEY (products_id) REFERENCES producer (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A6C8A81A9 ON products (products_id)');
    }
}
