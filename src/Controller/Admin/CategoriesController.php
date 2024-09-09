<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    function index(Categories $categories, CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll([]);

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categories/ajout', name: 'categories_add')]
    function add(): Response
    {

        return $this->render('admin/categories/add.html.twig', []);
    }
}
