<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116141606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859E3908D26');
        $this->addSql('DROP INDEX IDX_E98F2859E3908D26 ON contract');
        $this->addSql('ALTER TABLE contract ADD company_id INT NOT NULL, DROP compagny_contract_id, CHANGE streamer_id streamer_id INT NOT NULL');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_E98F2859979B1AD6 ON contract (company_id)');
        $this->addSql('ALTER TABLE contract_status CHANGE contract_id contract_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859979B1AD6');
        $this->addSql('DROP INDEX IDX_E98F2859979B1AD6 ON contract');
        $this->addSql('ALTER TABLE contract ADD compagny_contract_id INT DEFAULT NULL, DROP company_id, CHANGE streamer_id streamer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859E3908D26 FOREIGN KEY (compagny_contract_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_E98F2859E3908D26 ON contract (compagny_contract_id)');
        $this->addSql('ALTER TABLE contract_status CHANGE contract_id contract_id INT DEFAULT NULL');
    }
}
