<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SqlController extends AbstractController
{
    private $connection;

    public function __construct()
    {
        $this->connection = \Doctrine\DBAL\DriverManager::getConnection([
            'url' => "mysql://admin:password@127.0.0.1:3306/AntoineFactory?serverVersion=10.6.12-MariaDB"
        ]);
    }

    #[Route('/productinsert', name: 'sql.productInsert', methods: ['GET'])]
    public function productInsert(): Response
    {
        if ($this->isGranted("ROLE_ADMIN")){
            $sql = "
            INSERT INTO product (nom, prix, promotion, nb_ventes, stock, description, materiau, longueur, largeur, epaisseur, image_name, categorie, status)
            VALUES 
            ('Sous verre Craft Beer', 5.99, 5, 20, 50, 'Sous verre personnalisé bière Craft Beer.', 'Bois', 15, 10, 50, 'dessous_verre1.png', 'Sous_verre', 'new'),
            ('Sous verre La Cavayou', 15.99, 0, 20, 50, 'Sous verre personnalisé La Cavayou cave à bierre Saint Nazaire.', 'Bois', 150, 100, 50, 'dessous_verre2.png', 'Sous_verre', 'new'),
            ('Sous verre Olympique de Marseille ', 15.99, 0, 20, 50, 'Porte clé personnalisé Olympique de Marseille Droit au but.', 'Bois', 150, 100, 50, 'dessous_verre3.webp', 'Sous_verre', 'new'),
            ('Sous verre Premium Beer', 15.99, 0, 20, 50, 'Sous verre personnalisé bière Premium Beer.', 'Bois', 150, 100, 50, 'dessous_verre4.png', 'Sous_verre', 'new'),
            ('Sous verre Coffee', 15.99, 0, 20, 50, 'Sous verre personnalisé café Coffee.', 'Bois', 150, 100, 50, 'dessous_verre5.png', 'Sous_verre', 'new'),
            ('Sous verre Groot', 15.99, 0, 20, 50, 'Sous verre personnalisé I was Groot.', 'Bois', 150, 100, 50, 'dessous_verre6.webp', 'Sous_verre', 'new'),
            ('Porte-clé couple personnalisé', 15.99, 0, 20, 50, 'Porte-clé couple personnalisé.', 'Bois', 150, 100, 50, 'po_couple.webp', 'Porte_cle', 'new'),
            ('Porte-clé montagne', 15.99, 0, 20, 50, 'Porte-clé personnalisé montagne.', 'Bois', 150, 100, 50, 'po_montagne.png', 'Porte_cle', 'new'),
            ('Boite de petite taille', 15.99, 0, 20, 50, 'Boite pour ranger une ou deux bouteilles de vin.', 'Bois', 150, 100, 50, 'boite_2.jpg', 'Autre', 'new'),
            ('Grande malle', 89.99, 10, 15, 30, 'Grande malle en bois de chêne.', 'Bois', 800, 400, 300, 'coffre.webp', 'Autre', 'new'),
            ('Plateau carré', 29.99, 0, 25, 40, 'Plateau carré en bois de chêne.', 'Bois', 300, 300, 20, 'plateau_carré.jpg', 'Autre', 'new'),
            ('Porte-clé triangulaire', 7.99, 0, 30, 60, 'Porte-clé original en bois de chêne.', 'Bois', 50, 50, 5, 'po_reuleaux.png', 'Porte_clé', 'low'),
            ('Boite de taille moyenne', 19.99, 5, 18, 35, 'Boite en bois de chêne de taille moyenne.', 'Bois', 250, 150, 75, 'boite.jpg', 'Autre', 'low'),
            ('Décapsuleur mural', 12.99, 0, 22, 55, 'Décapsuleur mural pratique en bois de chêne.', 'Bois', 100, 30, 10, 'decapsuleur.jpg', 'Autre', 'low'),
            ('Médiators de guitare x5', 8.99, 0, 28, 70, 'Lot de médiators en bois de chêne.', 'Bois', 30, 25, 2, 'mediator.png', 'Autre', 'limited'),
            ('Plateau rond', 24.99, 15, 12, 25, 'Plateau rond en bois de chêne de qualité.', 'Bois', 350, 350, 25, 'plateau_rond.jpg', 'Autre', 'limited'),
            ('Porte-clé rond', 6.99, 0, 35, 80, 'Porte clé rond en bois de chêne.', 'Bois', 40, 40, 5, 'po_rond.png', 'Porte-clé', 'limited'),
            ('Boule de noël', 18.99, 0, 20, 45, 'Boule de noël en bois de chêne.', 'Bois', 100, 100, 5, 'boule_noel.jpeg', 'Boule de noël', 'new'),
            ('Dessous de verre rond', 9.99, 0, 25, 50, 'Dessous de verre rond en bois de chêne.', 'Bois', 90, 90, 5, 'dessous_verre.png', 'Sous_verre', 'new'),
            ('Pilori', 45.99, 20, 10, 20, 'Pilori en bois de chêne pour une décoration originale.', 'Bois', 200, 200, 150, 'pilori.jpg', 'Autre', 'new'),
            ('Porte-clé carré', 5.99, 0, 30, 0, 'Porte-clé carré en bois de chêne.', 'Bois', 35, 35, 5, 'po_carré.png', 'Porte-clé', 'out'),
            ('Pièce de puzzle', 14.99, 0, 18, 0, 'Pièce de puzzle agrandie en bois de chêne.', 'Bois', 120, 120, 10, 'puzzle.png', 'Autre', 'out'),
            ('Chope à bière', 19.99, 0, 25, 0, 'Chope à bière en bois de chêne.', 'Bois', 150, 80, 80, 'chope.jpg', 'Autre', 'out'),
            ('Épée en bois', 34.99, 0, 15, 30, 'Épée en bois de chêne pour les amateurs de jeux de rôle.', 'Bois', 900, 50, 20, 'épée_bois.jpg', 'Autre', 'low'),
            ('Planche à découper', 22.99, 0, 20, 40, 'Planche à découper en bois de chêne.', 'Bois', 300, 200, 20, 'planche_découpe.jpg', 'Autre', 'low'),
            ('Porte-clé rectangulaire', 7.99, 0, 30, 60, 'Porte clé rectangulaire en bois de chêne.', 'Bois', 40, 20, 5, 'po_rectangle_1.png', 'Porte_clé', 'low'),
            ('Porte éprouvette', 15.99, 0, 25, 50, 'Porte éprouvette en bois de chêne pour les amateurs de sciences.', 'Bois', 100, 30, 10, 'range_eprouvette.jpg', 'Autre', 'limited'),
            ('Dessous de verre en forme de cœur', 8.99, 0, 28, 65, 'Dessous de verre en forme de cœur en bois de chêne.', 'Bois', 80, 80, 5, 'coeur.jpg', 'Sous_verre', 'limited'),
            ('Étoile', 12.99, 0, 22, 45, 'Étoile en bois de chêne pour une décoration festive.', 'Bois', 120, 120, 10, 'etoile-bois.jpeg', 'Autre', 'limited'),
            ('Planche de shot', 16.99, 0, 23, 45, 'Planche de shot en bois de chêne pour des soirées alcoolisées','Bois',250, 150, 20, 'planche_shot.png', 'Autre', 'new'),
            ('Thermos', 29.99, 10, 15, 30, 'Thermos en bois de chêne pour garder vos boissons chaudes.', 'Bois', 200, 80, 80, 'thermo.jpg', 'Autre', 'new');
            ";
            
            try {
                $result = $this->connection->executeQuery($sql);
                $data = $result->fetchAllAssociative();
                // Process the data as needed
            } catch (\Exception $e) {
                // Handle exceptions (e.g., SQL syntax error)
                return new Response('Erreur d exécution du code SQL: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    //les params sont en get : uid, adr et tel
    //select le panier
    #[Route('/passageCommande', name: 'sql.passageCommande', methods: ['GET'])]
    public function passageCommande(Request $request): ?Response
    {
        if ($this->isGranted("ROLE_USER")){
            $uid = $request->query->get('uid', 0);
            $adr = $request->query->get('adr', '');
            $tel = $request->query->get('tel', '');

            try {
                // Execute the stored procedure
                $stmt5 = $this->connection->prepare("CALL passage_commande(?, ?, ?)");
                $stmt5->bindValue(1, $uid, \PDO::PARAM_INT);
                $stmt5->bindValue(2, $adr, \PDO::PARAM_STR);
                $stmt5->bindValue(3, $tel, \PDO::PARAM_STR);
                $stmt5->executeStatement();

                // Retrieve items that remain in the cart (not ordered)
                $quer = 'SELECT p.*, pr.* FROM panier p JOIN product pr ON p.id_product_id = pr.id WHERE p.id_user_id = :uid';
                $failedTransfers = $this->connection->executeQuery($quer, ['uid' => $uid]);

                if ($failedTransfers->rowCount() > 0) {
                    // Show the items that were not ordered
                    $failedTransfersData = $failedTransfers->fetchAllAssociative();
                    $failedTransfersString = "<b style='color:red'>Oups ! Quelques articles n'ont pas été commandés car la quantité souhaitée excédait celle disponible :</b><ul>";
                    foreach ($failedTransfersData as $transfer) {
                        $failedTransfersString .= sprintf(
                            "<li>%s en %s exemplaires alors qu'il n'y en avait que %s de disponibles</li>",
                            $transfer['nom'],
                            $transfer['quantite'],
                            $transfer['stock']
                        );
                    }

                    $failedTransfersString .= "</ul><a href='" . $this->generateUrl('panier.index', ['user_id' => $uid]) . "'>Retour au panier</a>";
                    return new Response($failedTransfersString, 200);
                } else{
                    $successPage = "<b style='color:green'>La commandé à été effectué avec succès !</b><br>";
                    $successPage .= "<a href='" . $this->generateUrl('home.index') . "'>Retour à l'accueil</a>";
                    return new Response($successPage, 200);
                } 
            } catch (\Exception $e) {
                return new Response('Erreur d exécution du code SQL: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->redirectToRoute('home.index');
        }
    }
}