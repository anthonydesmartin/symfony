<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117153206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE streamer_category (streamer_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_2B10584025F432AD (streamer_id), INDEX IDX_2B10584012469DE2 (category_id), PRIMARY KEY(streamer_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_company (streamer_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_111105B025F432AD (streamer_id), INDEX IDX_111105B0979B1AD6 (company_id), PRIMARY KEY(streamer_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_platform (streamer_id INT NOT NULL, platform_id INT NOT NULL, INDEX IDX_140E914A25F432AD (streamer_id), INDEX IDX_140E914AFFE6496F (platform_id), PRIMARY KEY(streamer_id, platform_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE streamer_type_of_content (streamer_id INT NOT NULL, type_of_content_id INT NOT NULL, INDEX IDX_5B6D7A9B25F432AD (streamer_id), INDEX IDX_5B6D7A9B1D19D54D (type_of_content_id), PRIMARY KEY(streamer_id, type_of_content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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
        $this->addSql('ALTER TABLE streamer_category DROP FOREIGN KEY FK_2B10584025F432AD');
        $this->addSql('ALTER TABLE streamer_category DROP FOREIGN KEY FK_2B10584012469DE2');
        $this->addSql('ALTER TABLE streamer_company DROP FOREIGN KEY FK_111105B025F432AD');
        $this->addSql('ALTER TABLE streamer_company DROP FOREIGN KEY FK_111105B0979B1AD6');
        $this->addSql('ALTER TABLE streamer_platform DROP FOREIGN KEY FK_140E914A25F432AD');
        $this->addSql('ALTER TABLE streamer_platform DROP FOREIGN KEY FK_140E914AFFE6496F');
        $this->addSql('ALTER TABLE streamer_type_of_content DROP FOREIGN KEY FK_5B6D7A9B25F432AD');
        $this->addSql('ALTER TABLE streamer_type_of_content DROP FOREIGN KEY FK_5B6D7A9B1D19D54D');
        $this->addSql('DROP TABLE streamer_category');
        $this->addSql('DROP TABLE streamer_company');
        $this->addSql('DROP TABLE streamer_platform');
        $this->addSql('DROP TABLE streamer_type_of_content');
    }
}
