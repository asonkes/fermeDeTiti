<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/favoris', name: 'favoris_')]
class FavorisController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        // On récupère la session
        $favoris = $session->get('favoris', []);

        // On initialise les variables
        $data = [];

        // Puisque plusieurs data ==> foreach
        foreach ($favoris as $id => $quantity) {
            $product = $productsRepository->find($id);

            $data[] = [
                'product' => $product
            ];
        }

        return $this->render('favoris/index.html.twig', [
            'data' => $data
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

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id
        $id = $product->getId();

        // On récupère les favoris existant
        $favoris = $session->get('favoris', []);

        // On va supprimer le favori
        if (!empty($favoris[$id])) {
            // unset (on retire l'id du tu tableau des favoris)
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
