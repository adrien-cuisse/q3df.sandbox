<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519132157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_98197A65E7927C74 ON player');
        $this->addSql('DROP INDEX UNIQ_98197A65A188FE64 ON player');
        $this->addSql('ALTER TABLE player ADD username VARCHAR(255) NOT NULL, DROP nickname');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player ADD nickname VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP username');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65E7927C74 ON player (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65A188FE64 ON player (nickname)');
    }
}
