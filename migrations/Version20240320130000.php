<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial schema for news portal';
    }

    public function up(Schema $schema): void
    {
        // Create category table
        $this->addSql('CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create news table
        $this->addSql('CREATE TABLE news (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            short_description LONGTEXT NOT NULL,
            content LONGTEXT NOT NULL,
            insert_date DATETIME NOT NULL,
            picture VARCHAR(255) DEFAULT NULL,
            view_count INT NOT NULL DEFAULT 0,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create comment table
        $this->addSql('CREATE TABLE comment (
            id INT AUTO_INCREMENT NOT NULL,
            news_id INT NOT NULL,
            author_name VARCHAR(255) NOT NULL,
            content LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_9474526C72A6B8B3 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create news_category table for many-to-many relationship
        $this->addSql('CREATE TABLE news_category (
            news_id INT NOT NULL,
            category_id INT NOT NULL,
            PRIMARY KEY(news_id, category_id),
            CONSTRAINT FK_4F4A68AD72A6B8B3 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE,
            CONSTRAINT FK_4F4A68AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create indexes
        $this->addSql('CREATE INDEX IDX_9474526C72A6B8B3 ON comment (news_id)');
        $this->addSql('CREATE INDEX IDX_4F4A68AD72A6B8B3 ON news_category (news_id)');
        $this->addSql('CREATE INDEX IDX_4F4A68AD12469DE2 ON news_category (category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE news_category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE category');
    }
} 