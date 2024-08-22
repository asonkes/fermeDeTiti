<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Controller\Traits\CategoriesTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll([], ['categoryOrder' => 'asc']);

        return $this->render('home/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
