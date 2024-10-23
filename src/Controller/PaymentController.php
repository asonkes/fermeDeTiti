<?php

namespace App\Controller;


use Stripe\Stripe;

use App\Entity\Orders;
use App\Entity\Products;
use Stripe\Checkout\Session;
use App\Entity\OrdersDetails;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/paiement', name: 'payment_')]
class PaymentController extends AbstractController
{
    #[Route('/{reference}', name: 'index')]
    public function index(string $reference, Orders $order, Products $product, Ordersdetails $ordersDetails, OrdersRepository $ordersRepository): RedirectResponse
    {
        $productStripe = [];

        // Récupérer le total de l'utilisateur qui a passé la commande
        $order = $ordersRepository->findOneBy(['reference' => $reference]);

        if (!$order) {
            return $this->redirectToRoute('orders_index');
            $this->addFlash('danger', "Nous avons rencontré un problème avec la commande, veuillez rééessayer pour le paiement, si l'erreur persiste, veuillez nous contacter.");
        }

        foreach ($order->getOrdersdetails()->getValues() as $ordersDetails) {
            $product = $ordersDetails->getProducts();

            // Récupérer le nom du produit
            $productName = $product->getName();

            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (($ordersDetails->getPrice()) * 100),
                    'product_data' => [
                        'name' => $productName,
                    ]
                ],
                'quantity' => $ordersDetails->getquantity(),
            ];
        }

        $productStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => (($order->getDeliveryFee()) * 100),
                'product_data' => [
                    'name' => 'Livraison à domicile',
                ]
            ],
            'quantity' => 1,
        ];

        // Configuration de Stripe avec votre clé secrète
        Stripe::setApiKey($_ENV['STRIPE_PUBLISHABLE_KEY']);

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $productStripe
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', ['reference' => $order->getReference()], UrlGeneratorInterface::ABSOLUTE_URL),

            'cancel_url' => $this->generateUrl('payment_cancel', ['reference' => $order->getReference()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // Rediriger vers la session de paiement stripe
        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/success/{reference}', name: 'success')]
    public function stripeSuccess(string $reference, Orders $order, OrdersDetails $ordersDetails, Products $product, OrdersRepository $ordersRepository, EntityManagerInterface $em): Response
    {
        $order = $ordersRepository->findOneBy(['reference' => $reference]);

        if ($order) {
            $status = $order->getStatus();

            $order->setStatus('payé');
            $em->persist($order);
            $em->flush();

            $ordersDetails = $order->getOrdersDetails()->getValues($ordersDetails);

            // On Récupère la quantité des détails de la commande
            foreach ($order->getOrdersdetails()->getValues() as $ordersDetails) {
                $quantity = $ordersDetails->getQuantity();

                $product = $ordersDetails->getProducts();;

                $stock = $product->getStock();

                $product->setStock($stock - $quantity);

                $em->persist($product);
                $em->flush();
            }
        }

        $this->addFlash('success', 'Votre paiement a été effectué avec succès, un grand merci pour votre confiance. Un mail vous a été envoyé.');

        return $this->redirectToRoute('home', [
            'order' => $order,
            'ordersDetails' => $ordersDetails
        ]);
    }


    #[Route('/annulation/{reference}', name: 'cancel')]
    public function stripeCancel(string $reference, Orders $order): Response
    {
        $this->addFlash('danger', "Votre paiement ne s'est pas effectuée correctement. Veuillez réessayer. Si vous rencontrer encore un problème, veuillez nous contacter à l'adresse : 'infos_warelles@gmail.com. Merci' ");

        return $this->redirectToRoute('orders_index', [
            'order' => $order
        ]);
    }
}
