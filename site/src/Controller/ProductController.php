<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /** 
     * Permet de rediriger vers le catalogue et de récupérer la liste de produit
    */
    #[Route('/catalogue', 'product.index', methods: ['GET','POST'])]
    public function index(ProductRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $products =  $repository->findAll();
        usort($products,[Product::class, 'meilleures_ventes']);
        // $products = $paginator->paginate(
        //     $tmp, /* query*/
        //     $request->query->getInt('page', 1), /*page number*/
        //     12
        // );

        return $this->render('pages/product/catalogue.html.twig', [
            'products' => $products
        ]);
    }
    
    /** 
     * Permet de modifier les donées lié à un produit, en tant qu'admin
    */
    #[Route('/product/edit/{id}', 'product.edit', methods:['GET', 'POST'])]
    public function edit(ProductRepository $repository, Int $id, Request $request, EntityManagerInterface $manager ): Response{

        if ($this->isGranted("ROLE_ADMIN")){
            $product = $repository->findOneBy(["id" => $id]);
            $form = $this->createForm(ProductType::class, $product,[
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $products=$form->getData();
            $manager->persist($products);
            $manager->flush();
            return $this->redirectToRoute('product.inventaire');
        }

            return $this->render('pages/product/edition.html.twig', [
                 'form' => $form->createView()
            ]);
        }else{
            return $this->render('pages/home.html.twig', [
               
           ]);
        }
    }
    
    /** 
     * Permet de supprimer un produit, en tant qu'admin
    */
    #[Route('/product/delete/{id}', 'product.delete', methods:['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, ProductRepository $repository, Int $id) : Response {
        if ($this->isGranted("ROLE_ADMIN")){
            $product = $repository->findOneBy(["id" => $id]);
            if ($product){
                $manager->remove($product);
                $manager->flush();
                return $this->redirectToRoute('product.inventaire');
            }else{
                return $this->redirectToRoute('product.inventaire');
            }
        }else{
            return $this->redirectToRoute('product.inventaire');

        }
    }
    
    /** 
     * Permet d'afficher la liste des produit pour la gestion, en tant qu'admin
    */
    #[Route('/product/inventaire', 'product.inventaire', methods:['GET', 'POST'])]
    public function inventaire(ProductRepository $repository, PaginatorInterface $paginator,  Request $request): Response{
        if ($this->isGranted("ROLE_ADMIN")){
            $products = $paginator->paginate(
                $repository->findAll(), /* query*/
                $request->query->getInt('page',1 ), /*page number*/
                5
            );

            return $this->render('pages/product/inventaire.html.twig', [
                 'products' => $products
            ]);
        }else{
            return $this->redirectToRoute('home.index');
        }
    }

    /** 
     * Permet de rediriger vers la page d'information du produit
    */
    #[Route('/product/informations/{id}', 'product.info', methods:['GET', 'POST'])]
    public function info(ProductRepository $repository, Int $id): Response{

        $product = $repository->findOneBy(["id" => $id]);

        return $this->render('pages/product/information.html.twig', [
             'product' => $product
        ]);
    }

    /** 
     * Permet de trier les produit dans le catalogue
    */
    #[Route('/product/catalogue/tri', 'product.tri', methods:['GET', 'POST'])]
    public function tri(ProductRepository $repository, Request $request): Response{

        
        
        if ( $request->request->get('filter') != "Tous" ){
            if ($request->request->get('sort_method') == 'promo'){
                $products = $repository->createQueryBuilder('p')
                    ->where('p.promotion != 0 AND p.categorie = :filter')
                    ->setParameter('filter', $request->request->get('filter'))
                    ->getQuery()
                    ->getResult();
                usort($products,[Product::class, $request->request->get('sort_method')]);
            }else{
                $products =  $repository->findBy(['categorie'=> $request->request->get('filter')]);
                usort($products,[Product::class, $request->request->get('sort_method')]);
            }
        }else{
            if ($request->request->get('sort_method') == 'promo'){
                $products = $repository->createQueryBuilder('p')
                    ->where('p.promotion != 0')
                    ->getQuery()
                    ->getResult();
                usort($products,[Product::class, $request->request->get('sort_method')]);
            }else{
                $products =  $repository->findAll();
                usort($products,[Product::class, $request->request->get('sort_method')]);
            }
        }


        return $this->render('pages/product/catalogue.html.twig', [
            'products' => $products
        ]);
    }

    /** 
     * Permet de cherhcer des produit dans le catalogue
    */
    #[Route('/product/catalogue/search', 'product.search', methods:['GET', 'POST'])]
    public function search(ProductRepository $repository, Request $request): Response
    {
        $searchTerm = $request->request->get('search');

        $products = $repository->createQueryBuilder('p')
            ->where('p.nom LIKE :searchTerm OR p.description LIKE :searchTerm OR p.materiau LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        return $this->render('pages/product/catalogue.html.twig', [
            'products' => $products
        ]);
    }

    /** 
     * Permet d'ajouter un produit sur le site, en tant qu'admin
    */
    #[Route('/product/nouveau', 'product.new', methods:['GET', 'POST'])]
    public function new(ProductRepository $repository , Request $request, EntityManagerInterface $manager ): Response{
        
        if ($this->isGranted("ROLE_ADMIN")){
           
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $products=$form->getData();
                $manager->persist($products);
                $manager->flush();
                return $this->redirectToRoute('product.inventaire');
            }
            
            return $this->render('pages/product/nouveau.html.twig', [
                 'form' => $form->createView()
            ]);
        }else{
            return $this->render('pages/home.html.twig', [
               
           ]);
        }
    }


}
