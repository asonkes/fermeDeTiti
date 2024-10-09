<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Form\ValidateFormType;
use App\Service\GeocodingService;
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
    private GeocodingService $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    #[Route('/', name: 'index')]
    public function index(Users $user, Orders $order, OrdersRepository $ordersRepository, Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // le $user permet de préremplir le formulaire.
        $form = $this->createForm(ValidateFormType::class, $user);

        // Récupérer la dernière commande de l'utilisateur connecté qui est en attente de paiement
        $order = $ordersRepository->findOneBy(
            ['users' => $user, 'status' => 'en attente de paiement'], // Condition pour la commande
            ['id' => 'DESC'] // Trier par ID décroissant pour obtenir la plus récente
        );

        // Vérifier si l'utilisateur a bien une commande en attente
        if (!$order) {
            $this->addFlash('danger', 'Aucune commande en attente de paiement trouvée pour cet utilisateur.');
            return $this->redirectToRoute('home');
        }

        // Calculer les sous-totaux, frais de livraison et totaux pour cette commande
        $subtotal = $order->getSubtotal();

        // Récupérer les informations de l'utilisateur pour le calcul de la distance
        $address = $user->getAddress();
        $zipcode = $user->getZipcode();
        $city = $user->getCity();

        // Obtenir les coordonnées de l'utilisateur
        $userCoordinates = $this->geocodingService->getCoordinates($address, $zipcode, $city);

        // Informations de la ferme (fixes)
        $farmAddress = 'rue noir mouchon, 15';
        $farmZipcode = '7850';
        $farmCity = 'enghien';

        // Obtenir les coordonnées de la ferme
        $farmCoordinates = $this->geocodingService->getCoordinates($farmAddress, $farmZipcode, $farmCity);

        // Calculer la distance
        $distance = $this->calculateDistance($userCoordinates, $farmCoordinates);

        // Calculer les frais de livraison
        $deliveryFee = $this->calculateDeliveryFee($distance);

        // Calcul du total et ajout des détails de la commande
        $total = 0.00;

        // Calculer le total
        $total = $subtotal + $deliveryFee;
        $order->setTotal($total);

        // Sauvegarder les changements dans la base de données
        $em->persist($order);
        $em->flush();

        // Redirection vers la page d'accueil ou retour de la vue avec la commande
        return $this->render('orders/index.html.twig', [
            'user' => $user,
            'order' => $order,
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'total' => $total,
            'form' => $form->createView(),
            'distance' => $distance
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
        $subtotal = 0.00;

        // On parcourt le panier pour créer les détails de la commande
        foreach ($panier as $item => $quantity) {

            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $product = $productsRepository->find($item);
            $price = $product->getPrice();

            // Calcul du sous-total
            $subtotal += $price * $quantity;

            // On créé le détail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            // On ajoute les détails à la commande
            $order->addOrdersDetail($orderDetails);
        }

        $order->setSubTotal($subtotal);

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

    private function calculateDistance(array $coord1, array $coord2): string
    {
        $earthRadius = 6371; // Rayon de la terre en km

        // Convertir les degrés en radians
        $latFrom = deg2rad($coord1['latitude']);
        $lonFrom = deg2rad($coord1['longitude']);
        $latTo = deg2rad($coord2['latitude']);
        $lonTo = deg2rad($coord2['longitude']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Retourner la distance en kilomètres
    }

    // Méthode pour calculer les frais de livraison en fonction de la distance
    private function calculateDeliveryFee(float $distance): string
    {
        $baseFee = 0.00; // Livraison gratuite jusqu'à 5 km
        $additionalFee1 = 5.00; // Frais pour la distance entre 5 et 10 km
        $additionalFee2 = 10.00; // Frais pour la distance entre 10 et 15 km
        $maxDistance = 15.00; // Distance maximale pour livraison

        if ($distance <= 5) {
            return $baseFee; // Gratuit
        } elseif ($distance > 5 && $distance <= 10) {
            return $additionalFee1; // 5€ de frais
        } elseif ($distance > 10 && $distance <= 15) {
            return $additionalFee2; // 10€ de frais
        } else {
            return 0.00;
        }
    }
}
