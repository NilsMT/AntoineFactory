<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;


use App\Entity\Panier;
use App\Entity\Commande;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PanierController extends AbstractController{
    /**
     * Récupère le contenu du panier et son total de l'utilisateur et retourne la page liée
     */

    #[Route('/panier/{user_id}', "panier.index", methods:['GET'])]
    public function index(UserRepository $repository, Int $user_id): Response{
            $user = $repository->findOneBy(['id'=>$user_id]);
            if($user  != $this->getUser()){
                return $this->redirectToRoute('error');
            } 
            $panier = $user->getPanier();

            $total = 0;
            foreach ($panier as $product): 
                $prixArticle = $product->getIdproduct()->getPrix()*(1-$product->getIdproduct()->getPromotion()/100);
                $quantite = $product->getQuantite();
                $total += $prixArticle * $quantite;
            endforeach;

            return $this->render('pages/user/panier.html.twig',[
                'products' => $panier,
                'prixTotal' => $total
            ]);
    }
    /**
     * Permet à un utilisateur connecté d'ajouter un article dans son panier exclusivement et le redirige vers ce dernier
     */
    #[Route('/panier/add/{id_product}', "panier.add", methods:['GET', 'POST'])]

    public function add(Request $request, int $id_product, ProductRepository $repository_product, UserRepository $repository_user, EntityManagerInterface $manager): Response{
        $user = $repository_user->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        if($user  != $this->getUser()){
            return $this->redirectToRoute('error');
        }
        $product = $repository_product->findOneBy(['id'=>$id_product]);
        $quantite = $request->request->get('quantity');
        $panier = new Panier();
        $panier->setIdUser($user);
        $panier->setIdProduct($product);
        $panier->setQuantite($quantite);
        if ($user->addPanier($panier) and $product->addPanier($panier)){
            $manager->flush();
            return $this->redirectToRoute('panier.index', ['user_id' => $user->getId()]);
        }
        $manager->persist($user);
        $manager->persist($panier);

        $manager->flush();

        return $this->redirectToRoute('panier.index', ['user_id' => $user->getId()]);
    }  

    /**
    * Permet à un utilisateur connecté de supprimer un article dans son panier exclusivement et le redirige vers ce dernier
     */
    #[Route('/panier/delete/{id_product}', "panier.delete", methods:['GET', 'POST'])]

    public function delete(int $id_product, ProductRepository $repository_product, UserRepository $repository_user, EntityManagerInterface $manager): Response{
        $user = $repository_user->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        if($user  != $this->getUser()){
            return $this->redirectToRoute('error');
        }
        $product = $repository_product->findOneBy(['id'=>$id_product]);
        $panier = new Panier();
        $panier->setIdUser($user);
        $panier->setIdProduct($product);
        $panier->setQuantite(0);
        if ($user->addPanier($panier) and $product->addPanier($panier)){
                $user->removePanier($panier);
                $manager->persist($user);
                $manager->remove($panier);
                $manager->flush();
                return $this->redirectToRoute('panier.index', ['user_id' => $user->getId()]);
        }     
        return $this->redirectToRoute('panier.index', ['user_id' => $user->getId()]);
    }  
    /**
     * Recupere le panier de l'utilisateur et permet le récapitulatif de la commande
    * */
    #[Route('/panier/commande', "panier.commande", methods:['GET', 'POST'])]

    public function commande( UserRepository $repository_user): Response{
        $user = $repository_user->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        if($user  != $this->getUser()){
            return $this->redirectToRoute('error');
        }
        $total = 0;
        foreach ($user->getPanier() as $product): 
            $prixArticle = $product->getIdproduct()->getPrix()*(1-$product->getIdproduct()->getPromotion()/100);
            $quantite = $product->getQuantite();
            $total += $prixArticle * $quantite;
        endforeach;
        return $this->render('pages/user/commande.html.twig',[
            'panier'=> $user->getPanier(),
            'total' => $total
        ]);
    }  

    /**
     * Retourne la page de paiement
     */
    #[Route('/panier/commande/paiement', "panier.paiement", methods:['GET', 'POST'])]

    public function paiement( UserRepository $repository_user): Response{
        $user = $repository_user->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        if($user  != $this->getUser()){
            return $this->redirectToRoute('error');
        }
        return $this->render('pages/security/paiement.html.twig',[
        ]);
    }  

    /**
     * Met le stock des articles et son nombre de ventes à jour, vide le panier de l'utilisateur et met à jour la table commande 
     */
    

    /*
    #[Route('/panier/commande/finalisation', "panier.finalisation", methods:['GET', 'POST'])]
    public function finalisation( UserRepository $repository_user,EntityManagerInterface $manager): Response{
        $user = $repository_user->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        if ($user != $this->getUser()) {
            return $this->redirectToRoute('error');
        }
        $total = 0;
        foreach ($user->getPanier() as $product): 
            $prixArticle = $product->getIdproduct()->getPrix()*(1-$product->getIdproduct()->getPromotion()/100);
            $quantite = $product->getQuantite();
            $total += $prixArticle * $quantite;
        endforeach;
        foreach ($user->getPanier() as $article){
            $commande = new Commande();
            $id_article = $article->getIdProduct()->getId();
            $produit = $article->getIdProduct();
            $commande->setIdUser($user->getId());
            $commande->setIdProduct($id_article);
            $commande->setUser($user);
            $commande->setAdresse("3 rue de la prison");
            $commande->setNomProduit($article->getIdProduct()->getNom());
            $commande->setTelephone("0123456789");
            $commande->setTotal($total);
            $commande->setQuantite($article->getQuantite());
            $user->removePanier($article);
            $produit->setStock($produit->getStock()-$commande->getQuantite());
            $produit->setNbVentes($produit->getNbVentes()+$commande->getQuantite());
            $manager->persist($user);
            $manager->remove($article);
            $manager->persist($commande);
            
        }    
        $manager->flush();
    
        return $this->render('pages/user/informations.html.twig', [
            'user_id' => $user->getId(),
            'user'=>$user
        ]);
    }*/

    #[Route('/panier/commande/finalisation', name: 'panier.finalisation', methods: ['GET', 'POST'])]
    public function finalisation(Request $request, UserRepository $repository_user, EntityManagerInterface $manager): Response
    {
        $user = $repository_user->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        if ($user != $this->getUser()) {
            return $this->redirectToRoute('error');
        }

        $adresse = $request->request->get('adresse');
        $tel = $request->request->get('tel');

        // Build the URL for passageCommande
        $passageCommandeUrl = $this->generateUrl('sql.passageCommande', [
            'uid' => $user->getId(),
            'adr' => $adresse,
            'tel' => $tel,
        ]);

        // Redirect to passageCommande
        return new RedirectResponse($passageCommandeUrl);
    }
}