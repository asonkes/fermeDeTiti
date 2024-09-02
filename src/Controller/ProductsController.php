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
        $products = $productsRepository->findProductsPaginated($page, $category->getSlug(), 6);

        return $this->render('products/index.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
