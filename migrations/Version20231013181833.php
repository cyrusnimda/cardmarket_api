<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013181833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, edition VARCHAR(3) NOT NULL, image VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE User ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP createdAt, CHANGE id id VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE User RENAME INDEX user_username_key TO UNIQ_8D93D649F85E0677');
        $this->addSql('ALTER TABLE User RENAME INDEX user_email_key TO UNIQ_8D93D649E7927C74');
        $this->addSql('ALTER TABLE card ADD price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE card');
        $this->addSql('ALTER TABLE user ADD createdAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, DROP created_at, CHANGE id id VARCHAR(191) NOT NULL, CHANGE username username VARCHAR(191) NOT NULL, CHANGE email email VARCHAR(191) NOT NULL, CHANGE password password VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO User_username_key');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO User_email_key');
    }
}
