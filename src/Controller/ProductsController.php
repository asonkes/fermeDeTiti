<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/{slug}', name: 'index')]
    public function index(string $slug, CategoriesRepository $categoriesRepository): Response
    {
        // Ici on a récupéré le nom de la catégorie spécifique
        $category = $categoriesRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $products = $category->getProducts();

        return $this->render('products/index.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
