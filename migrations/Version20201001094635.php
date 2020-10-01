<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201001094635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4E10122DA76ED395 ON categoria (user_id)');
        $this->addSql('ALTER TABLE etiqueta ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE etiqueta ADD CONSTRAINT FK_6D5CA63AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6D5CA63AA76ED395 ON etiqueta (user_id)');
        $this->addSql('ALTER TABLE marcador ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE marcador ADD CONSTRAINT FK_B5F18E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B5F18E7A76ED395 ON marcador (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DA76ED395');
        $this->addSql('DROP INDEX IDX_4E10122DA76ED395 ON categoria');
        $this->addSql('ALTER TABLE categoria DROP user_id');
        $this->addSql('ALTER TABLE etiqueta DROP FOREIGN KEY FK_6D5CA63AA76ED395');
        $this->addSql('DROP INDEX IDX_6D5CA63AA76ED395 ON etiqueta');
        $this->addSql('ALTER TABLE etiqueta DROP user_id');
        $this->addSql('ALTER TABLE marcador DROP FOREIGN KEY FK_B5F18E7A76ED395');
        $this->addSql('DROP INDEX IDX_B5F18E7A76ED395 ON marcador');
        $this->addSql('ALTER TABLE marcador DROP user_id');
    }
}
