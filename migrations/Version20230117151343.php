<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117151343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, register VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F26E94372 (siret), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, price INT NOT NULL, format VARCHAR(255) NOT NULL, modalities VARCHAR(255) NOT NULL, INDEX IDX_E98F285925F432AD (streamer_id), INDEX IDX_E98F2859979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_status (id INT AUTO_INCREMENT NOT NULL, contract_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_474080512576E0FD (contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, relation_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6BD307F25F432AD (streamer_id), INDEX IDX_B6BD307F979B1AD6 (company_id), INDEX IDX_B6BD307F3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, has_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3952D0CBA2BDE448 (has_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposal (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, has_proposal_status_id INT NOT NULL, format VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_BFE5947225F432AD (streamer_id), INDEX IDX_BFE59472979B1AD6 (company_id), INDEX IDX_BFE59472EB4EA67B (has_proposal_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposal_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_6289474925F432AD (streamer_id), INDEX IDX_62894749979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representative (id INT AUTO_INCREMENT NOT NULL, has_representative_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_2507390EC1A5C1BB (has_representative_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, followers BIGINT NOT NULL, mail VARCHAR(255) DEFAULT NULL, siret BIGINT DEFAULT NULL, id_streamer VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2DF6AE32F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_content (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F285925F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE contract_status ADD CONSTRAINT FK_474080512576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F25F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3256915B FOREIGN KEY (relation_id) REFERENCES relation (id)');
        $this->addSql('ALTER TABLE platform ADD CONSTRAINT FK_3952D0CBA2BDE448 FOREIGN KEY (has_type_id) REFERENCES platform_type (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE5947225F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472EB4EA67B FOREIGN KEY (has_proposal_status_id) REFERENCES proposal_status (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_6289474925F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE representative ADD CONSTRAINT FK_2507390EC1A5C1BB FOREIGN KEY (has_representative_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F285925F432AD');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859979B1AD6');
        $this->addSql('ALTER TABLE contract_status DROP FOREIGN KEY FK_474080512576E0FD');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F25F432AD');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F979B1AD6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3256915B');
        $this->addSql('ALTER TABLE platform DROP FOREIGN KEY FK_3952D0CBA2BDE448');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE5947225F432AD');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472979B1AD6');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472EB4EA67B');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_6289474925F432AD');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749979B1AD6');
        $this->addSql('ALTER TABLE representative DROP FOREIGN KEY FK_2507390EC1A5C1BB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE contract_status');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE platform_type');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('DROP TABLE proposal_status');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE representative');
        $this->addSql('DROP TABLE streamer');
        $this->addSql('DROP TABLE type_of_content');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
