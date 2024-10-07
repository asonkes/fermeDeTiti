<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class OrdersController extends AbstractController
{
    #[Route('/commandes', name: 'orders')]
    public function index(Orders $orders, OrdersRepository $ordersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $orders = $ordersRepository->findAll([]);

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
