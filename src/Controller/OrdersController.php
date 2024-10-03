<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Form\ValidateFormType;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commandes', name: 'orders_')]
class OrdersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Users $user, Orders $order, OrdersRepository $ordersRepository, Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // le $user permet de préremplir le formulaire.
        $form = $this->createForm(ValidateFormType::class, $user);

        // Récupérer la commande de l'utilisateur 
        $order = $ordersRepository->findOneBy(['users' => $user]);

        // Récupérer la commande de l'utilisateur
        $subtotal = $order->getSubtotal();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // On sauvegarde les modifications de l'utilisateur
                $this->addFlash('success', 'Vos données ont bien été modifiées');

                $em->persist($user);

                $em->flush();
            } else {
                $this->addFlash('danger', "Erreurs dans vos modifications, elles n'ont pas été enregistrées ! Veuillez les modifier svp !!!");
            }
        }

        // Redirection vers la page d'accueil
        return $this->render('orders/index.html.twig', [
            'user' => $user,
            'order' => $order,
            'subtotal' => $subtotal,
            'form' => $form->createView()
        ]);
    }

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

        // Initialiser le statut de la commande à "en attente de paiement"
        $order->setStatus('en attente de paiement');

        // Calcul du total et ajout des détails de la commande
        $subtotal = 0;

        // On parcourt le panier pour créer les détails de la commande
        foreach ($panier as $item => $quantity) {
            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $product = $productsRepository->find($item);
            $price = $product->getPrice();

            // Calcul du sous-total
            $subtotal = $price * $quantity;
            $subtotal += $subtotal;

            // On créé le détail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            // On ajoute les détails à la commande
            $order->addOrdersDetail($orderDetails);
            $order->setSubTotal($subtotal);
        }

        // Sauvegarder la commande en base de données
        $em->persist($order);
        $em->flush();

        // Vider le panier après la commande
        $session->remove('panier');

        // Message de succès
        $this->addFlash('success', 'Votre commande a été enregistrée avec succès!');

        // Redirection vers la page commandes
        return $this->redirectToRoute('orders_index');
    }

    #[Route('/validate', name: 'validate')]
    public function validate(): Response
    {
        // Redirection vers la page d'accueil
        return $this->render('orders/validate.html.twig');
    }
}
