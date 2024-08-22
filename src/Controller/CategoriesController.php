<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Controller\Traits\CategoriesTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'index')]
    public function index(string $slug, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        // Ici on a récupéré le nom de la catégorie spécifique
        $category = $categoriesRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $products = $category->getProducts();

        return $this->render('categories/index.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
