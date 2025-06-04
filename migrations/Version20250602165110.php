<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602165110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, news_id INTEGER NOT NULL, author_name VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, email VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_9474526CB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526CB5A459A0 ON comment (news_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE news (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, short_description CLOB NOT NULL, content CLOB NOT NULL, insert_date DATETIME NOT NULL, picture VARCHAR(255) DEFAULT NULL, view_count INTEGER DEFAULT 0 NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE news_category (news_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(news_id, category_id), CONSTRAINT FK_4F72BA90B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4F72BA9012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA90B5A459A0 ON news_category (news_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA9012469DE2 ON news_category (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE news
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE news_category
        SQL);
    }
}
