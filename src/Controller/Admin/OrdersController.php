<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Form\OrdersFormType;
use App\Form\OrdersDetailsFormType;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/commandes/edition/{id}', name: 'orders_edit')]
    public function edit(Orders $order, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On créé le formulaire
        $ordersForm = $this->createForm(OrdersFormType::class, $order);

        $ordersForm->handleRequest($request);

        // On regarde si le formulaire est soumis et valide
        if ($ordersForm->isSubmitted() && $ordersForm->isValid()) {
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Commande modifiée avec succès');

            return $this->redirectToRoute('admin_orders');
        }

        return $this->render('admin/orders/edit.html.twig', [
            'orders' => $order,
            'ordersForm' => $ordersForm
        ]);
    }

    #[Route('/commandes/suppression/{id}', name: 'orders_delete')]
    public function delete(Orders $order, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Ajouter un token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $em->remove($order);
            $em->flush();

            $this->addFlash('success', 'Commande supprimée avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression de la commande');
        }

        return $this->redirectToRoute('admin_orders');
    }
}
