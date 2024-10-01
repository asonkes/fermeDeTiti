<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/favoris', name: 'favoris_')]
class FavorisController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Products $product, Products $products, SessionInterface $session, ProductsRepository $productsRepository, Request $request): Response
    {
        // Récupérer les favoris de la session
        $favoris = $session->get('favoris', []);
        $favorisIds = array_keys($favoris);

        // Récupérer la page actuelle depuis l'URL
        $page = $request->query->getInt('page', 1);

        // Limite des articles par page
        $limit = 8;

        // Récupération des produits paginés ou liste vide si aucun favori
        $products = $productsRepository->findFavorisPaginated($favorisIds, $page, $limit);

        // Préparer les données pour Twig
        $data = [];
        foreach ($products['items'] as $product) {
            $category = $product->getCategories(); // Récupérer la catégorie
            $data[] = [
                'product' => $product,
                'category' => $category,
            ];
        }

        return $this->render('favoris/index.html.twig', [
            'data' => $data, // Passer les produits formatés avec les catégories
            'products' => $products,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit
        $id = $product->getId();

        // On récupère les favoris existants
        $favoris = $session->get('favoris', []);

        // On ajoute le favori
        if (empty($favoris[$id])) {
            $favoris[$id] = true;
        }

        // On hydrate le favori
        $session->set('favoris', $favoris);

        return $this->redirectToRoute('favoris_index');
    }

    #[Route('/addRedirect/{id}/{slug}', name: 'addRedirect')]
    public function addRedirect(Products $product, SessionInterface $session, Request $request): Response
    {
        // On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $favoris = $session->get('favoris', []);

        if (empty($favoris[$id])) {
            $favoris[$id] = true;
        } else {
            $this->addFlash('danger', 'Vous avez déjà choisit cet article comme favori');
        }

        // On sauvegarde(hydrate) les nouvelles informations
        $session->set('favoris', $favoris);

        // Récupérer l'URL de la page d'origine
        $referer = $request->headers->get('referer');

        // Si l'URL de la page précédente est disponible, on y redirige l'utilisateur
        if ($referer) {
            return $this->redirect($referer);
        }

        // Si aucune catégorie n'est trouvée, on peut rediriger vers une page par défaut
        return $this->redirectToRoute('home');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id
        $id = $product->getId();

        // On récupère les favoris existant
        $favoris = $session->get('favoris', []);

        // On va supprimer le favori
        if (!empty($favoris[$id])) {
            // unset (on retire l'id du tableau des favoris)
            unset($favoris[$id]);
        }

        // On hydrate le favori
        $session->set('favoris', $favoris);

        return $this->redirectToRoute('favoris_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): Response
    {
        // On récupère les favoris existants
        $session->remove('favoris');

        return $this->redirectToRoute('favoris_index');
    }
}
