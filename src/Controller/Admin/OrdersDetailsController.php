<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\OrdersDetails;
use App\Form\OrdersDetailsFormType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\OrdersDetailsFormTypePhpType;
use App\Repository\OrdersDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class OrdersDetailsController extends AbstractController
{
    #[Route('/details_des_commandes', name: 'ordersDetails')]
    public function index(OrdersDetails $ordersDetails, OrdersDetailsRepository $ordersDetailsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ordersDetails = $ordersDetailsRepository->findAll([]);

        return $this->render('admin/ordersDetails/index.html.twig', [
            'ordersDetails' => $ordersDetails
        ]);
    }

    #[Route('/details_des_commandes/edition/{id}', name: 'ordersDetails_edit')]
    public function edit(Orders $orders, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $orders = new Orders();

        // On va créer le formulaire
        $ordersDetailsForm = $this->createForm(OrdersDetailsFormType::class, $orders);

        // On traite le formulaire
        $ordersDetailsForm->handleRequest($request);

        // On va voir si le forulaire ets soumis et valide
        if ($ordersDetailsForm->isSubmitted() && $ordersDetailsForm->isValid()) {
            $em->persist($orders);
            $em->flush();

            $this->addFlash('success', 'Commande modifiée avec succès');

            return $this->redirectToRoute('admin_ordersDetails');
        }

        return $this->redirectToRoute('admin_orderDetails', [
            'OrdersDetailsForm' => $ordersDetailsForm,
            'orders' => $orders
        ]);
    }

    #[Route('/details_des_commandes/suppression/{id}', name: 'ordersDetails_delete')]
    public function delete(Orders $orders, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Ajouter un token pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $orders->getId(), $request->request->get('_token'))) {
            $em->remove($orders);
            $em->flush();

            $this->addFlash('success', 'Les détails de cette commande ont été supprimées avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression des détails de cette commande.');
        }

        return $this->redirectToRoute('admin_ordersDetails');
    }
}
