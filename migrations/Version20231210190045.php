<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210190045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Creación de la tabla 'product' y 'messenger_messages' que ya tienes
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, img VARCHAR(255) DEFAULT NULL, title VARCHAR(40) NOT NULL, body VARCHAR(2000) NOT NULL, price NUMERIC(10, 0) DEFAULT NULL, units NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    
        // Adición de la tabla 'user'
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            username VARCHAR(255) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            roles JSON NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

$this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL');
    }
    

    public function down(Schema $schema): void
{
    // Eliminación de las tablas 'product', 'messenger_messages' y 'user'
    $this->addSql('DROP TABLE product');
    $this->addSql('DROP TABLE messenger_messages');
    $this->addSql('DROP TABLE user');
}





}
