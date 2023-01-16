<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116140459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, streamer_id INT DEFAULT NULL, company_id INT DEFAULT NULL, relation_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6BD307F25F432AD (streamer_id), INDEX IDX_B6BD307F979B1AD6 (company_id), INDEX IDX_B6BD307F3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, streamer_id INT DEFAULT NULL, company_id INT DEFAULT NULL, INDEX IDX_6289474925F432AD (streamer_id), INDEX IDX_62894749979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F25F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3256915B FOREIGN KEY (relation_id) REFERENCES relation (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_6289474925F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE head_office head_office VARCHAR(255) DEFAULT NULL, CHANGE register register VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F25F432AD');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F979B1AD6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3256915B');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_6289474925F432AD');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749979B1AD6');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE relation');
        $this->addSql('ALTER TABLE company CHANGE mail mail VARCHAR(255) NOT NULL, CHANGE head_office head_office VARCHAR(255) NOT NULL, CHANGE register register VARCHAR(255) NOT NULL');
    }
}
