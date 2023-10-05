<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005194859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD seller_id INT DEFAULT NULL, ADD day VARCHAR(100) NOT NULL, ADD time VARCHAR(100) NOT NULL, ADD subject LONGTEXT DEFAULT NULL, ADD comment LONGTEXT DEFAULT NULL, ADD week INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE8DE820D9 ON booking (seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE8DE820D9');
        $this->addSql('DROP INDEX IDX_E00CEDDE8DE820D9 ON booking');
        $this->addSql('ALTER TABLE booking DROP seller_id, DROP day, DROP time, DROP subject, DROP comment, DROP week');
    }
}
