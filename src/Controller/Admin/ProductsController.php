<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le voter
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));

        // On créé un nouveau produit
        $product = new Products();

        // On créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On stocke le produit
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/produits/edition/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $product);

        // On créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On stocke le produit
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView()
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
