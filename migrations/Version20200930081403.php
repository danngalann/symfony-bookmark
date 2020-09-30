<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930081403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marcador_etiqueta (marcador_id INT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_DCF4C7BB323D722 (marcador_id), INDEX IDX_DCF4C7BD53DA3AB (etiqueta_id), PRIMARY KEY(marcador_id, etiqueta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE marcador_etiqueta');
    }
}
