<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507145302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE book_likes (book_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(book_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_47085B0416A2B381 ON book_likes (book_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_47085B04A76ED395 ON book_likes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book_dislikes (book_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(book_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_340CA84816A2B381 ON book_dislikes (book_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_340CA848A76ED395 ON book_dislikes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_likes ADD CONSTRAINT FK_47085B0416A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_likes ADD CONSTRAINT FK_47085B04A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_dislikes ADD CONSTRAINT FK_340CA84816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_dislikes ADD CONSTRAINT FK_340CA848A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_likes DROP CONSTRAINT FK_47085B0416A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_likes DROP CONSTRAINT FK_47085B04A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_dislikes DROP CONSTRAINT FK_340CA84816A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_dislikes DROP CONSTRAINT FK_340CA848A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book_likes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book_dislikes
        SQL);
    }
}
