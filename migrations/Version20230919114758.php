<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919114758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP INDEX IDX_6AB5B471A76ED395, ADD UNIQUE INDEX UNIQ_6AB5B471A76ED395 (user_id)');
        $this->addSql('ALTER TABLE recruteur DROP INDEX IDX_2BD3678CA76ED395, ADD UNIQUE INDEX UNIQ_2BD3678CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP INDEX UNIQ_6AB5B471A76ED395, ADD INDEX IDX_6AB5B471A76ED395 (user_id)');
        $this->addSql('ALTER TABLE recruteur DROP INDEX UNIQ_2BD3678CA76ED395, ADD INDEX IDX_2BD3678CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
