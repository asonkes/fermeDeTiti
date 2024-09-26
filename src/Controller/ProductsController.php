<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/{slug}', name: 'index')]
    public function index(string $slug, CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository, Request $request): Response
    {
        // On va chercher le numéro de page dans l'url
        $page = $request->query->getInt('page', 1);

        // Ici on a récupéré le nom de la catégorie spécifique
        $category = $categoriesRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // Pagination commence
        // (1, $category->getSlug(), 2) ==> 1 est le numéro de la page et 2 la limite
        $products = $productsRepository->findProductsPaginated($page, $category->getSlug(), 8);

        return $this->render('products/index.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }

    #[Route('/{category_slug}/article/{product_slug}', name: 'article')]
    public function article(string $category_slug, string $product_slug, ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        // Récupération du produit à partir de son slug
        $product = $productsRepository->findOneBy(['slug' => $product_slug]);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        // Récupération du producteur du produit
        $producer = $product->getProducer();

        // Récupération de la catégorie pour le lien de retour
        $category = $categoriesRepository->findOneBy(['slug' => $category_slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        //Récupération des autres produits du producteur
        $otherProducts = $productsRepository->findOtherProductsByProducer($producer->getId(), $product->getId(), 6);

        // Récupération des autres produits2
        $otherProducts2 = $productsRepository->findOtherProducts2(4);

        return $this->render('article/index.html.twig', [
            'product' => $product,
            'producer' => $producer,
            'category' => $category,
            'otherProducts' => $otherProducts,
            'otherProducts2' => $otherProducts2
        ]);
    }
}
