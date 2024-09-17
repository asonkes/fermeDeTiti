<?php

namespace App\Controller;


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
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
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

        // On remplit la commande
        $order->setUsers($this->getUser());
        $order->setReference(uniqId());

        // On parcourt le panier pour créer les détails de la commande
        // On a un tableau avec plusieurs produits dedans donc "foreach"
        // $item === produit
        foreach ($panier as $item => $quantity) {

            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $product = $productsRepository->find($item);

            $price = $product->getPrice();

            // On créé le détail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('success', 'Votre commande a été créé avec succès!');

        return $this->redirectToRoute('home');
    }
}
