<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219110346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, equipment_id INTEGER NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_42C84955517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE INDEX IDX_42C84955517FE9FE ON reservation (equipment_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, name FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO category (id, name) SELECT id, name FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__condition AS SELECT id, name FROM condition');
        $this->addSql('DROP TABLE condition');
        $this->addSql('CREATE TABLE condition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO condition (id, name) SELECT id, name FROM __temp__condition');
        $this->addSql('DROP TABLE __temp__condition');
        $this->addSql('CREATE TEMPORARY TABLE __temp__equipment AS SELECT id, category_id, location_id, stat_id, name, description, created_at, price, last_movement FROM equipment');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('CREATE TABLE equipment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, location_id INTEGER NOT NULL, stat_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(10, 2) DEFAULT NULL, last_movement DATETIME DEFAULT NULL, FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (stat_id) REFERENCES condition (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO equipment (id, category_id, location_id, stat_id, name, description, created_at, price, last_movement) SELECT id, category_id, location_id, stat_id, name, description, created_at, price, last_movement FROM __temp__equipment');
        $this->addSql('DROP TABLE __temp__equipment');
        $this->addSql('CREATE INDEX IDX_D338D58312469DE2 ON equipment (category_id)');
        $this->addSql('CREATE INDEX IDX_D338D58364D218E ON equipment (location_id)');
        $this->addSql('CREATE INDEX IDX_D338D5839502F0B ON equipment (stat_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__history AS SELECT id, equipment_id, movement_id FROM history');
        $this->addSql('DROP TABLE history');
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipment_id INTEGER NOT NULL, movement_id INTEGER NOT NULL, user_id INTEGER NOT NULL, event_date DATE NOT NULL, comment CLOB DEFAULT NULL, FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (movement_id) REFERENCES movement (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO history (id, equipment_id, movement_id) SELECT id, equipment_id, movement_id FROM __temp__history');
        $this->addSql('DROP TABLE __temp__history');
        $this->addSql('CREATE INDEX IDX_27BA704B517FE9FE ON history (equipment_id)');
        $this->addSql('CREATE INDEX IDX_27BA704B229E70A7 ON history (movement_id)');
        $this->addSql('CREATE INDEX IDX_27BA704BA76ED395 ON history (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, aisle, shelf FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, aisle VARCHAR(255) NOT NULL, shelf VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO location (id, aisle, shelf) SELECT id, aisle, shelf FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE TEMPORARY TABLE __temp__maintenance AS SELECT id, equipment_id, maintenance_type_id, maintenance_date, cost, description FROM maintenance');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('CREATE TABLE maintenance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipment_id INTEGER NOT NULL, maintenance_type_id INTEGER NOT NULL, maintenance_date DATE NOT NULL, cost NUMERIC(10, 2) DEFAULT NULL, description CLOB DEFAULT NULL, FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (maintenance_type_id) REFERENCES maintenance_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO maintenance (id, equipment_id, maintenance_type_id, maintenance_date, cost, description) SELECT id, equipment_id, maintenance_type_id, maintenance_date, cost, description FROM __temp__maintenance');
        $this->addSql('DROP TABLE __temp__maintenance');
        $this->addSql('CREATE INDEX IDX_2F84F8E9517FE9FE ON maintenance (equipment_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9BCBAC901 ON maintenance (maintenance_type_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__maintenance_type AS SELECT id, name FROM maintenance_type');
        $this->addSql('DROP TABLE maintenance_type');
        $this->addSql('CREATE TABLE maintenance_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO maintenance_type (id, name) SELECT id, name FROM __temp__maintenance_type');
        $this->addSql('DROP TABLE __temp__maintenance_type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movement AS SELECT id, equipment_id, user_id, movement_type_id, movement_date, comment FROM movement');
        $this->addSql('DROP TABLE movement');
        $this->addSql('CREATE TABLE movement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipment_id INTEGER NOT NULL, user_id INTEGER NOT NULL, movement_type_id INTEGER NOT NULL, movement_date DATE NOT NULL, comment CLOB DEFAULT NULL, FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (movement_type_id) REFERENCES movement_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movement (id, equipment_id, user_id, movement_type_id, movement_date, comment) SELECT id, equipment_id, user_id, movement_type_id, movement_date, comment FROM __temp__movement');
        $this->addSql('DROP TABLE __temp__movement');
        $this->addSql('CREATE INDEX IDX_F4DD95F7517FE9FE ON movement (equipment_id)');
        $this->addSql('CREATE INDEX IDX_F4DD95F7A76ED395 ON movement (user_id)');
        $this->addSql('CREATE INDEX IDX_F4DD95F7EA4ED04A ON movement (movement_type_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movement_type AS SELECT id, name FROM movement_type');
        $this->addSql('DROP TABLE movement_type');
        $this->addSql('CREATE TABLE movement_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO movement_type (id, name) SELECT id, name FROM __temp__movement_type');
        $this->addSql('DROP TABLE __temp__movement_type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__repair AS SELECT id, equipment_id, report_date, start_date, end_date, description FROM repair');
        $this->addSql('DROP TABLE repair');
        $this->addSql('CREATE TABLE repair (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipment_id INTEGER NOT NULL, report_date DATE NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, description CLOB DEFAULT NULL, FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO repair (id, equipment_id, report_date, start_date, end_date, description) SELECT id, equipment_id, report_date, start_date, end_date, description FROM __temp__repair');
        $this->addSql('DROP TABLE __temp__repair');
        $this->addSql('CREATE INDEX IDX_8EE43421517FE9FE ON repair (equipment_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages');
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, name FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, name CLOB NOT NULL)');
        $this->addSql('INSERT INTO category (id, name) SELECT id, name FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__condition AS SELECT id, name FROM "condition"');
        $this->addSql('DROP TABLE "condition"');
        $this->addSql('CREATE TABLE "condition" (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, name CLOB NOT NULL)');
        $this->addSql('INSERT INTO "condition" (id, name) SELECT id, name FROM __temp__condition');
        $this->addSql('DROP TABLE __temp__condition');
        $this->addSql('CREATE TEMPORARY TABLE __temp__equipment AS SELECT id, category_id, location_id, stat_id, name, description, created_at, price, last_movement FROM equipment');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('CREATE TABLE equipment (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, category_id INTEGER NOT NULL, location_id INTEGER NOT NULL, stat_id INTEGER NOT NULL, name CLOB NOT NULL, description CLOB DEFAULT NULL, created_at CLOB NOT NULL, price CLOB DEFAULT NULL, last_movement CLOB DEFAULT NULL, CONSTRAINT FK_D338D58312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D338D58364D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D338D5839502F0B FOREIGN KEY (stat_id) REFERENCES "condition" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO equipment (id, category_id, location_id, stat_id, name, description, created_at, price, last_movement) SELECT id, category_id, location_id, stat_id, name, description, created_at, price, last_movement FROM __temp__equipment');
        $this->addSql('DROP TABLE __temp__equipment');
        $this->addSql('CREATE INDEX IDX_D338D58312469DE2 ON equipment (category_id)');
        $this->addSql('CREATE INDEX IDX_D338D58364D218E ON equipment (location_id)');
        $this->addSql('CREATE INDEX IDX_D338D5839502F0B ON equipment (stat_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__history AS SELECT id, equipment_id, movement_id FROM history');
        $this->addSql('DROP TABLE history');
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, equipment_id INTEGER NOT NULL, movement_id INTEGER NOT NULL, CONSTRAINT FK_27BA704B517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_27BA704B229E70A7 FOREIGN KEY (movement_id) REFERENCES movement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO history (id, equipment_id, movement_id) SELECT id, equipment_id, movement_id FROM __temp__history');
        $this->addSql('DROP TABLE __temp__history');
        $this->addSql('CREATE INDEX IDX_27BA704B517FE9FE ON history (equipment_id)');
        $this->addSql('CREATE INDEX IDX_27BA704B229E70A7 ON history (movement_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, aisle, shelf FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, aisle CLOB NOT NULL, shelf CLOB NOT NULL)');
        $this->addSql('INSERT INTO location (id, aisle, shelf) SELECT id, aisle, shelf FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE TEMPORARY TABLE __temp__maintenance AS SELECT id, equipment_id, maintenance_type_id, maintenance_date, cost, description FROM maintenance');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('CREATE TABLE maintenance (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, equipment_id INTEGER NOT NULL, maintenance_type_id INTEGER NOT NULL, maintenance_date CLOB NOT NULL, cost CLOB DEFAULT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_2F84F8E9517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2F84F8E9BCBAC901 FOREIGN KEY (maintenance_type_id) REFERENCES maintenance_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO maintenance (id, equipment_id, maintenance_type_id, maintenance_date, cost, description) SELECT id, equipment_id, maintenance_type_id, maintenance_date, cost, description FROM __temp__maintenance');
        $this->addSql('DROP TABLE __temp__maintenance');
        $this->addSql('CREATE INDEX IDX_2F84F8E9517FE9FE ON maintenance (equipment_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9BCBAC901 ON maintenance (maintenance_type_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__maintenance_type AS SELECT id, name FROM maintenance_type');
        $this->addSql('DROP TABLE maintenance_type');
        $this->addSql('CREATE TABLE maintenance_type (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, name CLOB NOT NULL)');
        $this->addSql('INSERT INTO maintenance_type (id, name) SELECT id, name FROM __temp__maintenance_type');
        $this->addSql('DROP TABLE __temp__maintenance_type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name CLOB NOT NULL, created_at CLOB NOT NULL, available_at CLOB NOT NULL, delivered_at CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages');
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movement AS SELECT id, equipment_id, user_id, movement_type_id, movement_date, comment FROM movement');
        $this->addSql('DROP TABLE movement');
        $this->addSql('CREATE TABLE movement (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, equipment_id INTEGER NOT NULL, user_id INTEGER NOT NULL, movement_type_id INTEGER NOT NULL, movement_date CLOB NOT NULL, comment CLOB DEFAULT NULL, CONSTRAINT FK_F4DD95F7517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F4DD95F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F4DD95F7EA4ED04A FOREIGN KEY (movement_type_id) REFERENCES movement_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movement (id, equipment_id, user_id, movement_type_id, movement_date, comment) SELECT id, equipment_id, user_id, movement_type_id, movement_date, comment FROM __temp__movement');
        $this->addSql('DROP TABLE __temp__movement');
        $this->addSql('CREATE INDEX IDX_F4DD95F7517FE9FE ON movement (equipment_id)');
        $this->addSql('CREATE INDEX IDX_F4DD95F7A76ED395 ON movement (user_id)');
        $this->addSql('CREATE INDEX IDX_F4DD95F7EA4ED04A ON movement (movement_type_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movement_type AS SELECT id, name FROM movement_type');
        $this->addSql('DROP TABLE movement_type');
        $this->addSql('CREATE TABLE movement_type (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, name CLOB NOT NULL)');
        $this->addSql('INSERT INTO movement_type (id, name) SELECT id, name FROM __temp__movement_type');
        $this->addSql('DROP TABLE __temp__movement_type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__repair AS SELECT id, equipment_id, report_date, start_date, end_date, description FROM repair');
        $this->addSql('DROP TABLE repair');
        $this->addSql('CREATE TABLE repair (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, equipment_id INTEGER NOT NULL, report_date CLOB NOT NULL, start_date CLOB NOT NULL, end_date CLOB NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_8EE43421517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO repair (id, equipment_id, report_date, start_date, end_date, description) SELECT id, equipment_id, report_date, start_date, end_date, description FROM __temp__repair');
        $this->addSql('DROP TABLE __temp__repair');
        $this->addSql('CREATE INDEX IDX_8EE43421517FE9FE ON repair (equipment_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, email CLOB NOT NULL, roles CLOB NOT NULL, password CLOB NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
