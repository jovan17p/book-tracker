<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429000725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE comment_likes (comment_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(comment_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E050D68CF8697D13 ON comment_likes (comment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E050D68CA76ED395 ON comment_likes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment_dislikes (comment_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(comment_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_30B7DD17F8697D13 ON comment_dislikes (comment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_30B7DD17A76ED395 ON comment_dislikes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_likes ADD CONSTRAINT FK_E050D68CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_likes ADD CONSTRAINT FK_E050D68CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_dislikes ADD CONSTRAINT FK_30B7DD17F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_dislikes ADD CONSTRAINT FK_30B7DD17A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_likes DROP CONSTRAINT FK_E050D68CF8697D13
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_likes DROP CONSTRAINT FK_E050D68CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_dislikes DROP CONSTRAINT FK_30B7DD17F8697D13
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_dislikes DROP CONSTRAINT FK_30B7DD17A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment_likes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment_dislikes
        SQL);
    }
}
