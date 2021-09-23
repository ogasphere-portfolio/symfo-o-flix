<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923090808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode ADD season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA4EC001D1 ON episode (season_id)');
        $this->addSql('ALTER TABLE play ADD charact_id INT NOT NULL, ADD tv_show_id INT NOT NULL');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBA39306F15 FOREIGN KEY (charact_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBA5E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id)');
        $this->addSql('CREATE INDEX IDX_5E89DEBA39306F15 ON play (charact_id)');
        $this->addSql('CREATE INDEX IDX_5E89DEBA5E3A35BB ON play (tv_show_id)');
        $this->addSql('ALTER TABLE season ADD tv_show_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA95E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id)');
        $this->addSql('CREATE INDEX IDX_F0E45BA95E3A35BB ON season (tv_show_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA4EC001D1');
        $this->addSql('DROP INDEX IDX_DDAA1CDA4EC001D1 ON episode');
        $this->addSql('ALTER TABLE episode DROP season_id');
        $this->addSql('ALTER TABLE play DROP FOREIGN KEY FK_5E89DEBA39306F15');
        $this->addSql('ALTER TABLE play DROP FOREIGN KEY FK_5E89DEBA5E3A35BB');
        $this->addSql('DROP INDEX IDX_5E89DEBA39306F15 ON play');
        $this->addSql('DROP INDEX IDX_5E89DEBA5E3A35BB ON play');
        $this->addSql('ALTER TABLE play DROP charact_id, DROP tv_show_id');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA95E3A35BB');
        $this->addSql('DROP INDEX IDX_F0E45BA95E3A35BB ON season');
        $this->addSql('ALTER TABLE season DROP tv_show_id');
    }
}
