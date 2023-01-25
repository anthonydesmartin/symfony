<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125000109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, register VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F26E94372 (siret), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, price INT NOT NULL, format VARCHAR(255) NOT NULL, modalities VARCHAR(255) NOT NULL, INDEX IDX_E98F285925F432AD (streamer_id), INDEX IDX_E98F2859979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_status (id INT AUTO_INCREMENT NOT NULL, contract_id INT NOT NULL, name VARCHAR(255) NOT NULL, signature_company VARCHAR(255) DEFAULT NULL, signature_streamer VARCHAR(255) DEFAULT NULL, INDEX IDX_474080512576E0FD (contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, has_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3952D0CBA2BDE448 (has_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposal (id INT AUTO_INCREMENT NOT NULL, streamer_id INT NOT NULL, company_id INT NOT NULL, has_proposal_status_id INT NOT NULL, format VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_BFE5947225F432AD (streamer_id), INDEX IDX_BFE59472979B1AD6 (company_id), INDEX IDX_BFE59472EB4EA67B (has_proposal_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposal_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representative (id INT AUTO_INCREMENT NOT NULL, has_representative_id INT NOT NULL, has_representative_streamer_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_2507390EC1A5C1BB (has_representative_id), INDEX IDX_2507390EE8F5B369 (has_representative_streamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, followers BIGINT NOT NULL, mail VARCHAR(255) DEFAULT NULL, siret BIGINT DEFAULT NULL, id_streamer VARCHAR(255) NOT NULL, is_mature TINYINT(1) NOT NULL, profile_picture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2DF6AE32F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_category (streamer_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_2B10584025F432AD (streamer_id), INDEX IDX_2B10584012469DE2 (category_id), PRIMARY KEY(streamer_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_company (streamer_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_111105B025F432AD (streamer_id), INDEX IDX_111105B0979B1AD6 (company_id), PRIMARY KEY(streamer_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_platform (streamer_id INT NOT NULL, platform_id INT NOT NULL, INDEX IDX_140E914A25F432AD (streamer_id), INDEX IDX_140E914AFFE6496F (platform_id), PRIMARY KEY(streamer_id, platform_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_type_of_content (streamer_id INT NOT NULL, type_of_content_id INT NOT NULL, INDEX IDX_5B6D7A9B25F432AD (streamer_id), INDEX IDX_5B6D7A9B1D19D54D (type_of_content_id), PRIMARY KEY(streamer_id, type_of_content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_content (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F285925F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE contract_status ADD CONSTRAINT FK_474080512576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE platform ADD CONSTRAINT FK_3952D0CBA2BDE448 FOREIGN KEY (has_type_id) REFERENCES platform_type (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE5947225F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472EB4EA67B FOREIGN KEY (has_proposal_status_id) REFERENCES proposal_status (id)');
        $this->addSql('ALTER TABLE representative ADD CONSTRAINT FK_2507390EC1A5C1BB FOREIGN KEY (has_representative_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE representative ADD CONSTRAINT FK_2507390EE8F5B369 FOREIGN KEY (has_representative_streamer_id) REFERENCES streamer (id)');
        $this->addSql('ALTER TABLE streamer_category ADD CONSTRAINT FK_2B10584025F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_category ADD CONSTRAINT FK_2B10584012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_company ADD CONSTRAINT FK_111105B025F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_company ADD CONSTRAINT FK_111105B0979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_platform ADD CONSTRAINT FK_140E914A25F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_platform ADD CONSTRAINT FK_140E914AFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_type_of_content ADD CONSTRAINT FK_5B6D7A9B25F432AD FOREIGN KEY (streamer_id) REFERENCES streamer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE streamer_type_of_content ADD CONSTRAINT FK_5B6D7A9B1D19D54D FOREIGN KEY (type_of_content_id) REFERENCES type_of_content (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F285925F432AD');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859979B1AD6');
        $this->addSql('ALTER TABLE contract_status DROP FOREIGN KEY FK_474080512576E0FD');
        $this->addSql('ALTER TABLE platform DROP FOREIGN KEY FK_3952D0CBA2BDE448');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE5947225F432AD');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472979B1AD6');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472EB4EA67B');
        $this->addSql('ALTER TABLE representative DROP FOREIGN KEY FK_2507390EC1A5C1BB');
        $this->addSql('ALTER TABLE representative DROP FOREIGN KEY FK_2507390EE8F5B369');
        $this->addSql('ALTER TABLE streamer_category DROP FOREIGN KEY FK_2B10584025F432AD');
        $this->addSql('ALTER TABLE streamer_category DROP FOREIGN KEY FK_2B10584012469DE2');
        $this->addSql('ALTER TABLE streamer_company DROP FOREIGN KEY FK_111105B025F432AD');
        $this->addSql('ALTER TABLE streamer_company DROP FOREIGN KEY FK_111105B0979B1AD6');
        $this->addSql('ALTER TABLE streamer_platform DROP FOREIGN KEY FK_140E914A25F432AD');
        $this->addSql('ALTER TABLE streamer_platform DROP FOREIGN KEY FK_140E914AFFE6496F');
        $this->addSql('ALTER TABLE streamer_type_of_content DROP FOREIGN KEY FK_5B6D7A9B25F432AD');
        $this->addSql('ALTER TABLE streamer_type_of_content DROP FOREIGN KEY FK_5B6D7A9B1D19D54D');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE contract_status');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE platform_type');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('DROP TABLE proposal_status');
        $this->addSql('DROP TABLE representative');
        $this->addSql('DROP TABLE streamer');
        $this->addSql('DROP TABLE streamer_category');
        $this->addSql('DROP TABLE streamer_company');
        $this->addSql('DROP TABLE streamer_platform');
        $this->addSql('DROP TABLE streamer_type_of_content');
        $this->addSql('DROP TABLE type_of_content');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
