<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014145613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `condition` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, location_id INT NOT NULL, stat_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price NUMERIC(10, 2) DEFAULT NULL, last_movement DATETIME DEFAULT NULL, INDEX IDX_D338D58312469DE2 (category_id), INDEX IDX_D338D58364D218E (location_id), INDEX IDX_D338D5839502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, movement_id INT NOT NULL, INDEX IDX_27BA704B517FE9FE (equipment_id), INDEX IDX_27BA704B229E70A7 (movement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, aisle VARCHAR(255) NOT NULL, shelf VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, maintenance_type_id INT NOT NULL, maintenance_date DATE NOT NULL, cost NUMERIC(10, 2) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2F84F8E9517FE9FE (equipment_id), INDEX IDX_2F84F8E9BCBAC901 (maintenance_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, user_id INT NOT NULL, movement_type_id INT NOT NULL, movement_date DATE NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_F4DD95F7517FE9FE (equipment_id), INDEX IDX_F4DD95F7A76ED395 (user_id), INDEX IDX_F4DD95F7EA4ED04A (movement_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, report_date DATE NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_8EE43421517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D58312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D58364D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D5839502F0B FOREIGN KEY (stat_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B229E70A7 FOREIGN KEY (movement_id) REFERENCES movement (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9BCBAC901 FOREIGN KEY (maintenance_type_id) REFERENCES maintenance_type (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7EA4ED04A FOREIGN KEY (movement_type_id) REFERENCES movement_type (id)');
        $this->addSql('ALTER TABLE repair ADD CONSTRAINT FK_8EE43421517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D58312469DE2');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D58364D218E');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D5839502F0B');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B517FE9FE');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B229E70A7');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9517FE9FE');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9BCBAC901');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F7517FE9FE');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F7A76ED395');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F7EA4ED04A');
        $this->addSql('ALTER TABLE repair DROP FOREIGN KEY FK_8EE43421517FE9FE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `condition`');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('DROP TABLE maintenance_type');
        $this->addSql('DROP TABLE movement');
        $this->addSql('DROP TABLE movement_type');
        $this->addSql('DROP TABLE repair');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
