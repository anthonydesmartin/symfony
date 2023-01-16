<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116141903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform CHANGE has_type_id has_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE proposal CHANGE streamer_id streamer_id INT NOT NULL, CHANGE company_id company_id INT NOT NULL, CHANGE has_proposal_status_id has_proposal_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE representative CHANGE has_representative_id has_representative_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform CHANGE has_type_id has_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proposal CHANGE streamer_id streamer_id INT DEFAULT NULL, CHANGE company_id company_id INT DEFAULT NULL, CHANGE has_proposal_status_id has_proposal_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE representative CHANGE has_representative_id has_representative_id INT DEFAULT NULL');
    }
}
