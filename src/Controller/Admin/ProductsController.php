<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class ProductsController extends AbstractController
{
    #[Route('/produits', name: 'products')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/produits/ajout', name: 'add')]
    public function add(): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le voter
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));

        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/produits/edition/{id}', name: 'edit')]
    public function edit(Products $product): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $product);

        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/produits/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $product);

        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
