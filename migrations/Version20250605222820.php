<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605222820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__news_category AS SELECT news_id, category_id FROM news_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE news_category
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE news_category (news_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(news_id, category_id), CONSTRAINT FK_4F72BA90B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4F72BA9012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO news_category (news_id, category_id) SELECT news_id, category_id FROM __temp__news_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__news_category
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA9012469DE2 ON news_category (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA90B5A459A0 ON news_category (news_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__news_category AS SELECT news_id, category_id FROM news_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE news_category
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE news_category (news_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(news_id, category_id), CONSTRAINT FK_4F72BA90B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4F72BA9012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO news_category (news_id, category_id) SELECT news_id, category_id FROM __temp__news_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__news_category
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA90B5A459A0 ON news_category (news_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4F72BA9012469DE2 ON news_category (category_id)
        SQL);
    }
}
