<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin', name: 'admin_')]
class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    function index(Categories $categories, CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $categoriesRepository->findAll([]);

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categories/ajout', name: 'categories_add')]
    function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Categories();

        // On créé le formulaire
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);

        // On traite la requête du formulaire
        $categoryForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            // On génère le slug 
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            // On récupère l'image du formulaire
            $image = $categoryForm->get('image')->getData();

            if ($image) {
                //  Définir le dossier de destination
                $folder = 'categories';

                // Appeler le service d'ajout pour gérer l'image
                $fichier = $pictureService->add($image, $folder, 250, 350);

                // Appeler le setter pour hydrater la propriété image
                $category->setImage($fichier);
            }

            // On stocke
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès');

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/add.html.twig', [
            'categoryForm' => $categoryForm
        ]);
    }

    #[Route('/categories/edition/{id}', name: 'categories_edit')]
    function edit(Categories $category, Request $request, SluggerInterface $slugger, PictureService $pictureService, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $category);

        // On créé le formulaire 
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);

        // On traite la requête du formulaire
        $categoryForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            // On génère le slug
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            // On récupère l'ancienne image
            $oldImage = $category->getImage();

            // On récupère le fichier image
            $newImage = $categoryForm->get('image')->getData();

            // Si nouvelles image envoyée
            if ($newImage) {

                // Supprimer l'ancienne image qui existe
                if ($oldImage) {
                    $pictureService->delete($oldImage, 'categories');
                }

                // Définir le dossier de destination
                $folder = 'categories';

                // Appeler le service d'ajout pour gérer l'image
                $fichier = $pictureService->add($newImage, $folder, 300, 350);

                // Appeler le setter pour hydrater la propriété image
                $category->setImage($fichier);
            }

            // On stocke le produit
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin\categories\edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category' => $category
        ]);
    }

    #[Route('/categories/suppression/{id}', name: 'categories_delete')]
    public function delete(Categories $category, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {

            // Récupérer l'image associée
            $image = $category->getImage();

            // Supprimer l'image si elle existe
            if ($image) {
                // Passer le nom du fichier et le dossier au service de suppression
                $pictureService->delete($image, 'categories');
            }

            // On supprime l'élément catégorie de la base de données
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', 'Produit supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression du produit');
        }

        return $this->redirectToRoute('admin_categories');
    }
}
