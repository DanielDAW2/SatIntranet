<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190521121838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ordentrabajo ADD cierre_reparaciones_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordentrabajo ADD CONSTRAINT FK_EC7E6B386CEFCEEB FOREIGN KEY (cierre_reparaciones_id) REFERENCES cierre_reparaciones (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC7E6B386CEFCEEB ON ordentrabajo (cierre_reparaciones_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ordentrabajo DROP FOREIGN KEY FK_EC7E6B386CEFCEEB');
        $this->addSql('DROP INDEX UNIQ_EC7E6B386CEFCEEB ON ordentrabajo');
        $this->addSql('ALTER TABLE ordentrabajo DROP cierre_reparaciones_id');
    }
}
