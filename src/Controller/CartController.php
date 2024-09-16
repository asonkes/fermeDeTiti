<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Products $product, SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        // On récupère la session
        $panier = $session->get('panier', []);

        // On initialise des variables
        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            $total += $product->getPrice() * $quantity;
        }

        //dd($total);

        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        // [] ==> si pas de panier ==> tableau vide
        $panier = $session->get('panier', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore
        // Sinon on incrémente sa quantité
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);

        // On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('remove/{id}', name: 'remove')]
    public function remove(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On retire le produit du panier s'il n'y a qu'un exemplaire
        // Sinon on décrémente sa quantité
        // Et ici, on ne vérifie pas s'il est vide acr si vide, on ne fait rien
        if (!empty($panier[$id])) {

            if ($panier[$id] > 1) {

                $panier[$id]--;
            } else {

                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        // On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit 
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On supprime le produit du panier
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On hydrate(sauvegarde) la nouvelle information
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): Response
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}
