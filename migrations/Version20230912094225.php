<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912094225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, recruteur_id_id INT NOT NULL, job_title VARCHAR(255) NOT NULL, work_place VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_F65593E5B7E5B243 (recruteur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, candidat_id_id INT NOT NULL, annonce_id_id INT NOT NULL, INDEX IDX_E33BD3B8BFA9F225 (candidat_id_id), INDEX IDX_E33BD3B868C955C8 (annonce_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5B7E5B243 FOREIGN KEY (recruteur_id_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8BFA9F225 FOREIGN KEY (candidat_id_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B868C955C8 FOREIGN KEY (annonce_id_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE candidat CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE prenom prenom VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE recruteur CHANGE company company VARCHAR(255) DEFAULT NULL, CHANGE company_adress company_adress VARCHAR(255) DEFAULT NULL, CHANGE company_postcode company_postcode VARCHAR(255) DEFAULT NULL, CHANGE company_city company_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5B7E5B243');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8BFA9F225');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B868C955C8');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('ALTER TABLE candidat CHANGE nom nom VARCHAR(50) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE recruteur CHANGE company company VARCHAR(255) DEFAULT \'NULL\', CHANGE company_adress company_adress VARCHAR(255) DEFAULT \'NULL\', CHANGE company_postcode company_postcode VARCHAR(255) DEFAULT \'NULL\', CHANGE company_city company_city VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
