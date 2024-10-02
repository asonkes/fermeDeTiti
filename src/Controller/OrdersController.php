<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commandes', name: 'orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(Users $user, SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // On récupère le panier
        $panier = $session->get('panier', []);

        // On vérifie que le panier n'est pas vide
        if ($panier === []) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('home');
        }

        // Le panier n'est pas vide, on créé la commande
        $order = new Orders();

        // On remplit la commande avec l'utilisateur connecté
        $user = $this->getUser();
        $order->setUsers($user);

        // Générer la référence de commande
        // Extraire les trois premières lettres du nom de l'utilisateur, ou le nom entier si c'est court
        $lastname = strtoupper(substr($user->getLastname(), 0, 3));

        // Générer un nombre aléatoire entre 1000 et 9999
        $randomNumber = random_int(1000, 9999);

        // Générer la référence de commande formatée
        $orderReference = sprintf('CMD-%s-%s-%d', date('Ymd'), $lastname, $randomNumber);
        $order->setReference($orderReference);

        // Calcul du total et ajout des détails de la commande
        $total = 0;

        // On parcourt le panier pour créer les détails de la commande
        foreach ($panier as $item => $quantity) {
            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $product = $productsRepository->find($item);
            $price = $product->getPrice();

            // Calcul du sous-total
            $subtotal = $price * $quantity;
            $total += $subtotal;

            // On créé le détail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            // On ajoute les détails à la commande
            $order->addOrdersDetail($orderDetails);
            $order->setTotal($total);
        }

        // Sauvegarder la commande en base de données
        $em->persist($order);
        $em->flush();

        // Vider le panier après la commande
        $session->remove('panier');

        // Message de succès
        $this->addFlash('success', 'Votre commande a été créée avec succès!');

        // Redirection vers la page d'accueil
        return $this->redirectToRoute('home');
    }
}
