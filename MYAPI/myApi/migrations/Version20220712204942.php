<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712204942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE video_id video_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE token CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video_format CHANGE video_id video_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT NOT NULL, CHANGE video_id video_id INT NOT NULL');
        $this->addSql('ALTER TABLE token CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP roles');
        $this->addSql('ALTER TABLE video CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE video_format CHANGE video_id video_id INT NOT NULL');
    }
}
