<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930095513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BD53DA3AB');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD id INT AUTO_INCREMENT NOT NULL, ADD creado DATETIME NOT NULL, CHANGE marcador_id marcador_id INT DEFAULT NULL, CHANGE etiqueta_id etiqueta_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador_etiqueta MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BD53DA3AB');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP id, DROP creado, CHANGE marcador_id marcador_id INT NOT NULL, CHANGE etiqueta_id etiqueta_id INT NOT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD PRIMARY KEY (marcador_id, etiqueta_id)');
    }
}
