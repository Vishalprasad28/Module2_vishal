<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411103030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bucket (id INT AUTO_INCREMENT NOT NULL, book_id_id INT NOT NULL, added_by_id INT NOT NULL, INDEX IDX_E73F36A671868B2E (book_id_id), INDEX IDX_E73F36A655B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bucket ADD CONSTRAINT FK_E73F36A671868B2E FOREIGN KEY (book_id_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE bucket ADD CONSTRAINT FK_E73F36A655B127A4 FOREIGN KEY (added_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE books CHANGE cover_img cover_img VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket DROP FOREIGN KEY FK_E73F36A671868B2E');
        $this->addSql('ALTER TABLE bucket DROP FOREIGN KEY FK_E73F36A655B127A4');
        $this->addSql('DROP TABLE bucket');
        $this->addSql('ALTER TABLE books CHANGE cover_img cover_img VARCHAR(255) DEFAULT \'coverImg/default.png\'');
    }
}
