<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Service\PictureService;
use App\Repository\ProductsRepository;
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
    public function index(Products $products, ProductsRepository $productsRepository): Response
    {

        $products = $productsRepository->findAll([]);

        return $this->render('admin/products/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/produits/ajout', name: 'products_add')]
    public function addProduct(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, PictureService $pictureService)
    {

        $product = new Products();

        // On créé le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // Récupérer le fichier image
            $image = $productForm->get('image')->getData();

            if ($image) {
                // Définir le dossier de destination
                $folder = 'articles';

                // Appeler le service d'ajout pour gérer l'image
                $fichier = $pictureService->add($image, $folder, 250, 350);

                // Appeler le setter pour hydrater la propriété image
                $product->setImage($fichier);
            }

            // On stocke
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm
        ]);
    }

    #[Route('/produits/edition/{id}', name: 'products_edit')]
    public function edit(Products $product, Request $request, SluggerInterface $slugger, EntityManagerInterface $em, PictureService $pictureService): Response
    {

        // On créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On récupère l'ancienne image
            $oldImage = $product->getImage();

            // Récupérer le fichier image
            $newimage = $productForm->get('image')->getData();

            // Si nouvelles image envoyée
            if ($newimage) {

                // Supprimer l'ancienne image qui existe
                if ($oldImage) {
                    $pictureService->delete($oldImage, 'articles');
                }

                // Définir le dossier de destination
                $folder = 'articles';

                // Appeler le service d'ajout pour gérer l'image
                $fichier = $pictureService->add($newimage, $folder, 300, 350);

                // Appeler le setter pour hydrater la propriété image
                $product->setImage($fichier);
            }

            // On stocke le produit
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
    }

    #[Route('/produits/suppression/{id}', name: 'products_delete')]
    public function delete(Products $product, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {

            // Récupérer l'image associée
            $image = $product->getImage();

            // Supprimer l'image si elle existe
            if ($image) {
                // Passer le nom du fichier et le dossier au service de suppression
                $pictureService->delete($image, 'articles');
            }

            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Produit supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression du produit');
        }

        return $this->redirectToRoute('admin_products');
    }
}
