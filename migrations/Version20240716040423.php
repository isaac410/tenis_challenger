<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716040423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE match_game (id INT AUTO_INCREMENT NOT NULL, player_a_id INT NOT NULL, player_b_id INT NOT NULL, tournament_fase_id INT NOT NULL, winner INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_424480FE99C4036B (player_a_id), INDEX IDX_424480FE8B71AC85 (player_b_id), INDEX IDX_424480FEF6270058 (tournament_fase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, gender VARCHAR(255) NOT NULL, power INT NOT NULL, speed INT NOT NULL, reaction INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, gender VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_BD5FB8D95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_fase (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_785BDB0833D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE99C4036B FOREIGN KEY (player_a_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE8B71AC85 FOREIGN KEY (player_b_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FEF6270058 FOREIGN KEY (tournament_fase_id) REFERENCES tournament_fase (id)');
        $this->addSql('ALTER TABLE tournament_fase ADD CONSTRAINT FK_785BDB0833D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE99C4036B');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE8B71AC85');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FEF6270058');
        $this->addSql('ALTER TABLE tournament_fase DROP FOREIGN KEY FK_785BDB0833D1A3E7');
        $this->addSql('DROP TABLE match_game');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_fase');
    }
}
