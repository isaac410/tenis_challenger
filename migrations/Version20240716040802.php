<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240716040802 extends AbstractMigration {

    public function getDescription(): string {
        return 'Insert 16 players (8 male and 8 female)';
    }

    public function up(Schema $schema): void {
        $this->addSql("INSERT INTO player (name, lastname, gender, power, speed, reaction, created_at, updated_at) VALUES
            ('John', 'Doe', 'male', 80, 75, 70, NOW(), NOW()),
            ('Mike', 'Smith', 'male', 85, 80, 75, NOW(), NOW()),
            ('Steve', 'Johnson', 'male', 78, 85, 80, NOW(), NOW()),
            ('Robert', 'Brown', 'male', 90, 70, 85, NOW(), NOW()),
            ('Chris', 'Davis', 'male', 88, 77, 65, NOW(), NOW()),
            ('James', 'Wilson', 'male', 82, 83, 78, NOW(), NOW()),
            ('David', 'Moore', 'male', 80, 79, 90, NOW(), NOW()),
            ('Daniel', 'Taylor', 'male', 77, 82, 88, NOW(), NOW()),
            ('Emma', 'White', 'female', 78, 85, 70, NOW(), NOW()),
            ('Olivia', 'Harris', 'female', 80, 83, 75, NOW(), NOW()),
            ('Sophia', 'Martin', 'female', 85, 78, 80, NOW(), NOW()),
            ('Isabella', 'Thompson', 'female', 90, 75, 85, NOW(), NOW()),
            ('Ava', 'Garcia', 'female', 88, 77, 65, NOW(), NOW()),
            ('Mia', 'Martinez', 'female', 82, 83, 78, NOW(), NOW()),
            ('Amelia', 'Robinson', 'female', 80, 79, 90, NOW(), NOW()),
            ('Harper', 'Clark', 'female', 77, 82, 88, NOW(), NOW())
        ");
    }

    public function down(Schema $schema): void {
        $this->addSql("DELETE FROM player WHERE name IN ('John', 'Mike', 'Steve', 'Robert', 'Chris', 'James', 'David', 'Daniel', 'Emma', 'Olivia', 'Sophia', 'Isabella', 'Ava', 'Mia', 'Amelia', 'Harper')");
    }
}
