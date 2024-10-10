<?php

namespace App\Controller;


use Stripe\Stripe;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Entity\Products;
use App\Repository\OrdersDetailsRepository;
use Stripe\Checkout\Session;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/paiement', name: 'payment_')]
class PaymentController extends AbstractController
{
    #[Route('/{total}', name: 'index')]
    public function index(string $total, Orders $order, Products $product, Ordersdetails $ordersDetails, OrdersRepository $ordersRepository): RedirectResponse
    {
        $productStripe = [];

        // Configuration de Stripe avec votre clé secrète
        Stripe::setApiKey($_ENV['STRIPE_SECRETKEY']);

        // Récupérer le total de l'utilisateur qui a passé la commande
        $order = $ordersRepository->findOneBy(['total' => $total]);

        if (!$order) {
            return $this->redirectToRoute('orders_index');
            $this->addFlash('danger', "Nous avons rencontré un problème avec la commande, veuillez rééessayer pour le paiement, si l'erreur persiste, veuillez nous contacter.");
        }

        foreach ($order->getOrdersdetails()->getValues() as $ordersDetails) {
            // Récupérer le total de l'utilisateur qui a passé la commande

            //dd($quantity);
            $productId = $product->getId();
            //dd($productId);

            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $ordersDetails->getPrice(),
                    //dd($ordersDetails->getPrice()),
                    'product_data' => [
                        'name' => $ordersDetails->getProducts($product->getName()),
                        //dd($ordersDetails->getProducts($product->getName()))
                    ]
                ],
                'quantity' => $ordersDetails->getquantity(),
                dd($ordersDetails->getquantity()),
            ];
        }

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            // Utilisation de la référence de la commande
                            'name' => 'Commande' . $order->getReference(),
                        ],
                        // On convertit en centimes pour stripe
                        'unit_amount' => $total * 100
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], true),
            'cancel_url' => $this->generateUrl('payment_cancel', [], true),
        ]);

        // Rediriger vers la session de paiement stripe
        return new RedirectResponse($checkout_session->url);
    }
}
