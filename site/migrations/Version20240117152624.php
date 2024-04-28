<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117152624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_product INT NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, INDEX IDX_6EEAA67D6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, id_product_id INT NOT NULL, id_user_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_24CC0DF2E00EE68D (id_product_id), INDEX IDX_24CC0DF279F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, promotion INT NOT NULL, nb_ventes INT NOT NULL, stock INT NOT NULL, description LONGTEXT NOT NULL, materiau VARCHAR(255) NOT NULL, longueur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, epaisseur DOUBLE PRECISION NOT NULL, image_name VARCHAR(255) DEFAULT NULL, categorie VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF279F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2E00EE68D');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF279F37AE5');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
